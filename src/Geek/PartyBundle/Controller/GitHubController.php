<?php
/**
 * Created by PhpStorm.
 * User: gosha
 * Date: 9/22/14
 * Time: 11:52
 */

namespace Geek\PartyBundle\Controller;


use AppKernel;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\HttpFoundation\Response;

class GitHubController extends Controller
{
    public function hookAction()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->get('logger');
        $logger->info('Github hook triggered');
        $logger->debug($this->getRequest()->getContent());

        $branch = $this->container->getParameter('branch') ?: 'master';
        /** @var AppKernel $kernel */
        $kernel = $this->get('kernel');
        $dir = $kernel->getRootDir();
        $command = "/usr/bin/git --work-tree={$dir} pull http {$branch} 2>&1";
        $output = [];
        exec($command, $output, $return_code);

        $output = implode("\n", $output);
        $logger->info($output);

        $input = new ArgvInput(['console','cache:clear', '--env=prod']);
        $application = new Application($kernel);
        $application->run($input);

        $response = new Response($output);
        return $response;
    }
} 