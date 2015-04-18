<?php

namespace Geek\PartyBundle\Controller;

use Geek\PartyBundle\Entity\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Base\BaseController
{
    /**
     * Главная страница
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        /** @var ArticleRepository $articlesRepo */
        $articlesRepo = $this->getDoctrine()->
            getRepository('GeekPartyBundle:Article');

        $from = $request->get('from', 0);

        return $this->render('GeekPartyBundle:Main:index.html.twig', [
            'text' => $this->findTextBlock('index'),
            'articles' => $articlesRepo->fetchPage([], $from),
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
}
