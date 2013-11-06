<?php
namespace Vigattin\Connect;

use Vigattin\Config\Config;

class Connect {
    
    const REQUEST_MODE_UNDEFINED = 0;
    const REQUEST_MODE_PUBLIC_PHOTOS = 1;
    const REQUEST_MODE_FB_ALBUM_LIST = 2;
    const REQUEST_MODE_UPLOAD_PHOTO = 3;
    const REQUEST_MODE_DELETE_PHOTO = 4;
    const REQUEST_MODE_DB_READ_ALL_MEMBERS = 5;
    const REQUEST_MODE_DB_CREATE_MEMBERS = 6;
    const REQUEST_MODE_DB_UPDATE_MEMBERS = 7;
    const REQUEST_MODE_COUPON_CHECK = 8;
    const REQUEST_MODE_COUPON_REDEEM = 9;
    
    public $config;
    /* config var
    [connect] => Array
    (
        [apiUrl] 
        [sslCheckCertificate]
        [authDomainLoginUrl]
        [authDomainSecureLoginUrl]
        [authDomainLogoutUrl]
        [authDomainSecureLogoutUrl]
        [requestExpire]
        [apiKey]
    )
     */
    
    public function __construct(\Vigattin\Config\Config $config = NULL) {
        if(is_object($config)) $this->config = $config;
        else $this->config = new Config();
    }
    
    public function apiCall($request = array()) {
        $request['vapi_request_expire'] = time()+$this->config->config['connect']['requestExpire'];
        $request = base64_encode(serialize($request));
        $hash = sha1($request.$this->config->config['connect']['apiKey']);
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL             => $this->config->config['connect']['apiUrl'],
            CURLOPT_POST            => true,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POSTFIELDS      => array(
                'request'           => $request, 
                'hash'              => $hash
            ),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => "vauth client",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYPEER => $this->config->config['connect']['sslCheckCertificate']
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
}