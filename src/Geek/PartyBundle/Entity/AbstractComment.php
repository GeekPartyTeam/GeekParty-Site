<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="integer")
 * @ORM\DiscriminatorMap({0 = "ProjectComment", 1 = "ArticleComment"})
 */
class AbstractComment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $date;
    /**
     * @ORM\Column
     */
    protected $ip = '';
    /**
     * @ORM\Column
     */
    protected $userAgent = '';
    /**
     * @ORM\Column
     */
    protected $text;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $author;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param mixed $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
}