<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BrowseController extends BaseController
{
    /**
     * Список работ
     * @param $party string Идентификатор пати — gp1, gp2 или gp3
     * @return array
     * @Template()
     */
    public function partyAction($party)
    {
        $partyEntity = null;
        $em = $this->getDoctrine()->getManager();
        $partyRepo = $em->getRepository('GeekPartyBundle:Party');
        if ($party == 'latest') {
            $partyEntity = $partyRepo->findOneBy([], ['endTime' => 'DESC']);
        } else {
            $partyEntity = $partyRepo->find($party);
        }

        $works = [];
        if ($partyEntity) {
            $works = $em->getRepository('GeekPartyBundle:Work')
                ->findBy(['party' => $partyEntity]);
        }

        $parties = $partyRepo->findAll();

        return $this->arrayResponse([
            'works' => $works,
            'parties' => $parties,
            'party' => $partyEntity
        ]);
    }

    /**
     * Страница работы
     * @param $party string Идентификатор пати — gp1, gp2 или gp3
     * @param $work string Строковый идентификатор работы
     * @return array
     * @Template()
     */
    public function workAction($party, $work)
    {
        $em = $this->getDoctrine()->getManager();
        $workRepo = $em->getRepository('GeekPartyBundle:Work');
        $workEntity = $workRepo->find($work);

        return $this->arrayResponse([
            'party' => $party,
            'work' => $workEntity
        ]);
    }
}
