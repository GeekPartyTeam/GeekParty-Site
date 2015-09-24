<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Listener;

use Geek\PartyBundle\Entity\Repository\PartyRepository;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class TwigGlobalVarsLitener
{
    /** @var \Twig_Environment */
    private $twig;
    /** @var PartyRepository */
    private $partyRepository;

    public function __construct(\Twig_Environment $twig, PartyRepository $partyRepository)
    {
        $this->twig = $twig;
        $this->partyRepository = $partyRepository;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $currentParty = $this->partyRepository->getCurrentParty();
        $this->twig->addGlobal('current_party', $currentParty);

        $now = $event->getRequest()->get('now', date('Y-m-d H:i:s'));
        $this->twig->addGlobal('now', $now);
    }
}