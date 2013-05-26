<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
    , Symfony\Component\HttpFoundation\Response
    ;

class BaseController extends Controller
{
    public function isShowTime()
    {
        $now = new \DateTime();
        $showtime = new \DateTime("2013-04-14 12:00");
        return $now >= $showtime;
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        return parent::render($view, $this->arrayResponse($parameters), $response);
    }

    public function arrayResponse(array $parameters)
    {
        $parameters['showtime'] = $this->isShowTime();
        return $parameters;
    }
}
