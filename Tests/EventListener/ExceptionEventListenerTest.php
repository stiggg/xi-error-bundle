<?php

namespace Xi\Bundle\ErrorBundle\Tests\EventListener;

use PHPUnit_Framework_TestCase;
use AppKernel;
use Exception;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Xi\Bundle\ErrorBundle\EventListener\ExceptionEventListener;

require_once($_SERVER['KERNEL_DIR'] . "/AppKernel.php");

/**
 * @author Sami Tikka <stikka@iki.fi>
 */
class ExceptionEventListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var string
     */
    var $exceptionLogPathPath;

    /**
     *
     * @var ExceptionEventListener
     */
    var $listener;

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
        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $this->kernel = $kernel;

        $this->container = $kernel->getContainer();

        $exceptionLogPath = $_SERVER['KERNEL_DIR'] . '/logs/exception.test.log';
        if (file_exists($exceptionLogPath)) {
            unlink($exceptionLogPath);
        }

        $this->exceptionLogPath = $exceptionLogPath;

        $this->listener = $this->container->get('kernel.listener.exception_listener');
    }

    /**
     * @test
     */
    public function shouldCatchException()
    {
        $dispatcher = new EventDispatcher();

        $dispatcher->addListener('kernel.exception', array($this->listener, 'onKernelException'));

        $kernel = $this->getKernel();
        $event = new GetResponseForExceptionEvent(
            $kernel,
            (new Request()),
            HttpKernelInterface::MASTER_REQUEST,
            (new Exception('failure is inevitable', 500))
        );
        $dispatcher->dispatch('kernel.exception', $event);

        $this->assertTrue(file_exists($this->exceptionLogPath));
    }

    /**
     * @test
     */
    public function getExceptionMessageForEnvironmentShouldReturnRightMessageDependingOnEnvironment()
    {
        $detailedExceptionMessage = 'very detailed exception message';
        $generalExceptionMessage = 'internal error';
        $e = new Exception($detailedExceptionMessage, 500);

        $this->assertEquals(
            $detailedExceptionMessage,
            $this->listener->getExceptionMessageForEnvironment($e, $generalExceptionMessage)
        );

        $this->assertEquals(
            $generalExceptionMessage,
            $this->listener->getExceptionMessageForEnvironment(
                $e, $generalExceptionMessage, array()
            )
        );
    }

    /**
     * @return KernelInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return Container
     */
    public function getKernel()
    {
        return $this->kernel;
    }
}