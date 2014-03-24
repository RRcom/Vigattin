<?php
namespace Vigattin\Communicate;

use Vigattin\Cription\Cription;
use Vigattin\Config\Config;
use Vigattin\Communicate\MessageInterface;

/**
 * Class Communicate
 * @package Vigattin\Communicate
 */
class Communicate {

    const CURL_CLIENT_NAME = 'vigattin_communicate';

    public $config;
    protected $callableClassArray = array();

    public function __construct($config = NULL) {
        $this->config = $this->getDefaultConfig();
        if(is_array($config)) $this->config = array_merge($this->config, $config);
        else $this->config = array_merge($this->config, Config::getConfigFromFile('communication'));
    }

    /**
     * Send message containing data to the receiver
     * @param mixed $data the variable to send to the receiver recommended type array
     * @param string $msgName if message name is update_user trigger will search for the class with the name update_trigger and run it
     * @param string $remoteUrl the url where the the message listener are
     * @return int|mixed if success return the sent package data back, else false
     */
    public function sendMessage($data, $msgName, $remoteUrl = '') {
        if($remoteUrl === '') $remoteUrl = $this->config['remoteUrl'];
        $package = array(
            'data' => $data,
            'expire' => time()+$this->config['expiryLife'],
            'name' => $msgName,
        );
        $serialized = serialize($package);
        $encrypted = Cription::encript($serialized, $this->config['password'], $this->config['salt']);
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL             => $remoteUrl,
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

    /**
     * @return bool|mixed|string catch post message and return the received data if success, else false
     */
    public function catchMessage() {
        if(empty($_POST['package'])) return FALSE;
        $package = base64_decode($_POST['package'], TRUE);
        $package = Cription::decript($package, $this->config['password'], $this->config['salt']);
        if(!$package) return FALSE;
        $package = unserialize($package);
        if(!is_array($package)) return FALSE;
        $package = $this->triggerEvent($package);
        return $package;
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Add message listener class to the event when message was received
     * @param string $callableClass the class namespace (as string) to call when event happen
     * @param string $messageName
     * @param mixed $dependencies inject dependencies eg. serviceManager, best way is to store multiple dependencies in array
     * @return bool
     */
    public function registerOnCatchListener($callableClass, $messageName, $dependencies = null) {
        if(!is_string($callableClass)) return FALSE;
        $this->callableClassArray[] = array('class' => $callableClass, 'name' => $messageName, 'dependencies' => $dependencies);
    }

    /**
     * Trigger the associate listener class based on message name
     * @param array $package
     */
    public function triggerEvent($package) {
        $package['response'] = array();
        foreach($this->callableClassArray as $callable) {
            if($callable['name'] == $package['name']) {
                if(class_exists($callable['class'])) {
                    $implements = class_implements($callable['class']);
                    if(isset($implements['Vigattin\Communicate\MessageInterface'])) {
                        $class = $callable['class'];
                        $message = new $class();
                        $status = $this->checkPackage($package);
                        $message->injectDependencies($callable['dependencies']);
                        $message->setStatus($status);
                        $message->setReason('');
                        if($status == 'ok') $message->setMessage($package['data']);
                        else $message->setMessage(array());
                        $response = $message->onReceived();
                        if(is_array($response)) {
                            $package['response'] = array_merge($package['response'], $response);
                        }
                    }
                }
            }
        }
        return $package;
    }

    /**
     * check if package is valid
     * @param array $package
     * @return string
     */
    public function checkPackage($package) {
        $status = 'ok';
        if(empty($package['expire'])) $status = 'no expiration found';
        elseif(intval($package['expire']) < time()) $status = 'expired message';
        return $status;
    }

}