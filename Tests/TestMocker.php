<?php

namespace Xi\Bundle\ErrorBundle\Tests;

use Psr\Log\LoggerInterface;
use PHPUnit_Framework_TestCase;

/**
 * @author Sami Tikka <stikka@iki.fi>
 */
class TestMocker extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @return LoggerInterface
     */
    protected function getLoggerInterfaceMock()
    {
        return $this->getMock('Psr\Log\LoggerInterface', array(
            'emergency',
            'alert',
            'critical',
            'error',
            'warning',
            'notice',
            'info',
            'debug',
            'log'
        ));
    }
}
