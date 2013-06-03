<?php

namespace Geek\PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class WorkAuthor
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
    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="Work",inversedBy="authors")
     */
    protected $work;

    /**
     * Set id
     *
     * @param string $id
     * @return WorkAuthor
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
     * @return WorkAuthor
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
     * Set url
     *
     * @param string $url
     * @return WorkAuthor
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set work
     *
     * @param \Geek\PartyBundle\Entity\Work $work
     * @return WorkAuthor
     */
    public function setWork(\Geek\PartyBundle\Entity\Work $work = null)
    {
        $this->work = $work;
    
        return $this;
    }

    /**
     * Get work
     *
     * @return \Geek\PartyBundle\Entity\Work 
     */
    public function getWork()
    {
        return $this->work;
    }
}