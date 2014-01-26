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
    protected $name;
    
    /**
     * @ORM\Column
     */
    protected $description;

    /**
     * @ORM\Column
     */
    protected $source = '';
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $width;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $height;

    /**
     * @ORM\OneToMany(targetEntity="WorkAuthor", mappedBy="work", cascade={"persist","remove"})
     */
    protected $authors;

    /**
     * @ORM\ManyToOne(targetEntity="Party")
     */
    protected $party;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     */
    protected $team;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->authors = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add authors
     *
     * @param \Geek\PartyBundle\Entity\WorkAuthor $authors
     * @return Work
     */
    public function addAuthor(\Geek\PartyBundle\Entity\WorkAuthor $authors)
    {
        $this->authors[] = $authors;

        $authors->setWork($this);
    
        return $this;
    }

    /**
     * Remove authors
     *
     * @param \Geek\PartyBundle\Entity\WorkAuthor $authors
     */
    public function removeAuthor(\Geek\PartyBundle\Entity\WorkAuthor $authors)
    {
        $authors->setWork(null);

        $this->authors->removeElement($authors);
    }

    /**
     * Get authors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuthors()
    {
        return $this->authors;
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
     * Set team
     *
     * @param \Geek\PartyBundle\Entity\Team $team
     * @return Work
     */
    public function setTeam(\Geek\PartyBundle\Entity\Team $team = null)
    {
        $this->team = $team;
    
        return $this;
    }

    /**
     * Get team
     *
     * @return \Geek\PartyBundle\Entity\Team 
     */
    public function getTeam()
    {
        return $this->team;
    }
}