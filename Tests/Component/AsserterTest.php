<?php

namespace Xi\Bundle\ErrorBundle\Tests\Component;

use Xi\Bundle\ErrorBundle\Component\ExceptionFormatter;
use Xi\Bundle\ErrorBundle\Component\Asserter;
use Xi\Bundle\ErrorBundle\Tests\TestMocker;
use Psr\Log\LoggerInterface;
use PHPUnit_Framework_TestCase;
use Exception;

/**
 * @author Sami Tikka <stikka@iki.fi>
 */
class AsserterTest extends TestMocker
{
    public function setUp()
    {
        $this->assertMessage = 'failed asserting that rocket has enough fuel: ';
    }

    /**
     * @test
     */
    public function assertShouldThrowException()
    {
        $fuelAmount = 9;

        $message = $this->assertMessage . $fuelAmount;

        $this->setExpectedException(
            'Xi\Bundle\ErrorBundle\Exception\AssertException',
            $message
        );

        Asserter::callback(function ($fuel) {
            if ($fuel < 10) return false;

            return true;
        }, array($fuelAmount), $message);
    }

    /**
     * @test
     */
    public function assertShouldCallLogger()
    {
        $fuelAmount = 1;

        $message = $this->assertMessage . $fuelAmount;

        $this->setExpectedException(
            'Xi\Bundle\ErrorBundle\Exception\AssertException',
            $message
        );

        $logger = $this->getLoggerInterfaceMock();
        $logger->expects($this->once())
            ->method('error');

        Asserter::callback(function ($fuel) {
            if ($fuel < 10) return false;

            return true;
        }, array($fuelAmount), $message, $logger);
    }

    /**
     * @test
     */
    public function assertingTrueShouldThrowException()
    {
        $fuelAmount = 11;

        $message = $this->assertMessage . $fuelAmount;

        $this->setExpectedException(
            'Xi\Bundle\ErrorBundle\Exception\AssertException',
            $message
        );

        Asserter::true(10 > $fuelAmount, $message);
    }
}
