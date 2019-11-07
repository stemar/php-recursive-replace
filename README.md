# PHP recursive replace

Recursively replace array with placeholders in PHP.

## Goal

We need a recursive replacement of values within the same array.

Instead of repeating values throughout a JSON file or a PHP Array,
use placeholders to fill in repeatable portions of values.

Example:

```json
{
    "home": "/home/vagrant",
    "app": "php_example_app",
    "app_version": "1.3.0",
    "app_path": "{{home}}/{{app}}",
    "app_version_path": "{{app_path}}/{{app_version}}",
    "webroot_path": "{{app_version_path}}/www",
    "config_path": "{{app_version_path}}/app/config"
}
```

Instead of:

```json
{
    "home": "/home/vagrant",
    "app": "php_example_app",
    "app_version": "1.3.0",
    "app_path": "/home/vagrant/php_example_app",
    "app_version_path": "/home/vagrant/php_example_app/1.3.0",
    "webroot_path": "/home/vagrant/php_example_app/1.3.0/www",
    "config_path": "/home/vagrant/php_example_app/1.3.0/app/config"
}
```

## Installation

```bash
git clone https://github.com/stemar/php-recursive-replace.git
cd php-recursive-replace
```

## Test

### One local file

```bash
wget -O phpunit https://phar.phpunit.de/phpunit-8.phar
php phpunit ReplaceRecursiveTest.php
```

### With Composer

```bash
curl -sS https://getcomposer.org/installer -o composer | php
php composer require phpunit/phpunit
vendor/bin/phpunit ReplaceRecursiveTest.php
```

## Usage

Copy the `replace()` function from `replace_recursive.php` anywhere in your code.

## Examples

Try:

```php
<?php
$array = [
    "home" => "/home/vagrant",
    "app" => "php_example_app",
    "app_version" => "1.3.0",
    "app_path" => "{{home}}/{{app}}",
    "app_version_path" => "{{app_path}}/{{app_version}}",
    "webroot_path" => "{{app_version_path}}/www",
    "config_path" => "{{app_version_path}}/app/config"
];
$replaced_array = replace($array, $array);
echo replace("App version path: {{app_version_path}}", $replaced_array);
```

Result:

```
App version path: /home/vagrant/php_example_app/1.3.0
```

Try:

```json
$json = <<<JSON
{
    "home": "/home/vagrant",
    "app": "php_example_app",
    "app_version": "1.3.0",
    "app_path": "{{home}}/{{app}}",
    "app_version_path": "{{app_path}}/{{app_version}}",
    "webroot_path": "{{app_version_path}}/www",
    "config_path": "{{app_version_path}}/app/config"
}
JSON;
$array = json_decode($json, TRUE);
$replaced_array = replace($array, $array);
echo replace("Config path: {{config_path}}", $replaced_array);
```

Result:

```
Config path: /home/vagrant/php_example_app/1.3.0/app/config
```

Try with a symbol placeholder:

```json
$json = <<<JSON
{
    "home": "/home/vagrant",
    "app": "php_example_app",
    "app_version": "1.3.0",
    "app_path": ":home/:app",
    "app_version_path": ":app_path/:app_version",
    "webroot_path": ":app_version_path/www",
    "config_path": ":app_version_path/app/config"
}
JSON;
$array = json_decode($json, TRUE);
$replaced_array = replace($array, $array, '/:(\w+)/');
echo replace("Config path: :config_path", $replaced_array, '/:(\w+)/');
```

Result:

```
Config path: /home/vagrant/php_example_app/1.3.0/app/config
```
