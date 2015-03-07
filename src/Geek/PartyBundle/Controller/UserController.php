<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller;

use FOS\UserBundle\Model\User;
use Geek\PartyBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends BaseController
{
    /**
     * @Route("/{id}", requirements={"id" = "\d+"}, defaults={"id" = 1})
     * @Template()
     * @param $id
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()
            ->getManager();

        /** @var User $user */
        $user = $em->find('GeekPartyBundle:User', $id);

        $comments = $em->getRepository('GeekPartyBundle:AbstractComment')
            ->findBy(['author' => $user]);

        $projects = $em->getRepository('GeekPartyBundle:Work')
            ->findBy(['author' => $user]);

        return $this->arrayResponse([
            'user' => $user,
            'comments' => $comments,
            'projects' => $projects,
        ]);
    }
}