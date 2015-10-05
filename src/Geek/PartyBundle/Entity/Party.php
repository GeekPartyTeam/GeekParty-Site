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
     * @ORM\Column(type="datetime")
     */
    protected $showResultsTime;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->startTime = new \DateTime();
        $this->endTime = new \DateTime();
        $this->themeSubmissionStartTime = new \DateTime();
        $this->themeSubmissionEndTime = new \DateTime();
        $this->themeVotingStartTime = new \DateTime();
        $this->themeVotingEndTime = new \DateTime();
        $this->projectVotingStartTime = new \DateTime();
        $this->projectVotingEndTime = new \DateTime();
        $this->showResultsTime = new \DateTime();
    }

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
     * @param \DateTime $value
     * @return Party
     */
    public function setStartTime(\DateTime $value = null)
    {
        if ($value === null) {
            return $this;
        }

        $this->startTime = $value;
    
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
     * @param \DateTime $value
     * @return Party
     */
    public function setEndTime(\DateTime $value = null)
    {
        if ($value === null) {
            return $this;
        }

        $this->endTime = $value;
    
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

    /**
     * @return string
     */
    public function getName()
    {
        return preg_replace('/gp(.*)/', 'GP#$1', $this->getId());
    }

    /**
     * @return bool
     */
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
     * @param \DateTime $value
     * @return Party
     */
    public function setThemeSubmissionStartTime(\DateTime $value = null)
    {
        if ($value === null) {
            return $this;
        }

        $this->themeSubmissionStartTime = $value;

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
     * @param \DateTime $value
     * @return Party
     */
    public function setThemeSubmissionEndTime(\DateTime $value = null)
    {
        if ($value === null) {
            return $this;
        }

        $this->themeSubmissionEndTime = $value;

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
     * @param \DateTime $value
     * @return Party
     */
    public function setThemeVotingStartTime(\DateTime $value = null)
    {
        if ($value === null) {
            return $this;
        }

        $this->themeVotingStartTime = $value;

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
     * @param \DateTime $value
     * @return Party
     */
    public function setThemeVotingEndTime(\DateTime $value = null)
    {
        if ($value === null) {
            return $this;
        }

        $this->themeVotingEndTime = $value;

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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @param \DateTime|null $time
     * @return bool
     */
    public function isThemeVotingTime(\DateTime $time = null)
    {
        if (!$time) {
            $time = new \DateTime;
        }
        return $time >= $this->getThemeVotingStartTime() && $time < $this->getThemeVotingEndTime();
    }

    /**
     * @param \DateTime|null $time
     * @return bool
     */
    public function isThemeSubmissionTime(\DateTime $time = null)
    {
        if (!$time) {
            $time = new \DateTime;
        }
        return $time >= $this->getThemeSubmissionStartTime() && $time < $this->getThemeSubmissionEndTime();
    }

    /**
     * Set projectVotingStartTime
     *
     * @param \DateTime $value
     * @return Party
     */
    public function setProjectVotingStartTime(\DateTime $value = null)
    {
        if ($value === null) {
            return $this;
        }

        $this->projectVotingStartTime = $value;

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
     * @param \DateTime $value
     * @return Party
     */
    public function setProjectVotingEndTime(\DateTime $value = null)
    {
        if ($value === null) {
            return $this;
        }

        $this->projectVotingEndTime = $value;

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

    /**
     * @return mixed
     */
    public function getShowResultsTime()
    {
        return $this->showResultsTime;
    }

    /**
     * @param mixed $showResultsTime
     * @return Party
     */
    public function setShowResultsTime($showResultsTime)
    {
        $this->showResultsTime = $showResultsTime;

        return $this;
    }

    /**
     * @param \DateTime|null $time
     * @return bool
     */
    public function isProjectVotingTime(\DateTime $time = null)
    {
        if (!$time) {
            $time = new \DateTime;
        }

        return $time >= $this->getProjectVotingStartTime() && $time < $this->getProjectVotingEndTime();
    }

    public function isShowingResultsTime(\DateTimeInterface $time = null)
    {
        if (!$time) {
            $time = new \DateTime;
        }

        return $time >= $this->getShowResultsTime();
    }

    /**
     * @param \DateTime|null $time
     * @return bool
     */
    public function isEnded(\DateTime $time = null)
    {
        if (!$time) {
            $time = new \DateTime;
        }
        return $time > $this->getProjectVotingEndTime();
    }

    /**
     * @return bool
     */
    public function hasUserRating()
    {
        return $this->getProjectVotingEndTime() >= new \DateTime('2015-07-22');
    }
}
