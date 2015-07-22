<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Geek\PartyBundle\Entity\Party;

class PartyThemeRepository extends EntityRepository
{
    public function findAllSortedByName(Party $party)
    {
        return $this->findBy(
            ['party' => $party],
            ['text' => 'ASC']
        );
    }

    public function findAllSortedByVotes(Party $party)
    {
        $result = $this->getEntityManager()
            ->createQuery("SELECT t as theme, count(v) AS cnt FROM GeekPartyBundle:PartyTheme t
                  LEFT JOIN t.votes v
                  WHERE t.party = :party
                  GROUP BY t
                  ORDER BY cnt DESC")
            ->setParameter('party', $party)
            ->getResult();

        return array_column($result, 'theme');
    }
}