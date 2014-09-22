<?php
/**
 * Created by PhpStorm.
 * User: gosha
 * Date: 9/22/14
 * Time: 11:52
 */

namespace Geek\PartyBundle\Controller;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GitHubController extends Controller
{
    public function hookAction()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->get('logger');
        $logger->info('Github hook triggered');
        $logger->debug($this->getRequest()->getContent());

        $command = '/usr/bin/git --work-tree=/usr/share/nginx/html/geekparty pull http develop 2>&1';
        $output = [];
        exec($command, $output, $return_code);

        $output = implode("\n", $output);
        $logger->info($output);

        $response = new Response($output);
        return $response;
    }
} 