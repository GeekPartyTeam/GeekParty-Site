<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Geek\PartyBundle\Entity\Team;
use Geek\PartyBundle\Entity\TeamMember;
use Geek\PartyBundle\Form\TeamType;

/**
 * Team controller.
 *
 * @Route("/team")
 */
class TeamController extends BaseController
{
    public function checkRights(Team $entity)
    {
        if ((!$this->getUser() || $entity->getLeader() != $this->getUser()) && 
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

        if ($id) {
            /** @var \Geek\PartyBundle\Entity\Team $entity */
            $entity = $em->getRepository('GeekPartyBundle:Team')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Team entity.');
            }

            if (null !== ($redirect = $this->checkRights($entity))) {
                return $redirect;
            }

            $response['delete_form'] = $this->createDeleteForm($id);
        } else {
            $entity = new Team();
        }

        $editForm = $this->createForm(new TeamType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            foreach ($entity->getMembers() as $member) {
                $em->remove($member);
            }
            $entity->getMembers()->clear();
            foreach ($request->get('member')['name'] as $index => $name) {
                if ($name) {
                    $member = new TeamMember();
                    $member->setName($name);
                    $member->setDescription($request->get('member')['description'][$index]);
                    $member->setTeam($entity);
                    $entity->getMembers()->add($member);
                    $em->persist($member);
                }
            }
            $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();
            $entity->setLeader($user);
            $em->persist($entity);
            $em->flush();

            // return $this->redirect($this->generateUrl('team_edit', array('id' => $entity->getId())));
            return $this->redirect($this->generateUrl('geek_people'));
        }

        $response['entity']      = $entity;
        $response['edit_form']   = $editForm->createView();
        return $this->arrayResponse($response);
    }

    /**
     * Lists all Team entities.
     *
     * @Route("/", name="team")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GeekPartyBundle:Team')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Team entity.
     *
     * @Route("/{id}/show", name="team_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Team entity.
     *
     * @Route("/new", name="team_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Team();
        $form   = $this->createForm(new TeamType(), $entity);

        return $this->arrayResponse(array(
            'entity' => $entity,
            'edit_form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Team entity.
     *
     * @Route("/create", name="team_create")
     * @Method("POST")
     * @Template("GeekPartyBundle:Team:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return $this->update($request);
    }

    /**
     * Displays a form to edit an existing Team entity.
     *
     * @Route("/{id}/edit", name="team_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $entity Team */
        $entity = $em->getRepository('GeekPartyBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        $editForm = $this->createForm(new TeamType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->arrayResponse(array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Team entity.
     *
     * @Route("/{id}/update", name="team_update")
     * @Method("POST")
     * @Template("GeekPartyBundle:Team:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    /**
     * Deletes a Team entity.
     *
     * @Route("/{id}/delete", name="team_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var \Geek\PartyBundle\Entity\Team $entity */
            $entity = $em->getRepository('GeekPartyBundle:Team')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Team entity.');
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
