<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller;

use FOS\UserBundle\Model\User;
use Geek\PartyBundle\Controller\Base\BaseController;
use Geek\PartyBundle\Entity\Repository\AbstractCommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * @Route("/{id}", requirements={"id" = "\d+"}, defaults={"id" = 1})
     * @Template()
     * @param Request $request
     * @param $id
     * @return array
     */
    public function showAction(Request $request, $id)
    {

        $em = $this->getDoctrine()
            ->getManager();

        /** @var User $user */
        $user = $em->find('GeekPartyBundle:User', $id);

        $from = $request->get('from', 0);
        /** @var AbstractCommentRepository $commentsRepo */
        $commentsRepo = $em->getRepository('GeekPartyBundle:AbstractComment');
        $comments = $commentsRepo->fetchPage(['author' => $user], $from);

        $projects = $em->getRepository('GeekPartyBundle:Work')
            ->findBy(['author' => $user]);

        return $this->arrayResponse([
            'user' => $user,
            'comments' => $comments,
            'projects' => $projects,
        ]);
    }
}