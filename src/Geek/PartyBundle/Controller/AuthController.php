<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
    , Symfony\Component\HttpFoundation\Response
    ;

class AuthController extends Controller
{
    /**
     * Login check
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        return new Response();
    }
    
    /**
     * Login check
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkAction()
    {
        return new Response();
    }

    /**
     * Login check
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logoutAction()
    {
        return new Response();
    }
}
