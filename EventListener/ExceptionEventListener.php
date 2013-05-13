<?php

namespace Xi\Bundle\ErrorBundle\EventListener;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Listens kernel exceptions and logs them to environment specific files.
 *
 * @author Sami Tikka <stikka@iki.fi>
 */
class ExceptionEventListener
{
    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     *
     * @param LoggerInterface $logger
     * @param KernelInterface $kernel
     */
    public function __construct(LoggerInterface $logger, KernelInterface $kernel)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
    }

    /**
     * Listener executes this method on exception.
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $this->logger->error($exception);
    }
}
