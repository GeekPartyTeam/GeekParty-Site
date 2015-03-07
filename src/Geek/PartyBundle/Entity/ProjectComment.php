<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ProjectComment extends AbstractComment
{
    /**
     * @ORM\ManyToOne(targetEntity="Work")
     */
    protected $project;

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }
}