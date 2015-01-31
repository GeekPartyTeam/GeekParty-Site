<?php
/**
 * Коршунов Георгий <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Twig;

use AppKernel;
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

    public function fileExists($path)
    {
        return file_exists($this->kernel->getRootDir() . '/../public_html' . $path);
    }

    public function isWorkUploaded(Work $work)
    {
        return $this->fileExists('/works/' . $work->getParty()->getId() . '/' . $work->getId() . '/index.html');
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
}