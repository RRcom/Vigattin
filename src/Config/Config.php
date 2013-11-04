<?php
namespace Vigattin\Config;

class Config {
    
    const DEFAULT_CONFIG_FILE = 'config/config.php';

    public $config;
    
    public function __construct($config = '') {
        //$this->config = array();
        //if($config === '') $config = include __DIR__.'/../'.self::DEFAULT_CONFIG_FILE;
        //if(is_array($config)) $this->mergeConfigArray($config);
        //if(is_file($config)) $this->mergeConfigFile ($config);
    }
    
    public function mergeConfigArray($configArray) {
        if(!is_array($configArray)) array_merge ($this->config, $configArray);
        return $this->config;
    }
    
    public function mergeConfigFile($configFile) {
        if(is_file($configFile)) {
            $config = include $configFile;
            $this->mergeConfigArray($config);
        }
        return $config;
    }
    
    public function getConfig() {
        return $this->config;
    }
    
}

