<?php

namespace Geek\PartyBundle\Controller\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
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
        return 'Team';       
    }

    public function getRedirectPath()
    {
        return 'geek_index';
    }

    public function getFormClass()
    {
        return 'Geek\\PartyBundle\\Form\\' . $this->getEntity() . 'Type';
    }

    public function checkRights($entity)
    {
        return null;
    }

    /**
     * @param $entity
     * @param Request $request
     * @param \Symfony\Component\Form\Form $form
     * @return bool
     */
    public function updateEntity($entity, Request $request, Form $form)
    {
        return true;
    }

    public function actionAction($action, $id)
    {
        return call_user_func([$this, "{$action}Action"], $this->getRequest(), $id);
    }

    public function update(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        $response = [];

        if ($id != -1) {
            $entity = $em->getRepository('GeekPartyBundle:' . $this->getEntity())->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            if (null !== ($redirect = $this->checkRights($entity))) {
                return $redirect;
            }

            $response['delete_form'] = $this->createDeleteForm($id);
        } else {
            $class = $em->getRepository('GeekPartyBundle:' . $this->getEntity())->getClassName();
            $entity = new $class();
        }

        $formClass = $this->getFormClass();
        $editForm = $this->createForm(new $formClass(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            if ($this->updateEntity($entity, $request, $editForm)) {
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl($this->getRedirectPath()));
            }
        }

        $response['entity']      = $entity;
        $response['edit_form']   = $editForm->createView();
        return $this->render('GeekPartyBundle:' . $this->getEntity() . ':edit.html.twig', $response);
    }

    /**
     * Lists all entities.
     */
    public function indexAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GeekPartyBundle:' . $this->getEntity())->findAll();

        return $this->render('GeekPartyBundle:' . $this->getEntity() . ':index.html.twig', [
            'entities' => $entities,
        ]);
    }

    /**
     * Finds and displays a entity.
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:' . $this->getEntity())->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->renderPage('show', [
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    protected function renderPage($page, array $parameters = [])
    {
        return $this->render("GeekPartyBundle:{$this->getEntity()}:{$page}.html.twig", $parameters);
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="team_edit")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $params = $this->edit($id);

        return $this->render('GeekPartyBundle:' . $this->getEntity() . ':edit.html.twig', $params);
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
            $entity = $em->getRepository('GeekPartyBundle:' . $this->getEntity())->find($id);

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

    /**
     * @param $id
     * @return array
     */
    protected function edit($id)
    {
        $em = $this->getDoctrine()->getManager();

        if ($id != -1) {
            $entity = $em->getRepository('GeekPartyBundle:' . $this->getEntity())->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }
        } else {
            $class = $em->getRepository('GeekPartyBundle:' . $this->getEntity())->getClassName();
            $entity = new $class();
        }

        $formClass = $this->getFormClass();
        /** @var AbstractType $form */
        $form = new $formClass();
        $formOptions = [
            'is_admin' => $this->isAdmin(),
        ];
        $editForm = $this->createForm($form, $entity, $formOptions);

        $params = [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ];

        if ($id != -1) {
            $params['delete_form'] = $this->createDeleteForm($id)->createView();
            return $params;
        }
        return $params;
    }
}
