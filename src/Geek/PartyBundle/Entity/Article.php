<?php

namespace Geek\PartyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Prism\PollBundle\Entity\Poll;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Article
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $author;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $time;

    /**
     * @var Poll
     * @ORM\ManyToOne(targetEntity="Prism\PollBundle\Entity\Poll")
     */
    protected $poll;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="ArticleComment", mappedBy="article")
     */
    protected $comments;

    function __construct()
    {
        $this->comments = new ArrayCollection();
    }

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
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set author
     *
     * @param \Geek\PartyBundle\Entity\User $author
     * @return Article
     */
    public function setAuthor(\Geek\PartyBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Geek\PartyBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Article
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
     * Set poll
     *
     * @param Poll $poll
     * @return Article
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

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
