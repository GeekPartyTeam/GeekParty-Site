<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Geek\PartyBundle\Entity\Party;
use Geek\PartyBundle\Entity\Work;

class PartyRepository extends EntityRepository
{
    public function getRatings(Party $party)
    {
        // TODO: use party arg
        $em = $this->getEntityManager();
        $query = $em->getConnection()->query("SELECT
            work_id,
            Work.name,
            count(*) as vote_cnt,

            SUM(vote * LEAST(5.0, (SELECT COUNT(*) FROM ProjectVote v2 WHERE v2.user_id = v1.user_id)) / 5.0)
                / SUM(LEAST(5.0, (SELECT COUNT(*) FROM ProjectVote v2 WHERE v2.user_id = v1.user_id)) / 5.0)

                AS vote_avg
            FROM ProjectVote v1
            LEFT JOIN Work ON v1.work_id = Work.id
            WHERE v1.vote != 0
            GROUP BY work_id
            ORDER BY vote_avg desc;
        ");

        $results = $query->fetchAll(Query::HYDRATE_ARRAY);

        $ratings = [];
        foreach ($results as $row) {
            $ratings[$row['work_id']] = $row['vote_avg'];
        }

        return $ratings;
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
}