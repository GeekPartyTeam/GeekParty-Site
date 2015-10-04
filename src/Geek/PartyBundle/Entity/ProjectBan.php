<?php

namespace Geek\PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Geek\PartyBundle\Entity\Repository\ProjectBanRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"project_id"})})
 */
class ProjectBan
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column()
     */
    protected $comment = '';

    /**
     * @var Work
     * @ORM\ManyToOne(targetEntity="Work")
     * @ORM\OrderBy({"date" = "DESC"})
     */
    protected $project;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     * @return ProjectBan
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Work
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Work $project
     * @return ProjectBan
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }
}