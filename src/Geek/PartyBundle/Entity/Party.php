<?php

namespace Geek\PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Geek\PartyBundle\Entity\Repository\PartyRepository")
 */
class Party 
{
    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected $id;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $description = '';

    /**
     * @ORM\Column(type="datetime")
     */
    protected $startTime;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $endTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $themeSubmissionStartTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $themeSubmissionEndTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $themeVotingStartTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $themeVotingEndTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $projectVotingStartTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $projectVotingEndTime;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $audienceChoiceRating;

    /**
     * Set id
     *
     * @param string $id
     * @return Party
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Party
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    
        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Party
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    
        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    public function getName()
    {
        return preg_replace('/gp(.*)/', 'GP#$1', $this->getId());
    }

    public function isCurrent()
    {
        $now = new \DateTime();
        return $now >= $this->getStartTime() && $now <= $this->getEndTime();
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Party
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set themeSubmissionStartTime
     *
     * @param \DateTime $themeSubmissionStartTime
     * @return Party
     */
    public function setThemeSubmissionStartTime($themeSubmissionStartTime)
    {
        $this->themeSubmissionStartTime = $themeSubmissionStartTime;

        return $this;
    }

    /**
     * Get themeSubmissionStartTime
     *
     * @return \DateTime 
     */
    public function getThemeSubmissionStartTime()
    {
        return $this->themeSubmissionStartTime;
    }

    /**
     * Set themeSubmissionEndTime
     *
     * @param \DateTime $themeSubmissionEndTime
     * @return Party
     */
    public function setThemeSubmissionEndTime($themeSubmissionEndTime)
    {
        $this->themeSubmissionEndTime = $themeSubmissionEndTime;

        return $this;
    }

    /**
     * Get themeSubmissionEndTime
     *
     * @return \DateTime 
     */
    public function getThemeSubmissionEndTime()
    {
        return $this->themeSubmissionEndTime;
    }

    /**
     * Set themeVotingStartTime
     *
     * @param \DateTime $themeVotingStartTime
     * @return Party
     */
    public function setThemeVotingStartTime($themeVotingStartTime)
    {
        $this->themeVotingStartTime = $themeVotingStartTime;

        return $this;
    }

    /**
     * Get themeVotingStartTime
     *
     * @return \DateTime 
     */
    public function getThemeVotingStartTime()
    {
        return $this->themeVotingStartTime;
    }

    /**
     * Set themeVotingEndTime
     *
     * @param \DateTime $themeVotingEndTime
     * @return Party
     */
    public function setThemeVotingEndTime($themeVotingEndTime)
    {
        $this->themeVotingEndTime = $themeVotingEndTime;

        return $this;
    }

    /**
     * Get themeVotingEndTime
     *
     * @return \DateTime 
     */
    public function getThemeVotingEndTime()
    {
        return $this->themeVotingEndTime;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function isVotingTime(\DateTime $time = null)
    {
        if (!$time) {
            $time = new \DateTime;
        }
        return $time >= $this->getThemeVotingStartTime() && $time < $this->getThemeVotingEndTime();
    }

    /**
     * Set projectVotingStartTime
     *
     * @param \DateTime $projectVotingStartTime
     * @return Party
     */
    public function setProjectVotingStartTime($projectVotingStartTime)
    {
        $this->projectVotingStartTime = $projectVotingStartTime;

        return $this;
    }

    /**
     * Get projectVotingStartTime
     *
     * @return \DateTime 
     */
    public function getProjectVotingStartTime()
    {
        return $this->projectVotingStartTime;
    }

    /**
     * Set projectVotingEndTime
     *
     * @param \DateTime $projectVotingEndTime
     * @return Party
     */
    public function setProjectVotingEndTime($projectVotingEndTime)
    {
        $this->projectVotingEndTime = $projectVotingEndTime;

        return $this;
    }

    /**
     * Get projectVotingEndTime
     *
     * @return \DateTime 
     */
    public function getProjectVotingEndTime()
    {
        return $this->projectVotingEndTime;
    }

    public function isProjectVotingTime(\DateTime $time = null)
    {
        if (!$time) {
            $time = new \DateTime;
        }
        return $time >= $this->getProjectVotingStartTime() && $time < $this->getProjectVotingEndTime();
    }

    public function isEnded(\DateTime $time = null)
    {
        if (!$time) {
            $time = new \DateTime;
        }
        return $time > $this->getProjectVotingEndTime();
    }

    /**
     * Set audienceChoiceRating
     *
     * @param boolean $audienceChoiceRating
     *
     * @return Party
     */
    public function setAudienceChoiceRating($audienceChoiceRating)
    {
        $this->audienceChoiceRating = $audienceChoiceRating;

        return $this;
    }

    /**
     * Get audienceChoiceRating
     *
     * @return boolean
     */
    public function getAudienceChoiceRating()
    {
        return $this->audienceChoiceRating;
    }
}
