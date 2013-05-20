<?php

namespace Xi\Bundle\ErrorBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Xi\Bundle\ErrorBundle\Exception\AssertException;
use Psr\Log\LoggerInterface;

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
     * @var HttpKernelInterface
     */
    private $kernel;

    /**
     *
     * @param LoggerInterface $logger
     * @param HttpKernelInterface $kernel
     */
    public function __construct(LoggerInterface $logger, HttpKernelInterface $kernel)
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

        // stop assertation exception propagation
        if ($exception instanceof AssertException) {
            return;
        }

        $this->logger->error($exception);
    }
}
