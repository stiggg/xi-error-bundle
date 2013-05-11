<?php

namespace Xi\Bundle\ErrorBundle\Tests\Service;

use Xi\Bundle\ErrorBundle\Tests\TestMocker;
use Xi\Bundle\ErrorBundle\Service\AsserterService;
use Symfony\Component\DependencyInjection\Container;
use PHPUnit_Framework_TestCase;

/**
 * @author Sami Tikka <stikka@iki.fi>
 */
class AsserterServiceTest extends TestMocker
{
    /**
     *
     * @var AsserterService
     */
    private $service;

    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    public function setUp()
    {
        $this->logger = $this->getLoggerInterfaceMock();

        $this->service = new AsserterService($this->logger);
    }

    /**
     * @test
     */
    public function assertCallbackShouldLogAndThrowException()
    {
        $message = 'failed at failing';

        $this->setExpectedException(
            'Xi\Bundle\ErrorBundle\Exception\AssertException',
            $message
        );

        $this->logger->expects($this->once())
            ->method('error');

        $this->service->assertCallback(function($fial) {
            return $fial ? false : true;
        }, array(true), $message);
    }
}