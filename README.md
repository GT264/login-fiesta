# Login Fiesta

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gt264/login-fiesta.svg?style=flat-square)](https://packagist.org/packages/gt264/login-fiesta)
[![Tests](https://img.shields.io/github/actions/workflow/status/GT264/login-fiesta/run-tests.yml?branch=main&style=flat-square)](https://github.com/GT264/login-fiesta/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/gt264/login-fiesta.svg?style=flat-square)](https://packagist.org/packages/gt264/login-fiesta)
[![License](https://img.shields.io/packagist/l/gt264/login-fiesta.svg?style=flat-square)](LICENSE.md)

A Laravel package providing authentication and login flows.

## Requirements

- PHP ^8.3
- Laravel ^13.0

## Installation

You can install the package via Composer:

```bash
composer require gt264/login-fiesta
```

## Usage

```php
use LoginFiesta;

LoginFiesta::fiesta(); // "Login Fiesta!"
```

### Publishing the configuration

```bash
php artisan vendor:publish --tag=login-fiesta-config
```

This publishes the `login-fiesta.php` configuration file to your application's `config` directory.

### Artisan command

```bash
php artisan login-fiesta:greet
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [GT264](https://github.com/GT264)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.