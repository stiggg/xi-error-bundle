<?php

namespace Xi\Bundle\ErrorBundle\Tests\EventListener;

use Xi\Bundle\ErrorBundle\EventListener\ExceptionEventListener;
use Xi\Bundle\ErrorBundle\Tests\TestMocker;
use Xi\Bundle\ErrorBundle\Exception\AssertException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Exception;

/**
 * @author Sami Tikka <stikka@iki.fi>
 */
class ExceptionEventListenerTest extends TestMocker
{
    /**
     *
     * @var ExceptionEventListener
     */
    var $listener;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @var HttpKernelInterface
     */
    private $kernel;

    public function setUp()
    {
        $this->kernel = $this->getKernelMock();

        $this->logger = $this->getLoggerInterfaceMock();

        $this->listener = new ExceptionEventListener(
            $this->logger,
            $this->kernel
        );
    }

    /**
     * @test
     */
    public function shouldCatchException()
    {
        $this->dispatchException();
    }

    /**
     * @test
     */
    public function shouldNotCatchAssertationException()
    {
        $this->logger->expects($this->never())
            ->method('error');

        $exception = new AssertException('Failed to identify pike as a fish');
        $this->dispatchException($exception);
    }

    private function dispatchException($exception = null)
    {
        $dispatcher = new EventDispatcher();

        $dispatcher->addListener('kernel.exception', array($this->listener, 'onKernelException'));

        if ($exception == null) {
            $exception = new Exception('failure is inevitable', 500);
        }
        $event = new GetResponseForExceptionEvent(
            $this->kernel,
            (new Request()),
            HttpKernelInterface::MASTER_REQUEST,
            $exception
        );

        $dispatcher->dispatch('kernel.exception', $event);
    }
}