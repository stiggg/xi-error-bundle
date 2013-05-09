<?php

namespace Xi\Bundle\ErrorBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Monolog\Logger;
use Exception;
use AppKernel;

/**
 * Listens kernel exceptions and logs them to environment specific files.
 *
 * @author Sami Tikka <stikka@iki.fi>
 */
class ExceptionEventListener
{
    /**
     *
     * @var Logger
     */
    private $logger;

    /**
     *
     * @var AppKernel
     */
    private $kernel;

    /**
     *
     * @param Logger $logger
     * @param AppKernel $kernel
     */
    public function __construct(Logger $logger, AppKernel $kernel)
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
        $this->logger->err($exception);
    }

    /**
     * Returns exception message based on environment. In test/dev, we can
     * show user original exception message, otherwise we want to hide it
     * with some generic message.
     *
     * @param   Exception   $e
     * @param   string      $defaultMessage
     * @param   array       $safeEnvironments Array of environments, where it's safe
     *                      to display unfiltered exception messages.
     * @return  string
     */
    public function getExceptionMessageForEnvironment(
        Exception $e,
        $defaultMessage,
        $safeEnvironments = array('test', 'dev')
    ) {
        if (in_array($this->kernel->getEnvironment(), $safeEnvironments)) {
            return $e->getMessage();
        }

        return $defaultMessage;
    }
}
