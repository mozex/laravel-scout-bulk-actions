# Laravel Scout Bulk Actions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mozex/laravel-scout-bulk-actions.svg?style=flat-square)](https://packagist.org/packages/mozex/laravel-scout-bulk-actions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mozex/laravel-scout-bulk-actions/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mozex/laravel-scout-bulk-actions/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/mozex/laravel-scout-bulk-actions/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/mozex/laravel-scout-bulk-actions/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mozex/laravel-scout-bulk-actions.svg?style=flat-square)](https://packagist.org/packages/mozex/laravel-scout-bulk-actions)

Laravel Scout Bulk Actions is a comprehensive Laravel package designed to simplify and streamline your work when dealing with many models. If you've ever found it difficult to import or flush all records of each model into the index one by one, then this package is a game changer for you.

Our package alleviates the limitations of Laravel Scout's native commands `scout:import <model-name>` and `scout:flush <model-name>` by allowing you to perform these actions across all models simultaneously. This functionality can save considerable time and effort, especially when developing and testing projects with many models.

### Features

- `scout:import-all`: Imports all records from all models into the index at once, removing the need to run individual
commands for each model.
- `scout:flush-all`: Allows you to clear all records from the index for all models in one fell swoop instead of having to execute commands for each model separately.
- `scout:refresh`: This command sequentially performs a flush and an import operation. The beauty of this command is in its flexibility. Pass a model name to this command, and it will only refresh that specific model. Leave it blank, and it refreshes every model.

### Why Laravel Scout Bulk Actions?

When dealing with many models, it can quickly become cumbersome to import and flush records for each model individually. This is especially true in the development phase, where such operations must be performed
multiple times. By facilitating bulk actions on all models, our package significantly reduces the time and effort
required for these operations.

For large-scale applications with several models, Laravel Scout Bulk Actions is an indispensable tool that dramatically enhances your productivity and efficiency. Enjoy less time typing commands and more time crafting your Laravel masterpiece. Your testing phase will thank you for it!

In summary, Laravel Scout Bulk Actions is designed to improve and simplify your Laravel Scout experience. It's a small package with significant benefits that have the potential to make a massive difference in your Laravel project. So why wait? Give Laravel Scout Bulk Actions a try today!

## Support us

Creating and maintaining open-source projects requires significant time and effort. Your support will help enhance the project and enable further contributions to the Laravel community.

Sponsorship can be made through the [GitHub Sponsors](https://github.com/sponsors/mozex) program. Just click the "**[Sponsor](https://github.com/sponsors/mozex)**" button at the top of this repository. Any amount is greatly appreciated and will go directly towards developing and improving this package.
Thank you for considering sponsoring. Your support truly makes a difference!

## Installation

You can install the package via composer:

```bash
composer require mozex/laravel-scout-bulk-actions
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-scout-bulk-actions-config"
```

This is the contents of the published config file:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Searchable Model Directories
    |--------------------------------------------------------------------------
    |
    | Define the directories for Laravel Scout to scan for models that use the
    | Searchable trait. This configuration accepts an array of directory paths
    | where your models reside. Glob patterns are supported for these paths,
    | allowing you to include multiple directories. Laravel Scout will
    | automatically import or flush these models.
    |
    */

    'model_directories' => [
        app_path('Models'),
        // base_path('Modules/*/Models'),
    ],
];
```

## Usage

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

- [Mohammad Zahed](https://github.com/mozex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
