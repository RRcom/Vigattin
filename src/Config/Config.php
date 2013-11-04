<?php
namespace Vigattin\Config;

class Config {
    
    public $config;
    
    public function __construct($config = '') {
        $this->config = array();
        if(!is_array($config)) $this->mergeConfigArray($config);
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

