<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\User;
use Geek\PartyBundle\Controller\Base\BaseController;
use Geek\PartyBundle\Entity\Repository\AbstractCommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        /** @var EntityManager $em */
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

        $themeVotes = [];
        $projectVotes = [];
        if ($this->isAdmin()) {
            $themeVotes = $em->getRepository('GeekPartyBundle:PartyThemeVote')
                ->findBy(['user' => $user]);
            $projectVotes = $em->getRepository('GeekPartyBundle:ProjectVote')
                ->findBy(['user' => $user]);
        }

        return $this->arrayResponse([
            'user' => $user,
            'comments' => $comments,
            'projects' => $projects,
            'theme_votes' => $themeVotes,
            'project_votes' => $projectVotes,
        ]);
    }

    /**
     * @Route("/after_login")
     * @param Request $request
     * @return RedirectResponse
     */
    public function afterLoginAction(Request $request)
    {
        $url = parse_url($request->headers->get('referer'), PHP_URL_PATH);
        if (!$url) {
            $url = '/';
        }
        return new RedirectResponse($url);
    }
}