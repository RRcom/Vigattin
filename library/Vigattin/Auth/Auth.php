<?php
namespace Vigattin\Auth;

use Vigattin\Session\Session;
use Vigattin\Config\Config;
use Vigattin\URLtools\URLtools;

class Auth {
    
    const SESSION_NAME = 'vigattin_user_info';
    
    public $session;
    public $config;
    public $info;
    
    public function __construct() {
        $this->session = new Session();
        $this->config = new Config();
        $this->info = $this->session->getSessiondata(self::SESSION_NAME);
    }
    
    public function getLoginUrl($redirect = '', $secure = FALSE) {
        if($redirect === '') $redirect = URLtools::getCurrentUrl();
        if($secure) return $this->config->config['connect']['authDomainSecureLoginUrl'].'?redirect='.urlencode(urldecode($redirect));
        else return $this->config->config['connect']['authDomainLoginUrl'].'?redirect='.urlencode(urldecode($redirect));
    }
    
    public function getlogoutUrl($redirect = '', $secure = FALSE) {
        if($redirect === '') $redirect = URLtools::getCurrentUrl();
        if($secure) return $this->config->config['connect']['authDomainSecureLogoutUrl'].'?redirect='.urlencode(urldecode($redirect));
        else return $this->config->config['connect']['authDomainLogoutUrl'].'?redirect='.urlencode(urldecode($redirect));
    }
    
    public function catchServerRequest() {
        if((isset($_GET['login'])) && ($_GET['login'] ==  'user')) {
            $info = $this->parseInfo();
            if(count($info)) {
                if(isset($info['vauth_expire']) && (intval($info['vauth_expire']) > time())) {
                    $this->session->setSessionData(self::SESSION_NAME, $info);
                }
                else {
                    return "auth expired";
                }
            }
            else {
                return "auth failed";
            }
            return $info;
        }
        if((isset($_GET['logout'])) && ($_GET['logout'] ==  'user')) {
            $this->session->setSessionData(self::SESSION_NAME, '');
        }
        return FALSE;
    }
    
    public function getInfo($key = '') {
        if($key === '') return $this->info;
        return empty($this->info[$key]) ? '' : $this->info[$key];
    }
    
    public function parseInfo() {
        $info = array();
        if(isset($_GET['info'])) {
            $hash = isset($_GET['hash']) ? $_GET['hash'] : '';
            if($hash == sha1($_GET['info'].$this->config->config['connect']['apiKey'])) {
                $info = unserialize(urldecode(base64_decode($_GET['info'])));
            }
        }
        return $info;
    }
    
}

