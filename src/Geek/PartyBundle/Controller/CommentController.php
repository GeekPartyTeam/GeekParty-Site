<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller;

use Geek\PartyBundle\Controller\Base\BaseController;
use Geek\PartyBundle\Entity\AbstractComment;
use Geek\PartyBundle\Entity\ArticleComment;
use Geek\PartyBundle\Entity\ProjectComment;
use Geek\PartyBundle\Entity\Repository\AbstractCommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CommentController extends BaseController
{
    /**
     * @Route("/article")
     * @Method("POST")
     *
     * @param Request $request
     * @return array
     */
    public function articleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->find('GeekPartyBundle:Article', $request->get('article_id'));
        $comment = new ArticleComment();
        $comment->setArticle($article);
        $this->addComment($request->get('text'), $comment);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/project")
     * @Method("POST")
     *
     * @param Request $request
     * @return array
     */
    public function projectAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->find('GeekPartyBundle:Work', $request->get('project_id'));
        $comment = new ProjectComment();
        $comment->setProject($article);
        $this->addComment($request->get('text'), $comment);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/list/{from}", defaults={"from"=0})
     * @Method("GET")
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function listAction(Request $request)
    {
        $from = $request->get('from', 0);

        return $this->arrayResponse(
            $this->getRepository()->fetchPage([], $from)
        );
    }

    /**
     * @Method("POST")
     * @Route()
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request)
    {
        $id = $request->request->get('id');
        /** @var AbstractComment $comment */
        $comment = $this->getRepository()->find($id);
        $comment->setRemoved(true);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @return AbstractCommentRepository
     */
    private function getRepository()
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('GeekPartyBundle:AbstractComment');
    }

    /**
     * @param $text
     * @param AbstractComment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function addComment($text, AbstractComment $comment)
    {
        $em = $this->getDoctrine()->getManager();
        $comment->setText($text);
        $comment->setAuthor($this->getUser());
        $em->persist($comment);
        $em->flush();
    }
}