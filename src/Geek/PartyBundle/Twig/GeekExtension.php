<?php
/**
 * Коршунов Георгий <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Twig;

use AppKernel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Geek\PartyBundle\Entity\Repository\PartyRepository;
use Geek\PartyBundle\Entity\User;
use Geek\PartyBundle\Entity\Work;
use Symfony\Component\Security\Core\SecurityContext;

class GeekExtension extends \Twig_Extension
{
    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $context;
    /** @var \AppKernel */
    private $kernel;

    function __construct(SecurityContext $context, AppKernel $kernel)
    {
        $this->context = $context;
        $this->kernel = $kernel;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'geek_extension';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('is_owner_or_admin', [$this, 'isOwnerOrAdmin'])
            , new \Twig_SimpleFunction('is_admin', [$this, 'isAdmin'])
            , new \Twig_SimpleFunction('file_exists', [$this, 'fileExists'])
            , new \Twig_SimpleFunction('is_work_uploaded', [$this, 'isWorkUploaded'])
            , new \Twig_SimpleFunction('get_current_party', [$this, 'getCurrentParty'])
            , new \Twig_SimpleFunction('calculate_project_rating', [$this, 'calculateProjectRating'])
        ];
    }

    public function isOwnerOrAdmin(User $user = null)
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $user && $user === $this->context->getToken()->getUser();
    }

    public function isAdmin()
    {
        return $this->context->isGranted('ROLE_ADMIN');
    }

    public function isWorkUploaded(Work $work)
    {
        $workRepo = $this->kernel->getContainer()
            ->get('work.repo')
            ;
        return $workRepo->isWorkUploaded($work);
    }

    public function getCurrentParty()
    {
        $doctrine = $this->kernel->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $parties = $em->createQuery("SELECT p FROM GeekPartyBundle:Party p WHERE p.endTime > :time ORDER BY p.endTime ASC")
            ->setParameter('time', new \DateTime())
            ->getResult();

        if (count($parties) == 0) {
            $parties = $em->createQuery("SELECT p FROM GeekPartyBundle:Party p ORDER BY p.endTime DESC")
                ->getResult();
        }

        return count($parties) > 0 ? $parties[0] : null;
    }

    public function calculateProjectRating(Work $project)
    {
        // TODO: ratings by party
        static $ratings;

        if (!$ratings) {
            $ratings = [];

            $doctrine = $this->kernel->getContainer()->get('doctrine');
            /** @var EntityManager $em */
            $em = $doctrine->getManager();
            /** @var PartyRepository $repo */
            $repo = $em->getRepository('GeekPartyBundle:Party');
            $ratings = $repo->getRatings($project->getParty());
        }

        if (!isset($ratings[$project->getId()])) {
            return 0.0;
        }

        return $ratings[$project->getId()];
    }
}