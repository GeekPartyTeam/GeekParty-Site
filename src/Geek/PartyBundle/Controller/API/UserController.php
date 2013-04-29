<?php

namespace Geek\PartyBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
    , Symfony\Component\HttpFoundation\Response
    , FOS\RestBundle\Routing\ClassResourceInterface
    ;

class UserController extends Controller implements ClassResourceInterface
{
    public function cgetAction()
    {
        return $this->getDoctrine()->getManager()->getRepository('GeekPartyBundle:User')->findAll();
    } // "get_users"     [GET] /users    

    public function getAction($slug)
    {
        return $this->getDoctrine()->getManager()->getRepository('GeekPartyBundle:User')->find($slug);
    } // "get_user"      [GET] /users/{slug}

    public function postAction()
    {} // "post_users"    [POST] /users

    public function patchAction()
    {} // "patch_users"   [PATCH] /users

    public function editAction($slug)
    {} // "edit_user"     [GET] /users/{slug}/edit

    public function putAction($slug)
    {} // "put_user"      [PUT] /users/{slug}

    public function deleteAction($slug)
    {} // "delete_user"   [DELETE] /users/{slug}
}
