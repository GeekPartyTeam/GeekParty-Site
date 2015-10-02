<?php

namespace Geek\PartyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Geek\PartyBundle\Entity\ProjectBan;
use Geek\PartyBundle\Entity\Work;

/**
 * ProjectBanRepository
 */
class ProjectBanRepository extends EntityRepository
{
    /**
     * @param Work $project
     * @return bool
     */
    public function isBanned(Work $project)
    {
        return null !== $this->findBan($project);
    }

    /**
     * @param Work $project
     * @param $comment
     */
    public function ban(Work $project, $comment)
    {
        if ($this->isBanned($project)) {
            return;
        }

        $ban = new ProjectBan();
        $ban->setProject($project);
        $ban->setComment($comment);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($ban);
        $entityManager->flush($ban);
    }

    /**
     * @param Work $project
     */
    public function unban(Work $project)
    {
        $ban = $this->findBan($project);
        $entityManager = $this->getEntityManager();
        $entityManager->remove($ban);
        $entityManager->flush($ban);
    }

    /**
     * @param Work $project
     * @return null|object
     */
    public function findBan(Work $project)
    {
        return $this->findOneBy(['project' => $project]);
    }
}
