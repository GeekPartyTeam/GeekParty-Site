<?php
/**
 * Коршунов Георгий <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class Text extends EntityRepository
{
    public function fetch($name)
    {
        $entity = $this->findOneBy(array('name' => $name));
        if (!$entity) {
            $entity = new \Geek\PartyBundle\Entity\Text();
            $entity->setName($name);
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        }
        return $entity;
    }
} 