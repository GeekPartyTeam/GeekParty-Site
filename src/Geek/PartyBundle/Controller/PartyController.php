<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Geek\PartyBundle\Entity\Party;
use Geek\PartyBundle\Form\PartyType;

/**
 * Party controller.
 *
 * @Route("/party")
 */
class PartyController extends CRUDController
{
    public function getEntity()
    {
        return 'GeekPartyBundle:Team';       
    }

    public function getFormClass()
    {
        return 'TeamType';
    }

    public function getRedirectPath()
    {
        return 'geek_people';
    }

    public function checkRights()
    {
        return null;
    }

    public function updateEntity($entity, Request $request)
    {
    }

    /**
     * Lists all Party entities.
     *
     * @Route("/", name="party")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GeekPartyBundle:Party')->findAll();

        return $this->arrayResponse(array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Party entity.
     *
     * @Route("/{id}/show", name="party_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:Party')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Party entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->arrayResponse(array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Party entity.
     *
     * @Route("/new", name="party_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Party();
        $form   = $this->createForm(new PartyType(), $entity);

        return $this->arrayResponse(array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Party entity.
     *
     * @Route("/create", name="party_create")
     * @Method("POST")
     * @Template("GeekPartyBundle:Party:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Party();
        $form = $this->createForm(new PartyType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('party_show', array('id' => $entity->getId())));
        }

        return $this->arrayResponse(array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Party entity.
     *
     * @Route("/{id}/edit", name="party_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:Party')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Party entity.');
        }

        $editForm = $this->createForm(new PartyType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->arrayResponse(array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Party entity.
     *
     * @Route("/{id}/update", name="party_update")
     * @Method("POST")
     * @Template("GeekPartyBundle:Party:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:Party')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Party entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PartyType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('party_edit', array('id' => $id)));
        }

        return $this->arrayResponse(array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Party entity.
     *
     * @Route("/{id}/delete", name="party_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GeekPartyBundle:Party')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Party entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('party'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
