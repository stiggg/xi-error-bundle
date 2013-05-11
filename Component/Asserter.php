<?php

namespace Xi\Bundle\ErrorBundle\Component;

use Xi\Bundle\ErrorBundle\Exception\AssertException;
use Psr\Log\LoggerInterface;
use Exception;
use LogicException;
use Closure;

/**
 * Asserting and logging.
 *
 * @author Sami Tikka <stikka@iki.fi>
 */
class Asserter
{
    /**
     *
     * @param callable $callback
     * @param array $params
     * @param string $message
     * @param LoggerInterface $logger Optional logger
     * @throws AssertException
     */
    public static function callback(
        $callback,
        $params,
        $message,
        LoggerInterface $logger = null
    ) {
        if (is_callable($callback)) {
            $passed = call_user_func_array($callback, $params);
        } else {
            throw new LogicException('First parameter should be callable.');
        }

        return self::handler($passed, $message, $logger);
    }

    /**
     *
     * @param boolean $assertion
     * @param string $message
     * @param LoggerInterface $logger
     * @return true
     */
    public static function true(
        $assertion,
        $message,
        LoggerInterface $logger = null
    ) {
        return self::handler($assertion, $message, $logger);
    }

    /**
     *
     * @param boolean $passed
     * @param string $message
     * @param LoggerInterface $logger
     * @return true
     * @throws AssertException
     */
    private static function handler(
        $passed,
        $message,
        LoggerInterface $logger = null
    ) {
        $e = new AssertException($message);

        if (!$passed && $logger !== null) {
            $logger->error($e);
        }

        if (!$passed) {
            throw $e;
        }

        return $passed;
    }
}
