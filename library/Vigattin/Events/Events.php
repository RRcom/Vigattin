<?php
namespace Vigattin\Events;

use Vigattin\Events\EventAwareInterface;
use Vigattin\Vauth\Vauth;

class Events
{
    const EVENT_VAUTH_CREATED = 'vc';
    const EVENT_SUCCESS_LOGIN = 'sl';
    const EVENT_FAILED_LOGIN = 'fl';

    /** @var array */
    protected $events;

    public function __construct()
    {
        $this->events = array(
            self::EVENT_VAUTH_CREATED => array(),
            self::EVENT_SUCCESS_LOGIN => array(),
            self::EVENT_FAILED_LOGIN => array(),
        );
    }

    public function register($event, EventAwareInterface $eventAction)
    {
        $this->events[$event][] = $eventAction;
    }

    public function trigger($event, Vauth $vauth)
    {
        foreach($this->events[$event] as $eventAction) {
            $eventAction->onEventTrigger($vauth);
        }
    }
}