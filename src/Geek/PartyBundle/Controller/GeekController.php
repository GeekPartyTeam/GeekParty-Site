<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GeekController extends Controller
{
    public function indexAction()
    {
        return $this->render('GeekPartyBundle:Main:index.html.twig');
    }
}
