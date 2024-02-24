Remote Request PSR
==============

[![Build Status](https://app.travis-ci.com/alex-kalanis/remote-request-psr.svg?branch=master)](https://app.travis-ci.com/github/alex-kalanis/remote-request-psr)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/remote-request-psr/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/remote-request-psr/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/remote-request-psr/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/remote-request-psr)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/remote-request-psr.svg?v1)](https://packagist.org/packages/alex-kalanis/remote-request-psr)
[![License](https://poser.pugx.org/alex-kalanis/remote-request-psr/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/remote-request-psr)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/remote-request-psr/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/remote-request-psr/?branch=master)

PSR adapter for connecting Remote Request into your application with public interfaces.


## PHP Installation

```
{
    "require": {
        "alex-kalanis/remote-request-psr": "1.1"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


## PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Add some external packages with connection to the local or remote services.

3.) Connect the "\kalanis\RemoteRequestPsr\Processor\Simple" into your app.

5.) Just call request


## Usages

Basic data sending through network. In this case just use php's internal streams.

```php
    $request = \kalanis\RemoteRequestPsr\Content\Request();
    // ... $request is PSR request class WITHOUT immutability. So the most things works.

    $lib = new \kalanis\RemoteRequestPsr\Processor\Simple();
    $response = $lib->process($request);

    // ... $response is PSR response class WITHOUT immutability. So the most things works.
    return strval($response->getBody());
```
