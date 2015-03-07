<?php

namespace Geek\PartyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Work 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column
     */
    protected $shortname;

    /**
     * @ORM\Column
     */
    protected $name = '';
    
    /**
     * @ORM\Column
     */
    protected $description = '';

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $longDescription = '';

    /**
     * @ORM\Column
     */
    protected $source = '';
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $width = 640;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $height = 480;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $author;

    /**
     * @ORM\ManyToOne(targetEntity="Party")
     */
    protected $party;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $time;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="ProjectComment", mappedBy="project")
     */
    protected $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->time = new \DateTime();
    }
    
    /**
     * Set id
     *
     * @param string $id
     * @return Work
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
     * Set name
     *
     * @param string $name
     * @return Work
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Work
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
     * Set source
     *
     * @param string $source
     * @return Work
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    public function __toString()
    {
        return $this->getId();
    }

    /**
     * Set party
     *
     * @param Party $party
     * @return Work
     */
    public function setParty(Party $party = null)
    {
        $this->party = $party;
    
        return $this;
    }

    /**
     * Get party
     *
     * @return Party
     */
    public function getParty()
    {
        return $this->party;
    }

    /**
     * Get author
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param User $author
     * @return Work
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Work
     */
    public function setWidth($width)
    {
        $this->width = $width;
    
        return $this;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Work
     */
    public function setHeight($height)
    {
        $this->height = $height;
    
        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set longDescription
     *
     * @param string $longDescription
     * @return Work
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
    
        return $this;
    }

    /**
     * Get longDescription
     *
     * @return string 
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Work
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
     * Set shortname
     *
     * @param string $shortname
     * @return Work
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * Get shortname
     *
     * @return string 
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    public function getComments()
    {
        return $this->comments;
    }
}
