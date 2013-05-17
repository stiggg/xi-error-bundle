# xi-error-bundle

This is a Symfony2 bundle for error and exception handling and logging. It also includes components for asserting and formatting the exception messages according to environment.

Logging requires any PSR-3 compatible logger.

## Installation

Requires:

* PHP >=5.3
* Symfony >=2.1
* Monolog >= 1.3

Monolog 1.3 or greater and compatible Symfony Monolog bridge is required, since that's the first PSR-3 compatible version.

## Exception logging

Just include XiErrorBundle in your AppKernel.php, and you get automatic exception logging. Logs are created in <kernel.logs_dir>/exception.<kernel.environment>.log.

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

During development, you want to see exactly what went wrong. In production on the other hand, you don't want to show the actual, possibly very detailed exception message. Exception message formatter component/service takes an exception, and returns the detailed or the general error message depending on current environment.

This component is especially useful, when you need to show the user the result of some Ajax call. You can format the message in server, and see appropriate message depending on the environment.

In your controller / service:

```php
<?php

try {
    throw new Exception(sprintf('detailed exception message: we died because database said "%s"', 'could not connect'));
} catch (Exception $e) {

    # you can either use the service...
    $service = $this->get('xi_error.exception_formatter');
    $message = $service->formatMessage($e, 'general exception message: could not process your form, please try again');

    # ...or use the component directly
    #$message = \Xi\Bundle\ErrorBundle\Component\ExceptionFormatter::formatMessage(
    #    $e,
    #    'could not process your form, please try again',
    #    $this->get('kernel')->getEnvironment()
    #);

    return $message;
}
```

By default, the user sees the original exception message when in "test" or "dev" environment, and some more general error message in other environments.

## Assertions

This bundle includes assertion component & service. Component can be used on it's own, service does automatic assertion logging.

### Why use separate assertion logging?

