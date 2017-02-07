<?php
namespace Sede2\Messenger;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $dates = ['read'];

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->morphTo();
    }

    public function read()
    {
        $this->read = Carbon::now();
        $this->save();
        return $this;
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read');
    }

    public static function write($body)
    {
        $message = new static();
        $message->body = $body;
        return $message;
    }

    public function from(Model $sender)
    {
        $this->sender_id = $sender->id;
        $this->sender_type = (new \ReflectionClass($sender))->getShortName();
        return $this;
    }

    public function to(Model $receiver)
    {
        $this->receiver_id = $receiver->id;
        $this->receiver_type = (new \ReflectionClass($receiver))->getShortName();
        return $this;
    }

    public function fire()
    {
        $this->save();
        return $this;
    }

    public static function send(Model $sender, Model $receiver, $body)
    {
        return static::write($body)->from($sender)->to($receiver)->fire();
    }
}