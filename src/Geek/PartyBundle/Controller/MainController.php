<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('GeekPartyBundle:Main:index.html.twig');
    }

    public function peopleAction()
    {
        return $this->render('GeekPartyBundle:Main:people.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('GeekPartyBundle:Main:about.html.twig');
    }

    public function worksAction()
    {
        return $this->render('GeekPartyBundle:Main:works.html.twig');
    }

    public function testAction()
    {
        return $this->render('GeekPartyBundle:Main:test.html.twig');
    }
}
