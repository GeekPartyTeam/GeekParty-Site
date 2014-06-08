<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Geek\PartyBundle\Entity\Work;
use Geek\PartyBundle\Form\ProjectType;

/**
 * Project (Work) controller.
 *
 * @Route("/project")
 */
class ProjectController extends BaseController
{
    public function checkRights(Work $entity)
    {
        if ((!$this->getUser() || $entity->getTeam()->getLeader() !== $this->getUser()) && 
            !$this->get('security.context')->isGranted('ROLE_ADMIN')) 
        {
            return $this->redirect($this->generateUrl('geek_people'));
        }

        return null;
    }

    public function update(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        $response = [];

        $user = $this->container
                        ->get('security.context')
                        ->getToken()
                        ->getUser();
        $team = $em->getRepository('GeekPartyBundle:Team')
            ->findOneBy(['leader' => $user]);

        /** @var $entity \Geek\PartyBundle\Entity\Work */
        if ($id) {
            $entity = $em->getRepository('GeekPartyBundle:Work')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Work entity.');
            }

            if (null !== ($redirect = $this->checkRights($entity))) {
                return $redirect;
            }

            $response['delete_form'] = $this->createDeleteForm($id);
        } else {
            $entity = new Work();
        }

        $editForm = $this->createForm(new ProjectType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {

            //$entity->setTeam()

            $currentParty = $this->getCurrentParty();
            $entity->setParty($currentParty);

            if ($file = $editForm['file']->getData()) {
                /** @var $file UploadedFile */
                $dir = $this->get('kernel')->getRootDir() . '/../public_html/works/' . $currentParty->getId() . '/' . $entity->getId();
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $file = $file->move($dir . '/archive.zip');
                $zip = new \ZipArchive();
                if (!$zip->open($file->getRealPath())) {
                    $zip->extractTo($dir);
                }
                unlink($file->getRealPath());
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('geek_people'));
        }

        $response['entity']      = $entity;
        $response['edit_form']   = $editForm->createView();
        return $this->arrayResponse($response);
    }

    /**
     * Lists all Work entities.
     *
     * @Route("/", name="project")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GeekPartyBundle:Work')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Work entity.
     *
     * @Route("/{id}/show", name="project_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:Work')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Work entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Work entity.
     *
     * @Route("/new", name="project_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Work();
        $form   = $this->createForm(new ProjectType(), $entity);

        return $this->arrayResponse(array(
            'entity' => $entity,
            'edit_form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Work entity.
     *
     * @Route("/create", name="project_create")
     * @Method("POST")
     * @Template("GeekPartyBundle:Work:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return $this->update($request);
    }

    /**
     * Displays a form to edit an existing Work entity.
     *
     * @Route("/{id}/edit", name="project_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $entity Work */
        $entity = $em->getRepository('GeekPartyBundle:Work')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Work entity.');
        }

        $editForm = $this->createForm(new ProjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->arrayResponse(array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Work entity.
     *
     * @Route("/{id}/update", name="project_update")
     * @Method("POST")
     * @Template("GeekPartyBundle:Work:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    /**
     * Deletes a Work entity.
     *
     * @Route("/{id}/delete", name="project_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GeekPartyBundle:Work')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Work entity.');
            }

            if (null !== ($redirect = $this->checkRights($entity))) {
                return $redirect;
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('geek_people'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
