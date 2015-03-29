<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Geek\PartyBundle\Entity\Work;

class WorkRepository extends EntityRepository
{
    private $rootDir;

    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function isWorkUploaded(Work $work)
    {
        $path = '/works/' . $work->getParty()->getId() . '/' . $work->getId() . '/index.html';
        return file_exists($this->rootDir . '/../public_html' . $path);
    }
}