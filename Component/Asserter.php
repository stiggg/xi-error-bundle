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
    public static function assertAndLog(
        Closure $callback,
        $params,
        $message,
        LoggerInterface $logger
    ) {
        if (!call_user_func_array($callback, $params)) {
            $logger->error($message);

            throw new AssertException($message);
        }
    }

    /**
     *
     * @param callable $callback
     * @param array $params
     * @param string $message
     * @throws AssertException
     */
    public static function assert(
        callable $callback,
        $params,
        $message
    ) {
        if (!call_user_func_array($callback, $params)) {
            throw new AssertException($message);
        }
    }
}
