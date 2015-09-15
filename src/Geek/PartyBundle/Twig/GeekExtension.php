<?php
/**
 * Коршунов Георгий <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Twig;

use AppKernel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Geek\PartyBundle\Entity\Party;
use Geek\PartyBundle\Entity\PartyTheme;
use Geek\PartyBundle\Entity\Repository\PartyRepository;
use Geek\PartyBundle\Entity\Repository\PartyThemeRepository;
use Geek\PartyBundle\Entity\Repository\WorkRepository;
use Geek\PartyBundle\Entity\User;
use Geek\PartyBundle\Entity\Work;
use Symfony\Component\Security\Core\SecurityContext;

class GeekExtension extends \Twig_Extension
{
    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $context;
    /** @var \AppKernel */
    private $kernel;
    /** @var array */
    private $ratingsCache;

    function __construct(SecurityContext $context, AppKernel $kernel)
    {
        $this->context = $context;
        $this->kernel = $kernel;
        $this->ratingsCache = [];
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
            , new \Twig_SimpleFunction('is_authorised', [$this, 'isAuthorised'])
            , new \Twig_SimpleFunction('is_admin', [$this, 'isAdmin'])
            , new \Twig_SimpleFunction('file_exists', [$this, 'fileExists'])
            , new \Twig_SimpleFunction('is_work_uploaded', [$this, 'isWorkUploaded'])
            , new \Twig_SimpleFunction('get_current_party', [$this, 'getCurrentParty'])
            , new \Twig_SimpleFunction('format_date', [$this, 'formatDate'])
            , new \Twig_SimpleFunction('get_party_theme', [$this, 'getPartyTheme'])
            , new \Twig_SimpleFunction('calculate_project_rating', [$this, 'calculateProjectRating'])
            , new \Twig_SimpleFunction('is_winner', [$this, 'isWinner'])
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

    public function isAuthorised()
    {
        return $this->context->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function isWorkUploaded(Work $work)
    {
        /** @var WorkRepository $workRepo */
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

    public function formatDate($date)
    {
        if (!is_object($date)) {
            $date = new \DateTime($date);
        }
        return $date->format('Y-m-d H:i');
    }

    /**
     * @param Party $party
     * @return null|string
     */
    public function getPartyTheme(Party $party)
    {
        /** @var PartyThemeRepository $partyThemeRepo */
        $partyThemeRepo = $this->kernel->getContainer()->get('doctrine')
            ->getManager()
            ->getRepository('GeekPartyBundle:PartyTheme');

        $themes = $partyThemeRepo->findAllSortedByVotes($party);
        if ($themes) {
            /** @var PartyTheme $theme */
            $theme = $themes[0];

            return $theme->getText();
        }

        return null;
    }

    /**
     * @param Work $project
     * @param bool|false $userRatings
     * @return float
     */
    public function calculateProjectRating(Work $project, $userRatings = false)
    {
        $partyId = $project->getParty()->getId();
        if (!isset($this->ratingsCache[$partyId][(string)$userRatings])) {
            $em = $this->getEntityManager();
            /** @var PartyRepository $repo */
            $repo = $em->getRepository('GeekPartyBundle:Party');
            $this->ratingsCache[$partyId][(string)$userRatings] = $userRatings ?
                $repo->getUserRatings($project->getParty()) :
                $repo->getRatings($project->getParty());
        }

        if (!isset($this->ratingsCache[$partyId][(string)$userRatings][$project->getId()])) {
            return 0.0;
        }

        return $this->ratingsCache[$partyId][(string)$userRatings][$project->getId()];
    }

    /**
     * @param Work $project
     * @param bool|false $userRatings
     * @return bool
     */
    public function isWinner(Work $project, $userRatings = false)
    {
        $this->calculateProjectRating($project, $userRatings);

        $partyId = $project->getParty()->getId();
        $partyRatings = $this->ratingsCache[$partyId][(string)$userRatings];
        foreach ($partyRatings as $projectId => $rating) {
            /** @var WorkRepository $currentProject */
            $projectRepo = $this->getEntityManager()->getRepository('GeekPartyBundle:Work');
            /** @var Work $currentProject */
            $currentProject = $projectRepo->find($projectId);
            if (!$currentProject) {
                continue;
            }
            if (!$projectRepo->isWorkUploaded($currentProject)) {
                continue;
            }
            if ($currentProject->getId() != $project->getId()) {
                return false;
            }
            return true;
        }
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        $doctrine = $this->kernel->getContainer()->get('doctrine');
        return $doctrine->getManager();
    }
}