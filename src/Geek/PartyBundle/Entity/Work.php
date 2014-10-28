<?php

namespace Geek\PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Work 
{
    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected $id;

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
     * Constructor
     */
    public function __construct()
    {
        $this->authors = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \Geek\PartyBundle\Entity\Party $party
     * @return Work
     */
    public function setParty(\Geek\PartyBundle\Entity\Party $party = null)
    {
        $this->party = $party;
    
        return $this;
    }

    /**
     * Get party
     *
     * @return \Geek\PartyBundle\Entity\Party 
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
     * @param \Geek\PartyBundle\Entity\User $author
     * @return Work
     */
    public function setAuthor(\Geek\PartyBundle\Entity\User $author = null)
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
}
