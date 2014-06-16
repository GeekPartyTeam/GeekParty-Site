<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Geek\PartyBundle\Entity\Work;

/**
 * Work controller.
 */
class WorkController extends CRUDController
{
    public function getEntity()
    {
        return 'Work';       
    }

    public function getRedirectPath()
    {
        return 'admin_works';
    }

    public function getFormClass()
    {
        return 'Geek\PartyBundle\Form\ProjectType';
    }


    /**
     * Lists all entities.
     */
    public function indexAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GeekPartyBundle:Work')->findAll();

        usort($entities, function ($a, $b) {
            if (!$a->getParty() || !$b->getParty()) {
                return 0;
            }
            return strcmp($a->getParty()->getId(), $b->getParty()->getId());
        });

        return $this->render('GeekPartyBundle:Work:index.html.twig', [
            'entities' => $entities,
            'parties'  => $em->getRepository('GeekPartyBundle:Party')->findAll()
        ]);
    }

    public function updateEntity($entity, Request $request, Form $form)
    {
        /** @var \Geek\PartyBundle\Entity\Work $entity */
        $entity->setAuthor($this->getUser());

        $sameIdEntity = $this->getDoctrine()
            ->getManager()
            ->find('GeekPartyBundle:Work', $entity->getId());
        if ($sameIdEntity && $sameIdEntity !== $entity) {
            $form->addError(new FormError('Работа с таким идентификатором уже существует'));
            return false;
        }
        return true;
    }

    public function checkRights($entity)
    {
        /** @var \Geek\PartyBundle\Entity\Work $entity */
        return $entity->getAuthor() === $this->getUser();
    }
}
