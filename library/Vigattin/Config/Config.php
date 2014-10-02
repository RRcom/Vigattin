<?php
namespace Vigattin\Config;

class Config {
    
	const SAMPLE_CONFIG_FILE = '../../../config/sample.config.php';
    const DEFAULT_CONFIG_FILE = '../../../config/config.php';

    public $config;
    
    public function __construct(Array $config = NULL) {
        $this->config = array();
        if($config === NULL) $config = $this->getConfigFromFile();
        if(is_array($config)) $this->mergeConfigArray($config);
        elseif(is_file($config)) $this->mergeConfigFile($config);
    }
    
    public function mergeConfigArray($configArray) {
        if(is_array($configArray)) $this->config = array_merge($this->config, $configArray);
        return $this->config;
    }
    
    public function mergeConfigFile($configFile) {
        if(is_file($configFile)) {
            $config = include $configFile;
            $this->mergeConfigArray($config);
        }
        return $config;
    }
    
    public function getConfig($key = '') {
        if($key === '') return $this->config;
        if(!empty($this->config[$key])) return $this->config[$key];
        return FALSE;

    }
    
    static function getConfigFromFile($key = '') {
        if(!is_file(__DIR__.'/'.self::DEFAULT_CONFIG_FILE)) return FALSE;
        $config = include __DIR__.'/'.self::DEFAULT_CONFIG_FILE;
        if(!is_array($config)) return FALSE;
        if($key === '') return $config;
        if(!empty($config[$key])) return $config[$key];
        return FALSE;

    }
}

