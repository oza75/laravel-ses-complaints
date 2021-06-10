<?php

namespace Oza75\LaravelSesComplaints;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Oza75\LaravelSesComplaints\Contracts\CheckMiddleware;
use Oza75\LaravelSesComplaints\Models\Notification;
use Oza75\LaravelSesComplaints\Models\Subscription;

/**
 * @see \Oza75\LaravelSesComplaints\LaravelSesComplaints
 * @method static Model|Subscription subscriptionModel();
 * @method static Model|Notification notificationModel();
 * @method static Model|Subscription confirmSubscriptionRequest(array $attributes);
 * @method static mixed|null config(string $key, $default = null);
 * @method static bool enabled();
 */
class LaravelSesComplaintsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-ses-complaints';
    }
}
