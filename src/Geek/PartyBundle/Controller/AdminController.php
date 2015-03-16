<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller;

use Geek\PartyBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseController
{
    /**
     * @Route("/file_manager")
     * @Template()
     */
    public function fileManagerAction()
    {
        #   /$$$$$$$$ /$$$$$$ /$$           /$$      /$$
        #  | $$_____/|_  $$_/| $$          | $$$    /$$$
        #  | $$        | $$  | $$  /$$$$$$ | $$$$  /$$$$  /$$$$$$  /$$$$$$$   /$$$$$$   /$$$$$$   /$$$$$$   /$$$$$$
        #  | $$$$$     | $$  | $$ /$$__  $$| $$ $$/$$ $$ |____  $$| $$__  $$ |____  $$ /$$__  $$ /$$__  $$ /$$__  $$
        #  | $$__/     | $$  | $$| $$$$$$$$| $$  $$$| $$  /$$$$$$$| $$  \ $$  /$$$$$$$| $$  \ $$| $$$$$$$$| $$  \__/
        #  | $$        | $$  | $$| $$_____/| $$\  $ | $$ /$$__  $$| $$  | $$ /$$__  $$| $$  | $$| $$_____/| $$
        #  | $$       /$$$$$$| $$|  $$$$$$$| $$ \/  | $$|  $$$$$$$| $$  | $$|  $$$$$$$|  $$$$$$$|  $$$$$$$| $$
        #  |__/      |______/|__/ \_______/|__/     |__/ \_______/|__/  |__/ \_______/ \____  $$ \_______/|__/
        #                                                                              /$$  \ $$
        #                                                                             |  $$$$$$/
        #                                                                              \______/
        return [];
    }
}