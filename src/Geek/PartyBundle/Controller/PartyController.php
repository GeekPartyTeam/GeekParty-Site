<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Geek\PartyBundle\Entity\Party;

/**
 * Party controller.
 */
class PartyController extends Base\CRUDController
{
    public function getEntity()
    {
        return 'Party';       
    }

    public function getRedirectPath()
    {
        return 'admin_parties';
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     *
     * @Template()
     */
    public function indexAction(/** @noinspection PhpUnusedParameterInspection */
        Request $request, $id)
    {
        $entities = $this->getDoctrine()
            ->getManager()
            ->getRepository('GeekPartyBundle:Party')
            ->findBy([], ['startTime' => 'DESC']);

        return $this->renderPage('index', [
            'entities' => $entities,
        ]);
    }

}
