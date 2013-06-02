<?php

namespace Geek\PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Team 
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
    protected $name;

    /**
     * @ORM\Column
     */
    protected $description;

    /**
     * @ORM\Column
     */
    protected $contacts;
    
    /**
     * @ORM\OneToMany(targetEntity="TeamMember", mappedBy="team", cascade="all")
     */
    protected $members;

    /**
     * @ORM\OneToOne(targetEntity="User")
     */
    protected $leader;

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
     * Set name
     *
     * @param string $name
     * @return Team
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
     * @return Team
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
     * Set contacts
     *
     * @param string $contacts
     * @return Team
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
    
        return $this;
    }

    /**
     * Get contacts
     *
     * @return string 
     */
    public function getContacts()
    {
        return $this->contacts;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add members
     *
     * @param \Geek\PartyBundle\Entity\TeamMember $members
     * @return Team
     */
    public function addMember(\Geek\PartyBundle\Entity\TeamMember $members)
    {
        $this->members[] = $members;
    
        return $this;
    }

    /**
     * Remove members
     *
     * @param \Geek\PartyBundle\Entity\TeamMember $members
     */
    public function removeMember(\Geek\PartyBundle\Entity\TeamMember $members)
    {
        $this->members->removeElement($members);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set leader
     *
     * @param \Geek\PartyBundle\Entity\User $leader
     * @return Team
     */
    public function setLeader(\Geek\PartyBundle\Entity\User $leader = null)
    {
        $this->leader = $leader;
    
        return $this;
    }

    /**
     * Get leader
     *
     * @return \Geek\PartyBundle\Entity\User 
     */
    public function getLeader()
    {
        return $this->leader;
    }
}
