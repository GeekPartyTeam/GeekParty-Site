<?php

namespace Geek\PartyBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Geek\PartyBundle\Entity\Party;
use Geek\PartyBundle\Entity\ProjectVote;
use Geek\PartyBundle\Entity\Work;
use JMS\SecurityExtraBundle\Tests\Security\Authorization\Expression\Fixture\Issue22\Project;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
            'voted' => $this->getAlreadyVoted($workEntity),
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

        $vote = $this->getRequest()->get('vote');

        if ($this->getAlreadyVoted($project)) {
            return $this->redirect('geek_index');
        }

        $entity = new ProjectVote();
        $entity->setWork($project);
        $entity->setVote($vote);
        $entity->setUser($this->getUser());
        $entity->setIp($this->getRequest()->getClientIp());
        $entity->setUserAgent($this->getRequest()->headers->get('User-Agent'));
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('geek_browse', ['party' => $project->getParty()]));
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

        $works = $workRepo->findBy(['party' => $partyEntity], ['time' => 'ASC']);

        $tooOld = new \DateTime('2000-01-01');
        usort($works, function ($a, $b) use ($tooOld) {
            /** @var Work $a */
            /** @var Work $b */
            if ($b->getTime() < $tooOld) {
                return -1;
            }
            if ($a->getTime() < $tooOld) {
                return 1;
            }
            return $a->getTime() < $b->getTime() ? -1 : 1;
        });
        return $works;
    }

    /**
     * @param Work $project
     * @return bool
     */
    private function getAlreadyVoted(Work $project)
    {
        if (!$this->getUser()) {
            return false;
        }
        $currentParty = $this->getCurrentParty();
        if ($project->getParty() != $currentParty->getId() || !$currentParty->isProjectVotingTime()) {
            return false;
        }
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT COUNT(v) FROM GeekPartyBundle:ProjectVote v
                JOIN v.project p
                JOIN v.user u
                WHERE p = :project AND u = :user");
        $query->setParameter('project', $project);
        $query->setParameter('user', $this->getUser());
        $result = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SCALAR)[0];
        return is_array($result) && isset($result[1]) ? $result[1] != 0 : false;
    }
}
