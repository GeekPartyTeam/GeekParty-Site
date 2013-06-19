<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Geek\PartyBundle\Entity\Work;
use Geek\PartyBundle\Form\WorkType;

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
}
