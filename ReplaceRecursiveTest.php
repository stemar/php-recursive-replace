<?php
require __DIR__.'/replace_recursive.php';
use PHPUnit\Framework\TestCase;

/**
 * Usage: ./phpunit ReplaceRecursiveTest
 */
class ReplaceRecursiveTest extends TestCase {

    public function test_replace() {
        $array = [
            "home" => "/home/vagrant",
            "app" => "php_example_app",
            "app_version" => "1.3.0",
            "app_path" => "{{home}}/{{app}}",
            "app_version_path" => "{{app_path}}/{{app_version}}"
        ];
        $expected = [
            "home" => "/home/vagrant",
            "app" => "php_example_app",
            "app_version" => "1.3.0",
            "app_path" => "/home/vagrant/php_example_app",
            "app_version_path" => "/home/vagrant/php_example_app/1.3.0"
        ];
        $replaced_array = replace($array, $array);
        $this->assertEquals($expected, $replaced_array);

        $actual = replace("App version path: {{app_version_path}}", $replaced_array);
        $this->assertEquals("App version path: /home/vagrant/php_example_app/1.3.0", $actual);
    }

    public function test_replace_json_values() {
        $json = $this->parse_json("example.json");
        $array = [];
        $array += $json['servers']['production'];
        $array += $json['paths'];
        $array += $json['application'];
        $array += ["home" => "/home/vagrant"];
        $replaced_array = replace($array, $array);

        $expected = [
            "home" => "/var/www",
            "php_version" => "7.2.9",
            "url" => "https://example.com",
            "deploy_path" => "/var/www/php_example_app/1.3.0/current",
            "webroot_path" => "/var/www/php_example_app/1.3.0/current/www",
            "config_path" => "/var/www/php_example_app/1.3.0/current/app/config",
            "app_path" => "/var/www/php_example_app",
            "app_version_path" => "/var/www/php_example_app/1.3.0",
            "app" => "php_example_app",
            "app_version" => "1.3.0"
        ];
        $this->assertEquals($expected, $replaced_array);

        $actual = replace("Production webroot path: {{webroot_path}}", $replaced_array);
        $this->assertEquals("Production webroot path: /var/www/php_example_app/1.3.0/current/www", $actual);

        $actual = replace("Production php version: {{php_version}}", $replaced_array);
        $this->assertEquals("Production php version: 7.2.9", $actual);
    }

    public function test_replace_json_values_with_symbol_keys() {
        $json = <<<JSON
{
    "home": "/home/vagrant",
    "app": "php_example_app",
    "app_version": "1.3.0",
    "app_path": ":home/:app",
    "app_version_path": ":app_path/:app_version"
}
JSON;
        $array = json_decode($json, TRUE);
        $expected = [
            "home" => "/home/vagrant",
            "app" => "php_example_app",
            "app_version" => "1.3.0",
            "app_path" => "/home/vagrant/php_example_app",
            "app_version_path" => "/home/vagrant/php_example_app/1.3.0"
        ];
        $replaced_array = replace($array, $array, "/:(\w+)/");
        $this->assertEquals($expected, $replaced_array);

        $actual = replace("App version path: {{app_version_path}}", $replaced_array);
        $this->assertEquals("App version path: /home/vagrant/php_example_app/1.3.0", $actual);
    }

    /**
     * Parse JSON file
     *
     * @param string $filename
     * @return array
     */
    private function parse_json($filename) {
        if (!file_exists($filename)) {
            trigger_error(sprintf("<!> `%s` is missing!\n", $filename));
            return;
        }
        $array = json_decode(file_get_contents($filename), TRUE);
        if (json_last_error()) {
            trigger_error(sprintf("<!> json_decode() error: %s", json_last_error_msg()));
            return;
        }
        return $array;
    }
}