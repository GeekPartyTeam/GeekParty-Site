<?php

namespace Geek\PartyBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
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
}
