<?php

namespace Xi\Bundle\ErrorBundle\Service;

use Xi\Bundle\ErrorBundle\Component\ExceptionFormatter;
use Exception;
use AppKernel;

/**
 * Format exceptions according to environment.
 *
 * @author Sami Tikka <stikka@iki.fi>
 */
class ExceptionFormatter
{
    /**
     *
     * @var AppKernel
     */
    private $kernel;

    /**
     *
     * @param AppKernel $kernel
     */
    public function __construct(AppKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Returns exception message based on environment. In test/dev, we can
     * show user original exception message, otherwise we want to hide it
     * with some generic message.
     *
     * @param   Exception   $e
     * @param   string      $defaultMessage
     * @return  string
     */
    public function formatMessage(
        Exception $e,
        $defaultMessage
    ) {
        return ExceptionFormatter::formatMessage(
            $e,
            $defaultMessage,
            $this->kernel->getEnvironment(),
            array('test', 'dev')
        );
    }
}
