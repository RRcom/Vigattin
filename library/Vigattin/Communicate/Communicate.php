<?php
namespace Vigattin\Communicate;

use Vigattin\Cription\Cription;
use Vigattin\Config\Config;

class Communicate {

    const CURL_CLIENT_NAME = 'vigattin_communicate';

    public $config;
    protected $callableClassArray = array();

    public function __construct($config = NULL) {
        $this->config = $this->getDefaultConfig();
        if(is_array($config)) $this->config = array_merge($this->config, $config);
        else $this->config = array_merge($this->config, Config::getConfigFromFile('communication'));
    }

    public function sendMessage($data, $msgName) {
        $package = array(
            'data' => $data,
            'expire' => time()+$this->config['expiryLife'],
            'name' => $msgName,
        );
        $serialized = serialize($package);
        $encrypted = Cription::encript($serialized, $this->config['password'], $this->config['salt']);
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL             => $this->config['remoteUrl'],
            CURLOPT_POST            => true,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POSTFIELDS      => array('package' => base64_encode($encrypted)),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => self::CURL_CLIENT_NAME,
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYPEER => $this->config['useSecureConnection'],
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        $curlError = curl_errno($ch);
        curl_close($ch);
        if(!$result) return $curlError;
        return $result;
    }

    public function catchMessage() {
        if(empty($_POST['package'])) return FALSE;
        $package = base64_decode($_POST['package'], TRUE);
        $package = Cription::decript($package, $this->config['password'], $this->config['salt']);
        if(!$package) return FALSE;
        $package = unserialize($package);
        if(!is_array($package)) return FALSE;
        return $package;
    }

    public function getDefaultConfig() {
        return array(
            'remoteUrl' => '',
            'password' => '',
            'salt' => '',
            'useSecureConnection' => FALSE,
            'checkCertificate' => FALSE,
            'expiryLife' => 180,
        );
    }

    public function getConfig() {
        return $this->config;
    }

    public function registerOnCatchListener($callableClass, $messageName) {
        if(!is_string($callableClass)) return FALSE;
        $this->callableClassArray[] = array('class' => $callableClass, 'name' => $messageName);
    }

}