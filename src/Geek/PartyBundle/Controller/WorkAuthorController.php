<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Geek\PartyBundle\Entity\WorkAuthor;
use Geek\PartyBundle\Form\WorkAuthorType;

/**
 * WorkAuthor controller.
 *
 * @Route("/workauthor")
 */
class WorkAuthorController extends Controller
{
    /**
     * Lists all WorkAuthor entities.
     *
     * @Route("/", name="workauthor")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GeekPartyBundle:WorkAuthor')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a WorkAuthor entity.
     *
     * @Route("/{id}/show", name="workauthor_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:WorkAuthor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkAuthor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new WorkAuthor entity.
     *
     * @Route("/new", name="workauthor_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new WorkAuthor();
        $form   = $this->createForm(new WorkAuthorType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new WorkAuthor entity.
     *
     * @Route("/create", name="workauthor_create")
     * @Method("POST")
     * @Template("GeekPartyBundle:WorkAuthor:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new WorkAuthor();
        $form = $this->createForm(new WorkAuthorType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('workauthor_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing WorkAuthor entity.
     *
     * @Route("/{id}/edit", name="workauthor_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:WorkAuthor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkAuthor entity.');
        }

        $editForm = $this->createForm(new WorkAuthorType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing WorkAuthor entity.
     *
     * @Route("/{id}/update", name="workauthor_update")
     * @Method("POST")
     * @Template("GeekPartyBundle:WorkAuthor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:WorkAuthor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkAuthor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new WorkAuthorType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('workauthor_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a WorkAuthor entity.
     *
     * @Route("/{id}/delete", name="workauthor_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GeekPartyBundle:WorkAuthor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find WorkAuthor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('workauthor'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
