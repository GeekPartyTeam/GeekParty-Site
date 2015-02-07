<?php

namespace Geek\PartyBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Geek\PartyBundle\Entity\Party;
use Geek\PartyBundle\Entity\ProjectVote;
use Geek\PartyBundle\Entity\Repository\PartyRepository;
use Geek\PartyBundle\Entity\Work;
use JMS\SecurityExtraBundle\Tests\Security\Authorization\Expression\Fixture\Issue22\Project;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrowseController extends Base\BaseController
{
    /**
     * Список работ
     * @param $party string Идентификатор пати — gp1, gp2 или gp3
     * @return array
     * @Template()
     */
    public function partyAction($party)
    {
        /** @var Party $partyEntity */
        $partyEntity = null;
        $em = $this->getDoctrine()->getManager();
        /** @var EntityRepository $partyRepo */
        $partyRepo = $em->getRepository('GeekPartyBundle:Party');

        /** @var $partyEntity Party */
        if ($party == 'latest') {
            $partyEntity = $partyRepo->findOneBy([], ['endTime' => 'DESC']);
        } else {
            $partyEntity = $partyRepo->find($party);
        }

        $works = $this->fetchWorks($partyEntity);

        $parties = $partyRepo->findAll();

        return $this->arrayResponse([
            'text' => $this->findTextBlock('party'),
            'works' => $works,
            'parties' => $parties,
            'party' => $partyEntity
        ]);
    }

    /**
     * Страница работы
     * @param $party string Идентификатор пати — gp1, gp2 или gp3
     * @param $work string Идентификатор работы (или короткое имя, бывший строковый идентификатор)
     * @return array
     * @Template()
     */
    public function workAction($party, $work)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $workRepo = $em->getRepository('GeekPartyBundle:Work');
        $workEntity = $workRepo->find($work);
        if (!$workEntity) {
            $workEntity = $workRepo->findOneBy(['shortname' => $work]);
            if (!$work) {
                throw new NotFoundHttpException();
            }
        }

        return $this->arrayResponse([
            'party' => $party,
            'work' => $workEntity,
            'vote' => $this->getVote($workEntity),
        ]);
    }

    public function voteAction()
    {
        $id = $this->getRequest()->get('id');
        $em = $this->getDoctrine()->getManager();
        /** @var EntityRepository $workRepo */
        $projectRepo = $em->getRepository('GeekPartyBundle:Work');
        /** @var Work $project */
        $project = $projectRepo->find($id);

        $redirect = $this->redirect($this->generateUrl('geek_browse_work', [
            'party' => $project->getParty()->getId(),
            'work' => $project->getId(),
        ]));

        /** @var Session $session */
        $session = $this->get('session');
        if ($project->getAuthor() === $this->getUser()) {
            $session->getFlashBag()->add('notice', "Нельзя голосовать за свою игру");
            return $redirect;
        }

        $vote = (int)$this->getRequest()->get('vote');
        if ($vote < 1 || $vote > 5) {
            $session->getFlashBag()->add('notice', "Голос должен быть от 1 до 5 кирок");
            return $redirect;
        }

        $entity = $this->getVote($project);
        if (!$entity) {
            $entity = new ProjectVote();
            $entity->setWork($project);
            $entity->setUser($this->getUser());
        } else {
            $entity->setDate(new \DateTime());
        }

        $entity->setVote($vote);
        $entity->setIp($this->getRequest()->getClientIp());
        $entity->setUserAgent($this->getRequest()->headers->get('User-Agent'));
        $em->persist($entity);
        $em->flush();

        return $redirect;
    }

    /**
     * @param $partyEntity
     * @return array
     */
    private function fetchWorks(Party $partyEntity = null)
    {
        if (!$partyEntity) {
            return [];
        }

        $em = $this->getDoctrine()->getManager();
        /** @var EntityRepository $workRepo */
        $workRepo = $em->getRepository('GeekPartyBundle:Work');
        /** @var PartyRepository $partyRepo */
        $partyRepo = $em->getRepository('GeekPartyBundle:Party');

        $works = $workRepo->findBy(['party' => $partyEntity], ['time' => 'ASC']);

        $sortByUploadDate = function ($a, $b) {
            $tooOld = new \DateTime('2000-01-01');
            /** @var Work $a */
            /** @var Work $b */
            if ($b->getTime() < $tooOld) {
                return -1;
            }
            if ($a->getTime() < $tooOld) {
                return 1;
            }
            return $a->getTime() < $b->getTime() ? -1 : 1;
        };
        $sort = $sortByUploadDate;

        if ($partyEntity->isProjectVotingTime()) {
            $sortByVoteCount = function (Work $a, Work $b) use ($partyRepo) {
                return $partyRepo->getVoteCount($a) < $partyRepo->getVoteCount($b) ? -1 : 1;
            };
            $sort = $sortByVoteCount;
        }

        if ($partyEntity->isEnded()) {
            $ratings = $partyRepo->getRatings($partyEntity);
                $sortByRating = function (Work $a, Work $b) use ($ratings) {
                if (!isset($ratings[$a->getId()])) {
                    $ratings[$a->getId()] = 0;
                }
                if (!isset($ratings[$b->getId()])) {
                    $ratings[$b->getId()] = 0;
                }

                return $ratings[$a->getId()] > $ratings[$b->getId()] ? -1 : 1;
            };
            $sort = $sortByRating;
        }

        usort($works, $sort);
        return $works;
    }

    /**
     * @param Work $project
     * @return ProjectVote|null
     */
    private function getVote(Work $project)
    {
        if (!$this->getUser()) {
            return null;
        }
        $currentParty = $this->getCurrentParty();
        if ($project->getParty() != $currentParty) {
            return null;
        }
        if (!$currentParty->isProjectVotingTime()) {
            return null;
        }
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT v FROM GeekPartyBundle:ProjectVote v
                JOIN v.work p
                JOIN v.user u
                WHERE p = :project AND u = :user");
        $query->setMaxResults(1);
        $query->setParameter('project', $project);
        $query->setParameter('user', $this->getUser());
        $answer = $query->getResult();
        if (count($answer) < 1) {
            return null;
        }
        $result = $answer[0];
        return $result;
    }
}
