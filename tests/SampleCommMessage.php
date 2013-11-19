<?php
use Vigattin\Communicate\MessageInterface;

class SampleCommMessage implements MessageInterface {
    /**
     * @param $message mixed The actual message from remote server.
     */
    public function getMessage($message)
    {
        // TODO: Implement getMessage() method.
    }

    /**
     * @param $status int Status code of the received message.
     */
    public function getStatus($status)
    {
        // TODO: Implement getStatus() method.
    }

    /**
     * @param $reason string Description if message has error.
     */
    public function getReason($reason)
    {
        // TODO: Implement getReason() method.
    }

}