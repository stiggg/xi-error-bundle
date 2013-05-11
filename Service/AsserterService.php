<?php

namespace Xi\Bundle\ErrorBundle\Service;

use Xi\Bundle\ErrorBundle\Component\Asserter;
use Psr\Log\LoggerInterface;
use Exception;
use AppKernel;

/**
 * Asserter service.
 *
 * @author Sami Tikka <stikka@iki.fi>
 */
class AsserterService
{
    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param callable $callback
     * @param array $params
     * @param string $message
     */
    public function assertCallback(
        $callback,
        $params,
        $message
    ) {
        Asserter::callback($callback, $params, $message, $this->logger);
    }

    /**
     *
     * @param boolean $assertion
     * @param string $message
     */
    public function assertTrue(
        $assertion,
        $message
    ) {
        Asserter::true($assertion, $message, $this->logger);
    }
}
