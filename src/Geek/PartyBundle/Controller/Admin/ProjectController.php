<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller\Admin;

use Geek\PartyBundle\Entity\ProjectBan;
use Geek\PartyBundle\Entity\Repository\ProjectBanRepository;
use Geek\PartyBundle\Entity\Work;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class ProjectController
 * @Route("/admin/project")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class ProjectController extends Controller
{
    /**
     * @Route("/ban", name="admin_project_ban")
     * @Method("POST")
     */
    public function banEditAction(Request $request)
    {
        $id = $request->get('project_id');
        $ban = $request->get('banned', null);
        $entityManager = $this->getDoctrine()->getEntityManager();
        /** @var Work $project */
        $project = $entityManager->find(Work::class, $id);
        /** @var ProjectBanRepository $banRepo */
        $banRepo = $entityManager->getRepository(ProjectBan::class);
        if ($banRepo->isBanned($project) && !$ban) {
            $banRepo->unban($project);
        } elseif ($ban) {
            $banRepo->ban($project, $request->get('comment'));
        }

        return new RedirectResponse($request->headers->get('referer'));
    }
}