<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Entity\Repository;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Geek\PartyBundle\Entity\Party;
use Geek\PartyBundle\Entity\Work;

class PartyRepository extends EntityRepository
{
    public function getRatings(Party $party)
    {
        if ($party->hasUserRating()) {
            return $this->getNewRatings($party);
        } else {
            return $this->getOldRatings($party);
        }
    }

    public function getVoteCount(Work $a)
    {
        static $cache;
        if (!$cache) {
            $cache = [];
        }

        if (!isset($cache[$a->getId()])) {
            $count = $this->getEntityManager()->getConnection()
                ->fetchColumn("SELECT COUNT(*) FROM ProjectVote WHERE work_id = ?", [$a->getId()], 0);
            $cache[$a->getId()] = $count;
        }

        return $cache[$a->getId()];
    }

    /**
     * @param Party $party
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getOldRatings(Party $party)
    {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $platform = $conn->getDatabasePlatform();
        $leastFunction = $platform instanceof SqlitePlatform ? 'MIN' : 'LEAST';
        $stmt = $conn->prepare("SELECT
            work_id,
            Work.name,
            count(*) as vote_cnt,

            SUM(vote * $leastFunction(5.0, (SELECT COUNT(*) FROM ProjectVote v2 WHERE v2.user_id = v1.user_id)) / 5.0)
                / SUM($leastFunction(5.0, (SELECT COUNT(*) FROM ProjectVote v2 WHERE v2.user_id = v1.user_id)) / 5.0)

                AS vote_avg
            FROM ProjectVote v1
            LEFT JOIN Work ON v1.work_id = Work.id
            WHERE
              v1.vote != 0 AND
              Work.party_id = :party_id
            GROUP BY work_id
            ORDER BY vote_avg desc;
        ");

        $stmt->execute(['party_id' => $party->getId()]);
        $results = $stmt->fetchAll();

        $ratings = [];
        foreach ($results as $row) {
            $ratings[$row['work_id']] = $row['vote_avg'];
        }

        return $ratings;
    }

    public function getUserRatings(Party $party)
    {
        return $this->getRatingsByIsUploader($party, false);
    }

    private function getNewRatings(Party $party)
    {
        return $this->getRatingsByIsUploader($party, true);
    }

    /**
     * @param Party $party
     * @param bool  $isUploader
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getRatingsByIsUploader(Party $party, $isUploader = true)
    {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $platform = $conn->getDatabasePlatform();
        $leastFunction = $platform instanceof SqlitePlatform ? 'MIN' : 'LEAST';
        $uploaderCheck = $isUploader ? "NOT NULL" : "NULL";
        $sql = "SELECT
            work_id,
            w1.name,
            count(*) as vote_cnt,

            SUM(vote * $leastFunction(5.0, (SELECT COUNT(*) FROM ProjectVote v2 WHERE v2.user_id = v1.user_id)) / 5.0)
                / SUM($leastFunction(5.0, (SELECT COUNT(*) FROM ProjectVote v2 WHERE v2.user_id = v1.user_id)) / 5.0)

                AS vote_avg
            FROM ProjectVote v1
            LEFT JOIN Work w1 ON v1.work_id = w1.id
            LEFT JOIN User u  ON v1.user_id = u.id
            LEFT JOIN Work w2 ON w2.author_id = u.id
            WHERE
              v1.vote != 0 AND
              w1.party_id = :party_id AND
              w2.id IS $uploaderCheck
            GROUP BY work_id
            ORDER BY vote_avg desc;
        ";
        $stmt = $conn->prepare($sql);

        $stmt->execute(['party_id' => $party->getId()]);
        $results = $stmt->fetchAll();

        $ratings = [];
        foreach ($results as $row) {
            $ratings[$row['work_id']] = $row['vote_avg'];
        }

        return $ratings;
    }

    /**
     * @return Party
     */
    public function getCurrentParty()
    {
        static $party;

        if (!$party) {
            $parties = $this->getEntityManager()
                ->createQuery("SELECT p FROM GeekPartyBundle:Party p WHERE p.endTime > :time ORDER BY p.endTime ASC")
                ->setParameter('time', new \DateTime())
                ->setMaxResults(1)
                ->getResult();

            if (!$parties) {
                $parties = $this->getEntityManager()
                    ->createQuery("SELECT p FROM GeekPartyBundle:Party p ORDER BY p.endTime DESC")
                    ->setMaxResults(1)
                    ->getResult();
            }

            if ($parties) {
                $party = current($parties);
            }
        }

        return $party;
    }
}