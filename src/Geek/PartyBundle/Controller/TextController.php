<?php
/**
 * Коршунов Георгий <georgy.k@propellerads.com>
 */

namespace Geek\PartyBundle\Controller;

class TextController extends Base\CRUDController
{
    public function getEntity()
    {
        return 'Text';
    }
}