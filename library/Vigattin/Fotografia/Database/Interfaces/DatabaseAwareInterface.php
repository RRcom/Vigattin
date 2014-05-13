<?php
namespace Vigattin\Fotografia\Database\Interfaces;

interface DatabaseAwareInterface
{
    public function setDatabase(DatabaseInterface $database);
}