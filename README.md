# PHP recursive replace

Recursively replace array with placeholders in PHP.

## Goal

Instead of repeating values throughout a JSON file,
use placeholders to fill in repeatable portions of values.

Example:

```json
"paths": {
    "app_path": "{{home}}/{{app}}",
    "app_version_path": "{{app_path}}/{{app_version}}",
    "webroot_path": "{{app_version_path}}/www",
    "config_path": "{{app_version_path}}/app/config"
}
```

Instead of:

```json
"paths": {
    "app": "php_example_app",
    "app_version": "1.3.0",
    "app_path": "/home/vagrant/php_example_app",
    "app_version_path": "/home/vagrant/php_example_app/1.3.0"
}
```

## Prerequisites

[PHPUnit](https://phpunit.de/getting-started/phpunit-8.html)

### One local file, quick!

```bash
wget -O phpunit https://phar.phpunit.de/phpunit-8.phar && chmod +x phpunit
```

### With global Composer

- Composer: [Download](https://getcomposer.org/download) & [install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
- PHPUnit: `composer global require phpunit/phpunit`

## Installation

```bash
git clone https://github.com/stemar/php-recursive-replace.git
cd php-recursive-replace
./phpunit ReplaceRecursiveTest.php
```

## Usage

Copy the `replace()` function from `replace_recursive.php` anywhere in your code.

## Examples

Try:

```php
$array = [
    "home" => "/home/vagrant",
    "app" => "php_example_app",
    "app_version" => "1.3.0",
    "app_path" => "{{home}}/{{app}}",
    "app_version_path" => "{{app_path}}/{{app_version}}"
];
$replaced_array = replace($array, $array);
echo replace("App version path: {{app_version_path}}", $replaced_array);
```

Result:

```php
"App version path: /home/vagrant/php_example_app/1.3.0"
```
