<?php

namespace Xi\Bundle\ErrorBundle\Component;

use Exception;

/**
 * Format exceptions according to environment.
 *
 * @author Sami Tikka <stikka@iki.fi>
 */
class ExceptionFormatter
{
    /**
     * Returns exception message based on environment. In safe environments,
     * we can show more specific message, otherwise we want to hide it
     * with some generic message.
     *
     * @param   Exception   $e
     * @param   string      $defaultMessage
     * @param   string      $environment
     * @param   array       $safeEnvironments Array of environments, where it's safe
     *                      to display unfiltered exception messages.
     * @return  string
     */
    public static function formatMessage(
        Exception $e,
        $defaultMessage,
        $environment,
        $safeEnvironments = array('test', 'dev')
    ) {
        if (in_array($environment, $safeEnvironments)) {
            return $e->getMessage();
        }

        return $defaultMessage;
    }

    public static function formatShortLogMessage(Exception $e)
    {
        return sprintf(
            "exception '%s' with message '%s' in %s:%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
    }
}
