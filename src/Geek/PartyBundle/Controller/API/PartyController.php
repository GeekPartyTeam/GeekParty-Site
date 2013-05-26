<?php

namespace Geek\PartyBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
    , Symfony\Component\HttpFoundation\Response
    , FOS\RestBundle\Routing\ClassResourceInterface
    ;

class PartyController extends Controller implements ClassResourceInterface
{
    public function cgetAction()
    {
        return $this->getDoctrine()->getManager()->getRepository('GeekPartyBundle:Work')->findAll();
    } // "get_parties"     [GET] /parties    

    public function getAction($slug)
    {
        return $this->getDoctrine()->getManager()->getRepository('GeekPartyBundle:Work')->find($slug);
    } // "get_party"      [GET] /parties/{slug}

    public function postAction()
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $user = $em->find('GeekPartyBundle:Party', $request->get('id'));
        $form = $this->createForm(new PartyType(), $user);

        $form->bind($request);

        if ( ! $form->isValid()) {
            return $this->renderFormFailure("MyBundle:User:edit.html.twig", $form, array('user' => $user));
        }

        // do some business logic here

        $em->flush();

        return $this->formRedirect($form, $this->generateUrl('user_show', array('id' => $user->getId()), 201));
    } // "post_parties"    [POST] /parties

    public function putAction($slug)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $form = $this->createForm(new PartyType());

        $form->bind($request);

        if ( ! $form->isValid()) {
            return $this->renderFormFailure("MyBundle:User:edit.html.twig", $form, array('user' => $user));
        }

        // do some business logic here

        $em->flush();

        return $this->formRedirect($form, $this->generateUrl('user_show', array('id' => $user->getId()), 201));
    } // "put_party"      [PUT] /parties/{slug}

    public function deleteAction($slug)
    {} // "delete_party"   [DELETE] /parties/{slug}
}
