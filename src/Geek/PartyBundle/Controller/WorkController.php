<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Geek\PartyBundle\Entity\Work;
use Geek\PartyBundle\Form\WorkType;

/**
 * Work controller.
 */
class WorkController extends CRUDController
{
    public function getEntity()
    {
        return 'Work';       
    }

    public function getRedirectPath()
    {
        return 'admin_works';
    }
}
