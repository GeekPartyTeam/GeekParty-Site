<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

class AbstractCommentRepository extends EntityRepository
{
    const COMMENTS_PER_PAGE = 10;

    /**
     * @param $criteria
     * @param $from
     * @param int $commentsPerPage
     * @return array
     */
    public function fetchPage($criteria, $from, $commentsPerPage = self::COMMENTS_PER_PAGE)
    {
        $comments = $this->findBy($criteria, ['date' => 'DESC'], $commentsPerPage, $from);
        $totalCount = count($this->findAll());
        return [
            'from' => $from,
            'entities' => $comments,
            'total_count' => $totalCount,
            'entities_per_page' => $commentsPerPage,
        ];
    }

    public static function extractCommentsPage(Collection $comments, $from)
    {
        return [
            'from' => $from,
            'entities' => $comments->slice($from, AbstractCommentRepository::COMMENTS_PER_PAGE),
            'total_count' => $comments->count(),
            'entities_per_page' => self::COMMENTS_PER_PAGE,
        ];
    }
}