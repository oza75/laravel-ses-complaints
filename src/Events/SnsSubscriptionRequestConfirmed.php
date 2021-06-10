<?php


namespace Oza75\LaravelSesComplaints\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SnsSubscriptionRequestConfirmed implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Model
     */
    public $subscription;

    /**
     * BounceNotificationCreated constructor.
     * @param Model $subscription
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }
}
