<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Geek\PartyBundle\Entity\Party;
use Geek\PartyBundle\Form\PartyType;

/**
 * Party controller.
 */
class PartyController extends Base\CRUDController
{
    public function getEntity()
    {
        return 'Party';       
    }

    public function getRedirectPath()
    {
        return 'admin_parties';
    }
}
