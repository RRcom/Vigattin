<?php
namespace Vigattin\Session;

use Vigattin\Config\Config;
use Vigattin\Cription\Cription;

class Session {
    
    public $config;
    public $session;
    
    public function __construct() {
        $this->session = array();
        $this->config = new Config();
        $this->initSession();
    }
    
    public function setSessionData($key, $value = '') {
        if(is_array($key)) {
            $this->session = array_merge($this->session, $key);
        }
        else $this->session[$key] = $value;
        $this->updateCookie();
    }
    
    public function getSessiondata($key) {
        if(!empty($this->session[$key])) return $this->session[$key];
        return '';
    }
    
    public function updateCookie() {
        $value = serialize($this->session);
        $expire = intval($this->config->config['session']['cookieExpire']) ? $this->config->config['session']['cookieExpire'] + time() : 0;
        if((bool)$this->config->config['session']['encriptCookie']) {
            $value = Cription::encript($value, $this->config->config['session']['encriptKey']);
        }
        setcookie(
                $this->config->config['session']['cookieName'], 
                $value, 
                $expire, 
                '/',
                $this->config->config['session']['cookieDomain']
        );
       
        //setcookie($this->config->config['session']['cookieName'], $value, $this->config->config['session']['cookieExpire'], '/');
    }
    
    public function initSession() {
        $cookie = '';
        if(!empty($_COOKIE[$this->config->config['session']['cookieName']])) {
            $cookie = $_COOKIE[$this->config->config['session']['cookieName']];
            if((bool)$this->config->config['session']['encriptCookie']) {
                $cookie = Cription::decript($cookie, $this->config->config['session']['encriptKey']);
            }
            $cookie = unserialize($cookie);
            if(!is_array($cookie)) $cookie = array();
            $this->session = array_merge($this->session, $cookie);
        }
    }
    
}

