<?php

namespace Geek\PartyBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Geek\PartyBundle\Entity\Party;
use Geek\PartyBundle\Entity\Work;
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
            'work' => $workEntity
        ]);
    }

    /**
     * @param $partyEntity
     * @return array
     */
    public function fetchWorks(Party $partyEntity = null)
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
}
