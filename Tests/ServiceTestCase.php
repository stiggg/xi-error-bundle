<?php

namespace SBA\Component\Test;

use PHPUnit_Framework_TestCase;
use AppKernel;
use Exception;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\KernelInterface;

require_once($_SERVER['KERNEL_DIR'] . "/AppKernel.php");

/**
 * Base class for testing services.
 */
abstract class ServiceTestCase extends PHPUnit_Framework_Testcase
{
    /**
     * @var Container
     */
    private $container;

    /**
     *
     * @var KernelInterface
     */
    private $kernel;

    public function setUp()
    {
        parent::setUp();

        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $this->kernel = $kernel;

        $this->container = $kernel->getContainer();
    }
}
