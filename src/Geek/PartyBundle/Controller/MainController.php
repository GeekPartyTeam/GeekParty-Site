<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Response
    ;

class MainController extends BaseController
{
    /**
     * Главная страниа
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $text = $this->getDoctrine()
            ->getRepository('GeekPartyBundle:Text')
            ->findOneBy(['name' => 'index']);
        $articles = $this->getDoctrine()->
            getRepository('GeekPartyBundle:Article')
            ->findBy([], ['time' => 'DESC']);
        return $this->render('GeekPartyBundle:Main:index.html.twig', [
            'text' => $text,
            'articles' => $articles,
        ]);
    }

    /**
     * Список участников
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function peopleAction()
    {
        $params = [
            'teams' => $this->getDoctrine()->getRepository('GeekPartyBundle:Team')->findAll()
        ];
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $securityContext->getToken()->getUser();
            $team = $this->getDoctrine()
                ->getRepository('GeekPartyBundle:Team')
                ->findOneBy(['leader' => $user]);
            $params['user'] = $user;
            $params['team'] = $team;
            $params['works'] = $this->getDoctrine()->getRepository('GeekPartyBundle:Work')
                ->findBy(['team' => $params['team']]);
        }
        return $this->render('GeekPartyBundle:Main:people.html.twig', $params);
    }

    /**
     * Страница About
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
        return $this->render('GeekPartyBundle:Main:about.html.twig');
    }

    /**
     * Список работ
     * @param $party string Идентификатор пати — gp1, gp2 или gp3
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function worksAction($party)
    {
        $works = $this->getWorks();

        $params = [
            'works' => $works,
            'party' => $party
        ];

        return $this->render('GeekPartyBundle:Main:works.html.twig', $params);
    }

    /**
     * Страница работы
     * @param $party string Идентификатор пати — gp1, gp2 или gp3
     * @param $work string Строковый идентификатор работы
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workPageAction($party, $work)
    {
        $works = $this->getWorks();

        return $this->render('GeekPartyBundle:Main:work.html.twig', ['party' => $party, 'work' => $works[$party][$work]]);
    }

    /**
     * Тестовое действие
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction()
    {
        return $this->render('GeekPartyBundle:Main:test.html.twig');
    }

    public function adminAction()
    {
        // Make sure that 'index' and 'about' text blocks exists in DB
        /** @var \Geek\PartyBundle\Entity\Repository\Text $repo */
        $repo = $this->getDoctrine()->getManager()->getRepository('GeekPartyBundle:Text');
        return $this->render('GeekPartyBundle:Main:admin.html.twig', [
            'indexText' => $repo->fetch('index'),
            'aboutText' => $repo->fetch('about'),
        ]);
    }
}
