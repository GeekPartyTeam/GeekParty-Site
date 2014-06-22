<?php
/**
 * Коршунов Георгий <georgy.k@propellerads.com>
 */

namespace Geek\PartyBundle\Twig;

use AppKernel;
use Geek\PartyBundle\Entity\User;
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
            , new \Twig_SimpleFunction('file_exists', [$this, 'fileExists'])
        ];
    }

    public function isOwnerOrAdmin(User $user = null)
    {
        if ($this->context->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $user && $user === $this->context->getToken()->getUser();
    }

    public function fileExists($path)
    {
        return file_exists($this->kernel->getRootDir() . '/../public_html' . $path);
    }
}