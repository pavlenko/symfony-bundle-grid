# OAuth server symfony bundle
This bundle integrates with pe/component-grid.

## Installation
Install the library via [Composer](https://getcomposer.org/) by
running the following command:
```bash
composer require pe/symfony-bundle-grid
```
Then enable the bundle in your kernel:
```php
<?php
// app/AppKernel.php
class AppKernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new PE\Bundle\GridBundle\PEGridBundle(),
            // ...
        ];
    }
}
```
or for Symfony 4.0
```php
<?php
// SF 4.0 config/bundles.php

return [
    PE\Bundle\GridBundle\PEGridBundle::class => ['all' => true],
];
```
