# xi-error-bundle

This is a Symfony2 bundle for error and exception handling and logging. It also includes components for asserting and formatting the exception messages according to environment.

Logging requires any PSR-3 compatible logger.

## Installation

Works with PHP 5.3.x. If you are using Symfony2 services, Symfony requires atleast Monolog 1.3 or greater and compatible Monolog bridge, since that's the first PSR-3 compatible version.

So add to your composer.json:

> "require": {
>   "monolog/monolog": ">=1.3.0",
> }

## Exception logging

Include XiErrorBundle in your AppKernel.php:

```php
<?php
...
    public function registerBundles()
    {
        $bundles = array(
        ...
            new Ornicar\ApcBundle\OrnicarApcBundle(),
        ...
        );
    }
...
```

Default service creates exception log into %kernel.logs_dir%/exception.%kernel.environment%.log.

### Why use separate exception logging?

Default Symfony2 logs have lots of other stuff, that does not help much with debugging and log surveying process. This logger also includes stack traces for exceptions.

## Exception message formatting

During development, you want to see exactly what went wrong. In production on the other hand, you don't want to show the actual, possibly very detailed exception message. Exception message formatter component/service takes an exception, and returns the detailed or the general error message depending on current environment.

This component is especially useful, when you need to show the user the result of some Ajax call. You can format the message in server, and see appropriate message depending on the environment.

In your controller / service:


    <?php

    ...

    try {
        throw new Exception(sprintf('we died because database said "%s"', 'could not connect'));
    } catch (Exception $e) {

        # you can either use the service...
        $service = $this->get('xi_error.exception_formatter');
        $message = $service->formatMessage($e, 'could not process your form, please try again');

        # ...or use the component directly
        #$message = \Xi\Bundle\ErrorBundle\Component\ExceptionFormatter::formatMessage(
        #    $e,
        #    'could not process your form, please try again',
        #    $this->get('kernel')->getEnvironment()
        #);

        return $message;
    }

By default, the user sees the original exception message when in "testing" or "development" environment, and some more general error message in "production".