<?php

namespace Geek\PartyBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
    , Symfony\Component\HttpFoundation\Response
    ;

class BaseController extends Controller
{
    /**
     * @return \Geek\PartyBundle\Entity\Party
     */
    public function getCurrentParty()
    {
        $em = $this->getDoctrine()->getManager();
        $parties = $em->createQuery("SELECT p FROM GeekPartyBundle:Party p WHERE p.endTime > :time ORDER BY p.endTime ASC")
            ->setParameter('time', new \DateTime())
            ->getResult();

        if (count($parties) == 0) {
            $parties = $em->createQuery("SELECT p FROM GeekPartyBundle:Party p ORDER BY p.endTime DESC")
                ->getResult();
        }

        return count($parties) > 0 ? $parties[0] : null;
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        return parent::render($view, $this->arrayResponse($parameters), $response);
    }

    public function arrayResponse(array $parameters)
    {
        $parameters['current_party'] = $this->getCurrentParty();
        $parameters['current_user'] = $this->getUser();
        return $parameters;
    }
}
