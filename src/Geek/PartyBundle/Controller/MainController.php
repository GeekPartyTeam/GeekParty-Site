<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Response
    ;

class MainController extends Base\BaseController
{
    /**
     * Главная страница
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $articles = $this->getDoctrine()->
            getRepository('GeekPartyBundle:Article')
            ->findBy([], ['time' => 'DESC']);
        return $this->render('GeekPartyBundle:Main:index.html.twig', [
            'text' => $this->findTextBlock('index'),
            'articles' => $articles,
        ]);
    }

    /**
     * Страница "About"
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
        return $this->render('GeekPartyBundle:Main:about.html.twig', [
            'text' => $this->findTextBlock('about')
        ]);
    }

    /**
     * Страница "Admin"
     * @return Response
     */
    public function adminAction()
    {
        return $this->render('GeekPartyBundle:Main:admin.html.twig', [
            'indexText' => $this->findTextBlock('index'),
            'aboutText' => $this->findTextBlock('about'),
        ]);
    }
}
