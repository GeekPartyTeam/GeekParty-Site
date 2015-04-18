<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * @param $criteria
     * @param $from
     * @param int $entitiesPerPage
     * @return array
     */
    public function fetchPage($criteria, $from, $entitiesPerPage = null)
    {
        if (!$entitiesPerPage) {
            $entitiesPerPage = $this->getEntitiesPerPage();
        }
        $entities = $this->findBy($criteria, ['time' => 'DESC'], $entitiesPerPage, $from);
        $totalCount = count($this->findAll());
        return [
            'from' => $from,
            'entities' => $entities,
            'total_count' => $totalCount,
            'entities_per_page' => $entitiesPerPage,
        ];
    }

    public static function extractPage(Collection $collection, $from)
    {
        return [
            'from' => $from,
            'entities' => $collection->slice($from, self::getEntitiesPerPage()),
            'total_count' => $collection->count(),
            'entities_per_page' => self::getEntitiesPerPage(),
        ];
    }

    protected static function getEntitiesPerPage()
    {
        return 5;
    }
}