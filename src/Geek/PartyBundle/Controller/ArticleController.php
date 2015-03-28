<?php
/**
 * Коршунов Георгий <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller;

use Doctrine\ORM\EntityManager;
use Geek\PartyBundle\Entity\Article;
use Geek\PartyBundle\Entity\Repository\AbstractCommentRepository;
use Geek\PartyBundle\Entity\Voter;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Base\CRUDController
{
    public function voteAction()
    {
        if (!$this->isRequestValid()) {
            $this->addErrorMessage("Пожалуйста, не накручивайте голосование");
            return $this->redirectToIndex();
        }

        $request = $this->getRequest();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var \Prism\PollBundle\Entity\Poll $poll */
        $poll = $em->find('PrismPollBundle:Poll', $this->getRequest()->get('poll'));
        if (!$poll) {
            $this->addErrorMessage('Голосование не найдено');
            return $this->redirectToIndex();
        }

        $opinions = $this->getRequest()->get('opinion', []);
        if (count($opinions) == 0) {
            $this->addErrorMessage('Выберите хотя бы один пункт, чтобы проголосовать');
            return $this->redirectToIndex();
        }
        foreach ($poll->getOpinions() as $opinion) {
            /** @var \Prism\PollBundle\Entity\Opinion $opinion */
            if (isset($opinions[$opinion->getId()])) {
                $opinion->setVotes($opinion->getVotes() + 1);
            }
        }

        $voter = new Voter(
            $request->getClientIp(),
            $request->headers->get('User-Agent'),
            $poll,
            implode(',', array_keys($opinions))
        );
        if ($this->getUser()) {
            $voter->setUser($this->getUser());
        }

        $poll->incrVotes();

        $em->persist($voter);
        $em->flush();

        $this->addErrorMessage("Спасибо, ваш голос учтен");
        $response = $this->redirectToIndex();
        $response->headers->setCookie(new Cookie('prism_poll_' . $poll->getId(), true, time()+3600*24*365));
        return $response;
    }

    public function getEntity()
    {
        return 'Article';
    }

    public function getRedirectPath()
    {
        return 'geek_index';
    }

    public function updateEntity($entity, Request $request, Form $form)
    {
        if (!$this->getUser()) {
            return false;
        }
        /** @var \Geek\PartyBundle\Entity\Article $entity */
        $entity->setAuthor($this->getUser());
        $entity->setTime(new \DateTime());
        return true;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToIndex()
    {
        return $this->redirect($this->generateUrl('geek_index'));
    }
    protected function edit($id)
    {
        $params = parent::edit($id);

        $polls = $this->getDoctrine()
            ->getRepository('PrismPollBundle:Poll')
            ->findAll();
        $params['polls'] = $polls;

        return $params;
    }


    private function isRequestValid()
    {
        if ($this->getRequest()->getMethod() != 'POST') {
            return false;
        }

        $refererParsed = parse_url($this->getRequest()->headers->get('referer'));
        $host = preg_replace('/(.*):.*/', '$1', $this->getRequest()->headers->get('host'));
        $path = $this->generateUrl('geek_index');
        if ($refererParsed['host'] == $host || $refererParsed['path'] == $path) {
            return true;
        } else {
            return false;
        }
    }

    protected function renderPage($page, array $parameters = [])
    {
        if ($page == 'show') {
            /** @var Article $entity */
            $entity = $parameters['entity'];
            $from = $this->getRequest()->get('from', 0);
            $parameters = array_merge($parameters,
                ['comments' => AbstractCommentRepository::extractCommentsPage($entity->getComments(), $from)]
            );
        }
        return parent::renderPage($page, $parameters);
    }
}
