<?php

namespace Geek\PartyBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`User`")
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
    public $city = '';

    /**
     * @ORM\Column
     */
    public $twitter = '';

    /**
     * @ORM\Column(type="boolean")
     */
    public function __construct()
    {
        parent::__construct();
        $this->emailCanonical = '';
    }

    public $skill_code = false;

    /**
     * @ORM\Column(type="boolean")
     */
    public $skill_graphics = false;

    /**
     * @ORM\Column(type="boolean")
     */
    public $skill_sound = false;

    /**
     * @ORM\Column(type="boolean")
     */
    public $skill_gamedesign = false;

    /**
     * @ORM\Column
     */
    public $skills = '';

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    protected $firstname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    protected $lastname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255)
     */
    public $facebookId = '';

    /**
     * @var string
     *
     * @ORM\Column(name="vkontakteId", type="string", length=255)
     */
    public $vkontakteId = '';

    public function serialize()
    {
        $parent = parent::serialize();
        $data = [
            'parent' => $parent,
            'facebookId' => $this->facebookId,
            'vkontakteId' => $this->vkontakteId,
        ];
        return serialize($data);
    }

    public function unserialize($data)
    {
        $data = unserialize($data);
        if (is_array($data)) {
            $this->setVkontakteId($data['vkontakteId']);
            $this->setFacebookId($data['facebookId']);
            parent::unserialize($data['parent']);
        }
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        $this->setUsername($facebookId);
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->addRole('ROLE_FACEBOOK');
        }
        if (isset($fbdata['first_name'])) {
            $this->setFirstname($fbdata['first_name']);
        }
        if (isset($fbdata['last_name'])) {
            $this->setLastname($fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
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

    /**
     * Set vkontakteId
     *
     * @param string $vkontakteId
     * @return User
     */
    public function setVkontakteId($vkontakteId)
    {
        $this->vkontakteId = $vkontakteId;
        $this->setUsername($vkontakteId);

        return $this;
    }

    /**
     * Get vkontakteId
     *
     * @return string 
     */
    public function getVkontakteId()
    {
        return $this->vkontakteId;
    }
}
