<?php
/**
 * Коршунов Георгий <georgy.k@propellerads.com>
 */

namespace Geek\PartyBundle\Twig;

use Geek\PartyBundle\Entity\User;
use Symfony\Component\Security\Core\SecurityContext;

class GeekExtension extends \Twig_Extension
{
    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $context;

    function __construct(SecurityContext $context)
    {
        $this->context = $context;
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
        ];
    }

    public function isOwnerOrAdmin(User $user = null)
    {
        if ($this->context->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $user && $user === $this->context->getToken()->getUser();
    }
}