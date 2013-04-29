<?php

namespace Geek\PartyBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $city = '';

    /**
     * @ORM\Column
     */
    protected $twitter = '';

    /**
     * @ORM\Column(type="boolean")
     */
    protected $skill_code = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $skill_graphics = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $skill_sound = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $skill_gamedesign = false;

    /**
     * @ORM\Column
     */
    protected $skills = '';

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
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return User
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    
        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set skill_code
     *
     * @param boolean $skillCode
     * @return User
     */
    public function setSkillCode($skillCode)
    {
        $this->skill_code = $skillCode;
    
        return $this;
    }

    /**
     * Get skill_code
     *
     * @return boolean 
     */
    public function getSkillCode()
    {
        return $this->skill_code;
    }

    /**
     * Set skill_graphics
     *
     * @param boolean $skillGraphics
     * @return User
     */
    public function setSkillGraphics($skillGraphics)
    {
        $this->skill_graphics = $skillGraphics;
    
        return $this;
    }

    /**
     * Get skill_graphics
     *
     * @return boolean 
     */
    public function getSkillGraphics()
    {
        return $this->skill_graphics;
    }

    /**
     * Set skill_sound
     *
     * @param boolean $skillSound
     * @return User
     */
    public function setSkillSound($skillSound)
    {
        $this->skill_sound = $skillSound;
    
        return $this;
    }

    /**
     * Get skill_sound
     *
     * @return boolean 
     */
    public function getSkillSound()
    {
        return $this->skill_sound;
    }

    /**
     * Set skill_gamedesign
     *
     * @param boolean $skillGamedesign
     * @return User
     */
    public function setSkillGamedesign($skillGamedesign)
    {
        $this->skill_gamedesign = $skillGamedesign;
    
        return $this;
    }

    /**
     * Get skill_gamedesign
     *
     * @return boolean 
     */
    public function getSkillGamedesign()
    {
        return $this->skill_gamedesign;
    }

    /**
     * Set skills
     *
     * @param string $skills
     * @return User
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    
        return $this;
    }

    /**
     * Get skills
     *
     * @return string 
     */
    public function getSkills()
    {
        return $this->skills;
    }
}