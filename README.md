# xi-error-bundle

This is a Symfony2 bundle for error and exception handling and logging. It also includes assertion component/service and component/service for formatting user readable exception messages according to environment.

## Installation

This bundle requires:
* PHP >=5.3
* Symfony >=2.1
* Monolog >= 1.3

Add to composer.json:

    "monolog/monolog": ">=1.3.0",
    "xi/error-bundle": "dev-master"

Monolog 1.3 or greater and compatible Symfony Monolog bridge is required, since that's the first PSR-3 compatible version.

## Exception logging

Include XiErrorBundle in your AppKernel.php, and you get automatic exception logging. Logs are created in *%kernel.logs_dir%/exception.%kernel.environment%.log*.

```php
<?php
...
    public function registerBundles()
    {
        $bundles = array(
        ...
            new Xi\Bundle\ErrorBundle\XiErrorBundle()
        ...
        );
    }
...
```

### Why use separate exception logging?

Default Symfony2 logs have lots of other stuff, that does not help much with debugging and log surveying process. This logger also includes stack traces for exceptions.

## Exception message formatting

During development, you want to see exactly what went wrong. In production on the other hand, you don't want to show the actual, possibly very detailed exception message. Exception message formatter component/service takes an exception, and returns the detailed or the general error message *depending on the current environment*. By default, the original exception message is intended to be shown to developer in **"test"** or **"dev"** environment, and end-user sees some more general error message in other environments.

```php
<?php

    try {
        $this->em->persist($user);
        $this->em->flush();
    } catch (Exception $e) {
        # use the service...
        $service = $this->get('xi_error.exception_formatter');
        $message = $service->formatMessage($e, 'could not process your form, please try again');

        # ...or the component directly
        #$message = \Xi\Bundle\ErrorBundle\Component\ExceptionFormatter::formatMessage(
        #    $e,
        #    'could not process your form, please try again',
        #    $this->get('kernel')->getEnvironment()
        #);
    }

    return new Response($message);
}
```

## Assertions

This bundle includes assertion component & service. The component can be used on it's own. The service does automatic assertion logging.

### Why use separate assertion logging?

Design principle of this assertion component differs from PHP's own assert, which is recommended to be used during development. If you code your application so, that behind some point in the execution path you expect input data to be logically coherent and sanitized, use assertions to enforce it. Assertation component throws exception when abnormal conditions are encountered.

```php
<?php

    ...
    $message = sprintf('failed asserting that "%s" is more than 9', $number);

    # use the service with logging...
    $service = $this->get('xi_error.asserter_service');
    $service->assertCallback(function($number) {
        return $number > 9 ? true : false;
    }, array($number), $message);

    # ...or the component directly, no logging
    #\Xi\Bundle\ErrorBundle\Component\Asserter::true($number > 8, $message);

    # continue as normal
    ...
}
```

Service methods are:
* assertCallback(callable $callback, array $callbackParameterArray, string $assertationMessage)
* assertTrue(mixed $assertion, string $assertationMessage)

Equivalent component static methods:
* callback(callable $callback, array $callbackParameterArray, string $assertationMessage[, \Psr\Log\LoggerInterface $logger])
* true(mixed $assertion, string $assertationMessage[, \Psr\Log\LoggerInterface $logger])

Optional LoggerInterface parameter takes a PSR-3 compatible logger.

Logs are created in *%kernel.logs_dir%/assert.%kernel.environment%.log*.
