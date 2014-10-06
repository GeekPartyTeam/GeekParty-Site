<?php

namespace Geek\PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Prism\PollBundle\Entity\Poll;

/**
 * Voter
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Voter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="userAgent", type="string", length=1024)
     */
    private $userAgent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="opinions", type="string", length=255)
     */
    private $opinions;

    /**
     * @ORM\ManyToOne(targetEntity="Prism\PollBundle\Entity\Poll")
     */
    private $poll;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Geek\PartyBundle\Entity\User")
     */
    private $user;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Voter
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     * @return Voter
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string 
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Voter
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set opinions
     *
     * @param array $opinions
     * @return Voter
     */
    public function setOpinions($opinions)
    {
        $this->opinions = $opinions;

        return $this;
    }

    /**
     * Get opinions
     *
     * @return array 
     */
    public function getOpinions()
    {
        return $this->opinions;
    }

    /**
     * Set poll
     *
     * @param Poll $poll
     * @return Voter
     */
    public function setPoll(Poll $poll = null)
    {
        $this->poll = $poll;

        return $this;
    }

    /**
     * Get poll
     *
     * @return Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }

    function __construct($ip, $userAgent, $poll, $opinions)
    {
        $this->setIp($ip);
        $this->setUserAgent($userAgent);
        $this->setPoll($poll);
        $this->setOpinions($opinions);
        $this->setTime(new \DateTime());
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Voter
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
