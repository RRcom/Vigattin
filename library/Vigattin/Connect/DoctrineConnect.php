<?php
namespace Vigattin\Connect;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Vigattin\Config\Config;

class DoctrineConnect {
    
    static public function createEntityManager() {
        $entityDir = realpath(__DIR__.'/../../../entity/Vigattin/Entity');
        $vigattinConfig = Config::getConfigFromFile();
        $config = Setup::createAnnotationMetadataConfiguration(array($entityDir), $vigattinConfig['doctrine']['isDevmode']);
        $entityManager = EntityManager::create($vigattinConfig['doctrine']['connection'], $config);
        return $entityManager;
    }
    
}

