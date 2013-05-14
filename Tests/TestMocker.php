<?php

namespace Xi\Bundle\ErrorBundle\Tests;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Psr\Log\LoggerInterface;
use PHPUnit_Framework_TestCase;

/**
 * @author Sami Tikka <stikka@iki.fi>
 */
class TestMocker extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @return HttpKernelInterface
     */
    protected function getKernelMock()
    {
        return $this->getMockBuilder('Symfony\Component\HttpKernel\HttpKernelInterface')
            ->getMock();
    }

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
