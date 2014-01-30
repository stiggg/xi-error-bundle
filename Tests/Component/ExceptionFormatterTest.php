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

    /**
     * @test
     */
    public function formatShortLogMessageShouldFormatNicely()
    {
        $e = new Exception('asdf');

        $this->assertEquals(sprintf(
            "exception '%s' with message '%s' in %s:%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ), ExceptionFormatter::formatShortLogMessage($e));
    }
}