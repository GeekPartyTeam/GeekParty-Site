<?php
/**
 * Коршунов Георгий <georgy.k@propellerads.com>
 */

namespace Geek\PartyBundle\Controller;

use Doctrine\ORM\EntityManager;
use Geek\PartyBundle\Entity\Voter;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Base\CRUDController
{
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
        /** @var \Geek\PartyBundle\Entity\Article $entity */
        $entity->setAuthor($this->getUser());
        $entity->setTime(new \DateTime());
        return true;
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

    public function voteAction($action, $id)
    {
        if (!$this->isRequestValid()) {
            $this->addErrorMessage("Пожалуйста, не накручивайте голосование");
            return $this->redirect($this->generateUrl('geek_index'));
        }

        $request = $this->getRequest();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var \Prism\PollBundle\Entity\Poll $poll */
        $poll = $em->find('PrismPollBundle:Poll', $this->getRequest()->get('poll'));
        if (!$poll) {
            $this->addErrorMessage('Голосование не найдено');
        }

        $opinions = $this->getRequest()->get('opinion', []);
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

        $em->persist($voter);
        $em->flush();

        $this->addErrorMessage("Спасибо, ваш голос учтен");
        $response = $this->redirect($this->generateUrl('geek_index'));
        $response->headers->setCookie(new Cookie('prism_poll_' . $poll->getId(), true, time()+3600*24*365));
        return $response;
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
}
