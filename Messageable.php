<?php

namespace Sede2\Messenger;

use Illuminate\Database\Eloquent\Model;

trait Messageable
{
    public function messagesSent()
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function messagesReceived()
    {
        return $this->morphMany(Message::class, 'receiver');
    }

    public function sendMessage(Model $receiver, $body)
    {
        return Message::send($this, $receiver, $body);
    }
}