<?php

namespace Geek\PartyBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
    , Symfony\Component\HttpFoundation\Response
    , FOS\RestBundle\Routing\ClassResourceInterface
    ;

class WorkController extends Controller implements ClassResourceInterface
{
    public function cgetAction()
    {
        return $this->getDoctrine()->getManager()->getRepository('GeekPartyBundle:Work')->findAll();
    } // "get_works"     [GET] /works    

    public function getAction($slug)
    {
        return $this->getDoctrine()->getManager()->getRepository('GeekPartyBundle:Work')->find($slug);
    } // "get_work"      [GET] /works/{slug}

    public function postAction()
    {} // "post_works"    [POST] /works

    public function patchAction()
    {} // "patch_works"   [PATCH] /works

    public function editAction($slug)
    {} // "edit_work"     [GET] /works/{slug}/edit

    public function putAction($slug)
    {} // "put_work"      [PUT] /works/{slug}

    public function deleteAction($slug)
    {} // "delete_work"   [DELETE] /works/{slug}
}
