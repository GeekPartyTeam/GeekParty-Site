<?php
/**
 * Коршунов Георгий <georgy.k@propellerads.com>
 */

namespace Geek\PartyBundle\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends CRUDController
{
    public function getEntity()
    {
        return 'Article';
    }

    public function getRedirectPath()
    {
        return 'geek_index';
    }

    public function updateEntity($entity, Request $request, Form $form)
    {
        /** @var \Geek\PartyBundle\Entity\Article $entity */
        $entity->setAuthor($this->getUser());
        $entity->setTime(new \DateTime());
        return true;
    }
}
