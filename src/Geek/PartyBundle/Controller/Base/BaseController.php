<?php

namespace Geek\PartyBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
    , Symfony\Component\HttpFoundation\Response
    ;
use Symfony\Component\HttpFoundation\Session\Session;

class BaseController extends Controller
{
    const FLASH_NOTICE = 'notice';
    const FLASH_INFO = 'info';
    const FLASH_SUCCESS = 'success';

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

    /**
     * @param array $parameters
     * @return array
     */
    public function arrayResponse(array $parameters)
    {
        $parameters['current_party'] = $this->getCurrentParty();
        $parameters['current_user'] = $this->getUser();
        $parameters['now'] = $this->getRequest()->get('now', date('Y-m-d H:i:s'));
        return $parameters;
    }

    /**
     * @param $name
     * @return \Geek\PartyBundle\Entity\Text|null|object
     */
    protected function findTextBlock($name)
    {
        /** @var $repo \Geek\PartyBundle\Entity\Repository\Text */
        $repo = $this->getDoctrine()
            ->getRepository('GeekPartyBundle:Text');
        return $repo->fetch($name);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface
     */
    protected function getFlashBag()
    {
        /** @var Session $session */
        $session = $this->get('session');
        return $session->getFlashBag();
    }

    /**
     * @param $message
     */
    protected function addErrorMessage($message)
    {
        $this->getFlashBag()->add(self::FLASH_NOTICE, $message);
    }

    protected function isAdmin()
    {
        return $this->get('security.context')->isGranted('ROLE_ADMIN');
    }
}
