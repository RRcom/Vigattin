<?php
use Vigattin\Config\Config;

class ConfigTestCase extends PHPUnit_Framework_TestCase {

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
    }
    
    public function testGetConfigFromFileUsingKey() {
        $config = Config::getConfigFromFile('doctrine');
        $this->assertArrayHasKey('connection', $config);
        $this->assertArrayHasKey('isDevmode', $config);
    }

    public function testGetConfigFromFileUsingNoKey() {
        $config = Config::getConfigFromFile();
        $this->assertArrayHasKey('doctrine', $config);
        $this->assertArrayHasKey('session', $config);
    }

    public function testGetConfigFromInstanceWithKey() {
        $config = new Config();
        $this->assertArrayHasKey('connection', $config->getConfig('doctrine'));
        $this->assertArrayHasKey('isDevmode', $config->getConfig('doctrine'));
    }

    public function testGetConfigFromInstanceWithNoKey() {
        $config = new Config();
        $this->assertArrayHasKey('doctrine', $config->getConfig());
        $this->assertArrayHasKey('session', $config->getConfig());
    }
}
