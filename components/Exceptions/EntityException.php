<?php

namespace Components\Exceptions;
use Phalcon\Mvc\ModelInterface;
use Exception;

class EntityException extends Handler
{
	public function __construct(ModelInterface $entity, $message = '', $type = 'id', $code = 0, Exception $prev = null)
    {
        $this->entity = $entity;
        $this->message = $message;

        $messages = [];
        foreach ((array) $entity->getMessages() as $entityMessage) {
            $messages[] = (string) $entityMessage;
        }
        array_unshift($messages, $message);
        $message = implode('. ', array_map(function ($value) {
            return rtrim($value, '.');
        }, $messages));

         parent::__construct($message, $code, $prev);
    }

    public function getMessagess(){
    	return $this->message;
    }
}