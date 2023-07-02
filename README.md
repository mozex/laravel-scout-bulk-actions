# Laravel Scout Bulk Actions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mozex/laravel-scout-bulk-actions.svg?style=flat-square)](https://packagist.org/packages/mozex/laravel-scout-bulk-actions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mozex/laravel-scout-bulk-actions/tests.yml?branch=main&label=Tests&style=flat-square)](https://github.com/mozex/laravel-scout-bulk-actions/actions/workflows/tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/mozex/laravel-scout-bulk-actions/code-styling.yml?branch=main&label=Code%20Styling&style=flat-square)](https://github.com/mozex/laravel-scout-bulk-actions/actions/workflows/code-styling.yml)
[![GitHub Code Analysis Action Status](https://img.shields.io/github/actions/workflow/status/mozex/laravel-scout-bulk-actions/code-analysis.yml?branch=main&label=Code%20Analysis&style=flat-square)](https://github.com/mozex/laravel-scout-bulk-actions/actions/workflows/code-analysis.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/mozex/laravel-scout-bulk-actions.svg?style=flat-square)](https://packagist.org/packages/mozex/laravel-scout-bulk-actions)

Laravel Scout Bulk Actions is a comprehensive Laravel package designed to simplify and streamline your work when dealing with many models. If you've ever found it difficult to import or flush all records of each model into the index one by one, then this package is a game changer for you.

Our package alleviates the limitations of Laravel Scout's native commands `scout:import <model>` and `scout:flush <model>` by allowing you to perform these actions across all models simultaneously. This functionality can save considerable time and effort, especially when developing and testing projects with many models.

- [Laravel Scout Bulk Actions](#laravel-scout-bulk-actions)
- [Features](#features)
- [Why Laravel Scout Bulk Actions?](#why-laravel-scout-bulk-actions)
- [Support Us](#support-us)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Importing All Models](#importing-all-models)
  - [Flushing All Models](#flushing-all-models)
  - [Refreshing All Models](#refreshing-all-models)
  - [Refreshing Specific Model](#refreshing-specific-model)
- [How Does It Work?](#how-does-it-work)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security Vulnerabilities](#security-vulnerabilities)
- [Credits](#credits)
- [License](#license)

## Features

- `scout:import-all`: Imports all records from all models into the index at once, removing the need to run individual
commands for each model.
- `scout:flush-all`: Allows you to clear all records from the index for all models in one fell swoop instead of having to execute commands for each model separately.
- `scout:refresh`: This command sequentially performs a flush and an import operation. The beauty of this command is in its flexibility. Pass a model name to this command, and it will only refresh that specific model. Leave it blank, and it refreshes every model.

## Why Laravel Scout Bulk Actions?

When dealing with many models, it can quickly become cumbersome to import and flush records for each model individually. This is especially true in the development phase, where such operations must be performed
multiple times. By facilitating bulk actions on all models, our package significantly reduces the time and effort
required for these operations.

For large-scale applications with several models, Laravel Scout Bulk Actions is an indispensable tool that dramatically enhances your productivity and efficiency. Enjoy less time typing commands and more time crafting your Laravel masterpiece. Your testing phase will thank you for it!

In summary, Laravel Scout Bulk Actions is designed to improve and simplify your Laravel Scout experience. It's a small package with significant benefits that have the potential to make a massive difference in your Laravel project. So why wait? Give Laravel Scout Bulk Actions a try today!

## Support Us

Creating and maintaining open-source projects requires significant time and effort. Your support will help enhance the project and enable further contributions to the Laravel community.

Sponsorship can be made through the [GitHub Sponsors](https://github.com/sponsors/mozex) program. Just click the "**[Sponsor](https://github.com/sponsors/mozex)**" button at the top of this repository. Any amount is greatly appreciated and will go directly towards developing and improving this package.
Thank you for considering sponsoring. Your support truly makes a difference!

## Installation

You can install the package via composer:

```bash
composer require mozex/laravel-scout-bulk-actions
```

## Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --tag="scout-bulk-actions-config"
```

After publishing the config file, a configuration file named `scout-bulk-actions.php` will be created in your `config`
directory. You can define which directories to scan for models that are using the `Searchable` trait in this file.

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
    | allowing you to include multiple directories. Laravel Scout Bulk
    | Actions will automatically import or flush these models.
    |
    */

    'model_directories' => [
        app_path('Models'),
        // base_path('Modules/*/Models'),
    ],
];
```

You can add any path to the `model_directories` array. This is where you tell Laravel Scout Bulk Actions where to look for your models. For instance, `app_path('Models')` will target the `app/Models` directory.

The `model_directories` array also accepts [glob](https://www.php.net/manual/en/function.glob.php) patterns. This can be useful if your models are spread across multiple directories. For example, if you have a directory for each module in your application, and each of these module directories has a `Models` subdirectory, you could add a path like `base_path('Modules/*/Models')` to include all these `Models` directories at once.

Remember to clear your config cache using `php artisan config:clear` if you make any changes to the configuration file and
your application is in production mode.

## Usage

Once the package is installed, you'll have access to three new Artisan commands:

- `scout:import-all`
- `scout:flush-all`
- `scout:refresh`

### Importing All Models

To import all models into the Scout index, use the `scout:import-all` command:

```php
php artisan scout:import-all
```

This will loop through all your Scout Searchable models and import them into your Scout index.

### Flushing All Models

To remove all models from the Scout index, use the `scout:flush-all` command:

```php
php artisan scout:flush-all
```

This will loop through all your Scout Searchable models and flush them from your Scout index.

### Refreshing All Models

To refresh (flush and then import) all models data in your Scout index, use the `scout:refresh` command:

```php
php artisan scout:refresh
```

This will flush all models from your Scout index and then import them again.

### Refreshing Specific Model

The `scout:refresh` command can also be used with a specific model name. This will only refresh the index for the given model:

```php
php artisan scout:refresh "App\Models\Post"
```

If no model name is passed to the scout:refresh command, it will refresh all models.

## How Does It Work?

The Laravel Scout Bulk Actions package has been designed to automatically identify and interact with all models in your Laravel application that use Scout's Searchable trait.

Upon execution of a command, the package will first scan the directories specified in the config file for models. This is done to find all models utilizing Laravel Scout's Searchable trait.

Once the models have been identified, the package will then execute Scout's native import or flush commands on each of these models, depending on the command you've chosen to run.

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
