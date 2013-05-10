<?php

namespace Xi\Bundle\ErrorBundle\Tests\Component;

use Xi\Bundle\ErrorBundle\Component\ExceptionFormatter;
use Xi\Bundle\ErrorBundle\Component\Asserter;
use Psr\Log\LoggerInterface;
use PHPUnit_Framework_TestCase;
use Exception;

/**
 * @author Sami Tikka <stikka@iki.fi>
 */
class AsserterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function assertShouldThrowException()
    {
        $message = 'rocket does not have enough fuel';

        $fuelAmount = 9;

        $this->setExpectedException(
            'Xi\Bundle\ErrorBundle\Exception\AssertException',
            $message
        );

        Asserter::assert(function ($fuel) {
            if ($fuel < 10) return false;

            return true;
        }, array($fuelAmount), $message);
    }

    /**
     * @test
     */
    public function assertAndLogShouldCallLogger()
    {
        $message = 'rocket does not have enough fuel';

        $fuelAmount = 1;

        $this->setExpectedException(
            'Xi\Bundle\ErrorBundle\Exception\AssertException',
            $message
        );
        
        $logger = $this->getLoggerInterfaceMock();
        $logger->expects($this->once())
            ->method('error');

        Asserter::assertAndLog(function ($fuel) {
            if ($fuel < 10) return false;

            return true;
        }, array($fuelAmount), $message, $logger);
    }

    /**
     *
     * @return LoggerInterface
     */
    private function getLoggerInterfaceMock()
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
