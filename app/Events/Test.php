<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel; 
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class Test implements ShouldBroadcast
{
    use SerializesModels;

    public $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    // Broadcast on a public channel called 'chat'
    public function broadcastOn()
    {
        return new Channel('chat');
    }

    // Data sent to clients
    public function broadcastWith()
    {
        return [
            'text' => $this->text,
        ];
    }
}
