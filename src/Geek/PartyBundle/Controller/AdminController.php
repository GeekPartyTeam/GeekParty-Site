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
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        #   /$$$$$$                 /$$
        #  |_  $$_/                | $$
        #    | $$   /$$$$$$$   /$$$$$$$  /$$$$$$  /$$   /$$
        #    | $$  | $$__  $$ /$$__  $$ /$$__  $$|  $$ /$$/
        #    | $$  | $$  \ $$| $$  | $$| $$$$$$$$ \  $$$$/
        #    | $$  | $$  | $$| $$  | $$| $$_____/  >$$  $$
        #   /$$$$$$| $$  | $$|  $$$$$$$|  $$$$$$$ /$$/\  $$
        #  |______/|__/  |__/ \_______/ \_______/|__/  \__/
        #
        #
        #
        return [
            'indexText' => $this->findTextBlock('index'),
            'aboutText' => $this->findTextBlock('about'),
            'partyText' => $this->findTextBlock('party'),
        ];
    }

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

    /**
     * @Route("/users")
     * @Template()
     */
    public function usersAction()
    {
        return [
            'users' => $this->getDoctrine()
                ->getManager()
                ->getRepository('GeekPartyBundle:User')
                ->findAll()
        ];
    }
}