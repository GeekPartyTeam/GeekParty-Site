<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Response
    ;

class MainController extends Base\BaseController
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
     * Страница About
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
        return $this->render('GeekPartyBundle:Main:about.html.twig');
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
