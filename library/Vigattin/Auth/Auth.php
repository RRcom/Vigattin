<?php
namespace Vigattin\Auth;

use Vigattin\Session\Session;
use Vigattin\Config\Config;
use Vigattin\URLTools\URLTools;

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
    
    /**
     * Get vigattin login URL
     * @param string $redirect where to redirect after successfull login
     * @param bool $secure wether to use secure connection or not
     * @return string the url with redirect for login
     */
    
    public function getLoginUrl($redirect = '', $secure = FALSE) {
        if($redirect === '') $redirect = URLtools::getCurrentUrl();
        if($secure) return $this->config->config['connect']['authDomainSecureLoginUrl'].'?redirect='.urlencode(urldecode($redirect));
        else return $this->config->config['connect']['authDomainLoginUrl'].'?redirect='.urlencode(urldecode($redirect));
    }
    
    /**
     * Get vigattin FB login URL
     * @param string $redirect where to redirect after successfull FB login
     * @param bool $secure wether to use secure connection or not
     * @return string the url with redirect for FB login
     */
    
    public function getFbLoginUrl($redirect = '', $secure = FALSE) {
        if($redirect === '') $redirect = URLTools::getCurrentUrl();
        if($secure) return $this->config->config['connect']['authDomainSecureFbLoginUrl'].'?redirect='.urlencode(urldecode($redirect));
        else return $this->config->config['connect']['authDomainFbLoginUrl'].'?redirect='.urlencode(urldecode($redirect));
    }
    
    /**
     * Get vigattin logout URL
     * @param string $redirect where to redirect after successfull logout
     * @param bool $secure wether to use secure connection or not
     * @return string the url with redirect for logout
     */
    
    public function getlogoutUrl($redirect = '', $secure = FALSE) {
        if($redirect === '') $redirect = URLTools::getCurrentUrl();
        if($secure) return $this->config->config['connect']['authDomainSecureLogoutUrl'].'?redirect='.urlencode(urldecode($redirect));
        else return $this->config->config['connect']['authDomainLogoutUrl'].'?redirect='.urlencode(urldecode($redirect));
    }
    
    /**
     * Catch login|logout request from vigattin server
     * @return array|string|boolean return array of user info if success or error string if an error exist or false if no request catched
     */
    
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
            $this->clearInfo();
        }
        return FALSE;
    }
    
    /**
     * Get user info from a login user
     * @param string|null $key the name of info to fetch or if null get all info
     * @return mixed single info or array of info
     */
    
    public function getInfo($key = '') {
        if($key === '') return $this->info;
        return empty($this->info[$key]) ? '' : $this->info[$key];
    }
    
    /**
     * Parse the info string package to array from vigattin server
     * @return array array of user info
     */
    
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
    
    /**
     * Clear user data from Vigattin\Session class 
     */
    
    public function clearInfo() {
        $this->session->setSessionData(self::SESSION_NAME, '');
    }
}

