# xi-error-bundle

This is a Symfony2 bundle for error and exception handling and logging. It also includes component/service for formatting user readable exception messages according to environment.

[![Build Status](https://travis-ci.org/xi-project/xi-error-bundle.png)](https://travis-ci.org/xi-project/xi-error-bundle)

## Installation

This bundle requires:
* PHP >=5.3
* Symfony >=2.1
* Monolog >= 1.3

Monolog 1.3 or greater and compatible Symfony Monolog bridge is required, since that's the first PSR-3 compatible version.

Add to composer.json:

    "xi/error-bundle": "1.*"
    
See packagist for further versions: https://packagist.org/packages/xi/error-bundle

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

### Does this handle errors->exception conversion?

Since Symfony 2.2, errors are automatically converted to exceptions.

## Exception message formatting

During development, every bit of information helps with debugging the code as soon as errors happen. In production it is desirable to hide the actual, possibly very detailed exception message to the public, and show something generic instead. 

Exception message formatter component/service takes an exception, and returns the original exception message or a general error message *depending on the current environment*. By default, the original exception message is intended to be shown to developer in **"test"** or **"dev"** environment, and end-user sees some more general error message in other environments.

```php
<?php
    $message = 'Everything went better than expected.';

    try {
        # try something dangerous
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
    ...
```