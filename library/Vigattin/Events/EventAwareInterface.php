<?php
namespace Vigattin\Events;

use Vigattin\Vauth\Vauth;

interface EventAwareInterface
{
    public function onEventTrigger(Vauth $vauth);
}