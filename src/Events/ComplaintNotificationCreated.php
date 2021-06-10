<?php


namespace Oza75\LaravelSesComplaints\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintNotificationCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Model
     */
    public $notification;

    /**
     * ComplaintNotificationCreated constructor.
     * @param Model $notification
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }
}
