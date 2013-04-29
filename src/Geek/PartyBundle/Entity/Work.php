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
    protected $id = '';

    /**
     * @ORM\Column
     */
    protected $name = '';

    /**
     * @ORM\Column
     */
    protected $description = '';

    /**
     * @ORM\Column(type="integer")
     */
    protected $width = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $height = 0;

    /**
     * @ORM\OneToMany(targetEntity="WorkAuthor",mappedBy="work")
     */
    private $authors;

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
    
        return $this;
    }

    /**
     * Remove authors
     *
     * @param \Geek\PartyBundle\Entity\WorkAuthor $authors
     */
    public function removeAuthor(\Geek\PartyBundle\Entity\WorkAuthor $authors)
    {
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
}