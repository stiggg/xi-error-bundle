<?php

namespace Xi\Bundle\ErrorBundle\Service;

use Xi\Bundle\ErrorBundle\Component\ExceptionFormatter;
use Exception;

/**
 * Service for exception formatter.
 *
 * @author Sami Tikka <stikka@iki.fi>
 */
class ExceptionFormatterService
{
    /**
     *
     * @var string
     */
    private $environment;

    /**
     *
     * @param string $environment
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
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
            $this->environment,
            array('test', 'dev')
        );
    }
}
