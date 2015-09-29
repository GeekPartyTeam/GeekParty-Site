<?php

namespace Geek\PartyBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Hook\Call\BeforeScenario;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext, KernelAwareContext
{
    private $kernel;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function before()
    {
      $kernel = $this->kernel;

      $app = new Application($kernel);
      $app->setAutoExit(false);
      $this->runConsole($app, "doctrine:schema:create");
      $this->runConsole($app, "doctrine:fixtures:load", ['-n' => true]);
    }

    /**
     * @param Application $app
     * @param $command
     * @param array $options
     * @return int
     * @throws \Exception
     */
    protected function runConsole(Application $app, $command, array $options = [])
    {
      $options["-e"] = "test";
      $options["-q"] = null;
      $options = array_merge($options, ['command' => $command]);
      return $app->run(new ArrayInput($options));
    }
}
