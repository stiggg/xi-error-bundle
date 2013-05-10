<?php

namespace Xi\Bundle\ErrorBundle\Tests\Component;

use Xi\Bundle\ErrorBundle\Component\ExceptionFormatter;
use PHPUnit_Framework_TestCase;
use Exception;

/**
 * @author Sami Tikka <stikka@iki.fi>
 */
class ExceptionFormatterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function formatMessageShouldReturnRightMessageDependingOnEnvironment()
    {
        $detailedExceptionMessage = 'very detailed exception message';
        $generalExceptionMessage = 'internal error';
        $e = new Exception($detailedExceptionMessage, 500);

        $this->assertEquals(
            $detailedExceptionMessage,
            ExceptionFormatter::formatMessage($e, $generalExceptionMessage, 'dev')
        );

        $this->assertEquals(
            $generalExceptionMessage,
            ExceptionFormatter::formatMessage(
                $e, $generalExceptionMessage, 'prod'
            )
        );

        $this->assertEquals(
            $generalExceptionMessage,
            ExceptionFormatter::formatMessage(
                $e, $generalExceptionMessage, 'dev', array()
            )
        );
    }
}