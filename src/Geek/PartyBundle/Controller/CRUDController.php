<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Abstract CRUD controller.
 */
abstract class CRUDController extends BaseController
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

    public function update(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        $response = [];

        if ($id) {
            $entity = $em->getRepository($this->getEntity())->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            if (null !== ($redirect = $this->checkRights($entity))) {
                return $redirect;
            }

            $response['delete_form'] = $this->createDeleteForm($id);
        } else {
            $class = $em->getRepository($this->getEntity())->getClassName();
            $entity = new $class();
        }

        $formClass = $this->getFormClass();
        $editForm = $this->createForm(new $formClass(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->updateEntity($entity, $request);

            $em->persist($entity);
            $em->flush();

            // return $this->redirect($this->generateUrl('team_edit', array('id' => $entity->getId())));
            return $this->redirect($this->generateUrl($this->getRedirectPath()));
        }

        $response['entity']      = $entity;
        $response['edit_form']   = $editForm->createView();
        return $this->arrayResponse($response);
    }

    /**
     * Lists all entities.
     *
     * @Route("/", name="team")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository($this->getEntity())->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a entity.
     *
     * @Route("/{id}/show", name="team_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getEntity())->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="team_new")
     * @Template()
     */
    public function newAction()
    {
        $class = $em->getRepository($this->getEntity())->getClassName();
        $formClass = $this->getFormClass();
        $entity = new $class();
        $form   = $this->createForm(new $formClass(), $entity);

        return $this->arrayResponse(array(
            'entity' => $entity,
            'edit_form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new entity.
     */
    public function createAction(Request $request)
    {
        return $this->update($request);
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="team_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getEntity())->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $formClass = $this->getFormClass();
        $editForm = $this->createForm(new $formClass(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->arrayResponse(array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing entity.
     */
    public function updateAction(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    /**
     * Deletes a entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($this->getEntity())->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            if (null !== ($redirect = $this->checkRights($entity))) {
                return $redirect;
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl($this->getRedirectPath()));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
