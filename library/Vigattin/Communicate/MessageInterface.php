<?php
namespace Vigattin\Communicate;

interface MessageInterface {

    const STATUS_SUCCESS = 0;
    const STATUS_EXPIRED = 1;
    const STATUS_HASH_INVALID = 2;

    /**
     * @param $message mixed The actual message from remote server.
     */
    public function setMessage($message);

    /**
     * @param $status int Status code of the received message.
     */
    public function setStatus($status);

    /**
     * @param $reason string Description if message has error.
     */
    public function setReason($reason);

    /**
     * Trigger when message receiving is complete
     */
    public function onRecieved();
}