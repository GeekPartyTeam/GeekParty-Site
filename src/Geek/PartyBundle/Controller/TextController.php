<?php
/**
 * Коршунов Георгий <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller;

class TextController extends Base\CRUDController
{
    public function getEntity()
    {
        return 'Text';
    }
}