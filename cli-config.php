<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Vigattin\Connect\DoctrineConnect;

$entityManager = DoctrineConnect::createEntityManager(TRUE);
return ConsoleRunner::createHelperSet($entityManager);