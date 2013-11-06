<?php

class MemberTestCase extends PHPUnit_Framework_TestCase {
    
    protected $member;
    protected $config;
    protected $connect;


    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $config_array = require __DIR__.'/../config/config.php';
        $this->config = new Vigattin\Config\Config($config_array);
        $this->connect = new Vigattin\Connect\Connect($this->config);
        $this->member = new Vigattin\Member\Member($this->connect);
    }
    
    public function testDBReadAllMembers() {
        $result = $this->member->dbReadAllMembers();
        //$this->assertCount(30, $result['result']);
        var_dump($this->connect->getConfig());
        //$this->assertArrayHasKey('result', $result);
    }
    
}
