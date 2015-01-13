<?php

namespace Geek\PartyBundle\Controller;

use Geek\PartyBundle\Entity\PartyTheme;
use Geek\PartyBundle\Entity\PartyThemeVote;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Party themes adding & voting
 */
class PartyThemeController extends Base\BaseController
{
    /**
     * Displays a form to create a new Work entity.
     *
     * @Route("/", name="themes_index")
     * @Template()
     */
    public function indexAction()
    {
        $currentParty = $this->getCurrentParty();
        return $this->themesList($currentParty->getThemeSubmissionStartTime(), $currentParty->getThemeSubmissionEndTime());
    }

    /**
     * Displays a form to create a new Work entity.
     *
     * @Route("/add", name="themes_add")
     * @Template()
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->get('session');
        $response = $this->redirect($this->generateUrl('themes_index'));

        $text = $request->get('text');
        if (!$text) {
            $session->getFlashBag()->add(
                'error',
                'Пустой текст'
            );
            return $response;
        }

        $theme = new PartyTheme();
        $theme->setParty($this->getCurrentParty());
        $theme->setText($text);
        $theme->setUser($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($theme);
        $em->flush();

        return $response;
    }

    private function themesList($startDate, $endDate)
    {
        $currentParty = $this->getCurrentParty();
        $now = new \DateTime();

        if ($now < $startDate || $now > $endDate) {
            return $this->redirect($this->generateUrl('geek_index'));
        }

        $themes = $this->getDoctrine()
            ->getRepository('GeekPartyBundle:PartyTheme')
            ->findBy(['party' => $currentParty]);

        return $this->arrayResponse(['themes' => $themes]);
    }

    /**
     * Displays a form to create a new Work entity.
     *
     * @Route("/vote", name="themes_votes")
     * @Template()
     * @Method("GET")
     */
    public function votesAction()
    {
        $currentParty = $this->getCurrentParty();
        return $this->themesList($currentParty->getThemeVotingStartTime(), $currentParty->getThemeVotingEndTime());
    }

    /**
     * Displays a form to create a new Work entity.
     *
     * @Route("/vote", name="themes_vote")
     * @Template()
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function voteAction(Request $request)
    {
        $response = $this->redirect($this->generateUrl('themes_index'));

        try {

            $themeId = $request->get('theme_id');
            if (!$themeId) {
                throw new \Exception('Не выйдет!');
            }

            $em = $this->getDoctrine()->getManager();
            /** @var PartyTheme $theme */
            $theme = $em->getRepository('GeekPartyBundle:PartyTheme')->find($themeId);

            // TODO: защита от повторного голосования

            $vote = new PartyThemeVote();
            $vote->setUser($this->getUser());
            $vote->setTheme($theme);
            $em->persist($vote);
            $em->flush();

        } catch (\Exception $e) {
            /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
            $session = $this->get('session');
            $session->getFlashBag()->add(
                'error',
                $e->getMessage()
            );
            return $response;
        }

        return $response;
    }

    /**
     * @Route("/remove", name="themes_remove")
     * @Method("POST")
     * @param Request $request
     * @throws \Exception
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request)
    {
        $response = $this->redirect($request->headers->get('referer'));
        if (!$this->isAdmin()) {
            return $response;
        }

        $themeId = $request->get('theme_id');
        if (!$themeId) {
            throw new \Exception('Не выйдет!');
        }

        $em = $this->getDoctrine()->getManager();
        /** @var PartyTheme $theme */
        $theme = $em->getRepository('GeekPartyBundle:PartyTheme')->find($themeId);
        $em->remove($theme);
        $em->flush();

        return $response;
    }
} 