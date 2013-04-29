<?php

namespace Geek\PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Party
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id = '';

    /**
     * @ORM\Column(type="datetime")
     */
    protected $startTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $endTime;

    public function __construct()
    {
        $this->startTime = new \DateTime;
        $this->endTime = new \DateTime;
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
}