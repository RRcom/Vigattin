<?php
use Vigattin\Communicate\Communicate;
include __DIR__.'/SampleCommMessage.php';

class ConfigTestCase extends PHPUnit_Framework_TestCase {

    public $communication;

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->communication = new Communicate();
    }

    public function testGetConfig() {
        $config = $this->communication->getConfig();
        $this->assertArrayHasKey('remoteUrl', $config);
        $this->assertArrayHasKey('password', $config);
        $this->assertArrayHasKey('salt', $config);
        $this->assertArrayHasKey('checkCertificate', $config);
        $this->assertArrayHasKey('expiryLife', $config);
    }

    public function testSendMessage() {
        $result = $this->communication->sendMessage(array('test1' => 'data1', 'test2' => 12345), 'test_message');
        //$this->assertEquals('ok', $result);
        //echo $result;
    }

    public function testRegisterEvents() {
        $this->communication->registerOnCatchListener('SampleCommMessage', 'test');
    }

    public function testTrigger() {
        $package = array(
            'name' => 'test',
            'expire' => 1234,
            'data' => array(12345),
        );
        $this->communication->triggerEvent($package);
    }
}
