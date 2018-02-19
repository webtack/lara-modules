# Lara-modules
`webtack/lara-modules` is a Laravel package which created to manage your Laravel app using modules.
## Install

To install through Composer, by run the following command:

``` bash
composer require webtack/lara-modules
```

### Autoloading

By default the module classes are not loaded automatically. You can autoload your modules using `psr-4`. For example:

``` json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "modules/"
    }
  }
}
```

#### Add Service Provider

Add this service provider to your `config/app.php` file.

``` php
Webtack\Modules\ModulesServiceProvider::class
```

If the loader still does not see your modules, run the command

```bash
composer dump-autoload
```

### Artisan

#### vendor:publish
Artisan command to Publish any publishable assets from vendor packages (Required to get Laravel Packages working!).

``` bash
php artisan vendor:publish --provider="Webtack\Modules\ModulesServiceProvider" 
```

### Test Module

#### Click here

```html
http://localhost/path/to/project/test
http://localhost/path/to/project/api/test
```
##### to test the module's functionality

## License

The MIT License (MIT). Please see [License File][link-license] for more information.

[link-license]: https://github.com/webtack/lara-modules/blob/master/LICENSE.md