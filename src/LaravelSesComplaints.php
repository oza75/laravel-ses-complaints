<?php

namespace Oza75\LaravelSesComplaints;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Oza75\LaravelSesComplaints\Contracts\CheckMiddleware;
use Oza75\LaravelSesComplaints\Events\BounceNotificationCreated;
use Oza75\LaravelSesComplaints\Events\ComplaintNotificationCreated;
use Oza75\LaravelSesComplaints\Events\SnsSubscriptionRequestConfirmed;
use Oza75\LaravelSesComplaints\Exceptions\CannotConfirmSubscriptionException;
use Oza75\LaravelSesComplaints\Models\Notification;
use Oza75\LaravelSesComplaints\Models\Subscription;
use Oza75\LaravelSesComplaints\Contracts\LaravelSesComplaints as Contract;

class LaravelSesComplaints implements Contract
{
    /**
     * @param string $key
     * @param mixed|null $default
     * @return Repository|Application|mixed
     */
    public function config(string $key, $default = null)
    {
        return config('laravel-ses-complaints.' . $key, $default);
    }

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return $this->config('enabled', false);
    }

    /**
     * @return Model|Subscription
     */
    public function subscriptionModel()
    {
        $class = $this->config('models.subscription', Subscription::class);

        return new $class;
    }

    /**
     * @return Model|Notification
     */
    public function notificationModel()
    {
        $class = $this->config('models.notification', Notification::class);

        return new $class;
    }

    /**
     * @param array $attributes
     * @return Builder|Model|Subscription
     * @throws CannotConfirmSubscriptionException
     */
    public function confirmSubscriptionRequest(array $attributes)
    {
        $model = $this->subscriptionModel();

        $subscription = $model->query()->create($attributes);

        $response = Http::get($subscription->getAttribute('subscribe_url'));

        if ($response->failed()) {
            $subscription->delete();

            throw new CannotConfirmSubscriptionException("Cannot confirm subscription request");
        }

        event(new SnsSubscriptionRequestConfirmed($subscription));

        return $subscription;
    }

    /**
     * @param array $attributes
     * @param array|Collection $recipients
     * @return Collection
     */
    public function createBounceNotification(array $attributes, $recipients): Collection
    {
        $model = $this->notificationModel();
        $models = collect();

        foreach ($recipients as $recipient) {
            $data = array_merge($attributes, [
                'destination_email' => $recipient['email'],
                'options' => $recipient['options']
            ]);

            $notification = $model->newQuery()->create($data);
            $models->push($notification);

            event(new BounceNotificationCreated($notification));
        }

        return $models;
    }

    /**
     * @param array $attributes
     * @param array|Collection $recipients
     * @return Collection
     */
    public function createComplaintNotification(array $attributes, $recipients): Collection
    {
        $model = $this->notificationModel();
        $models = collect();

        foreach ($recipients as $recipient) {
            $data = array_merge($attributes, [
                'destination_email' => $recipient['email'],
                'options' => $recipient['options']
            ]);

            $notification = $model->newQuery()->create($data);
            $models->push($notification);

            event(new ComplaintNotificationCreated($notification));
        }

        return $models;
    }

    /**
     * @return array
     */
    public function checkMiddlewares(): array
    {
        return collect($this->config('middlewares', []))->map(function ($options, $key) {
            return ['middleware' => $key, 'options' => $options];
        })->toArray();
    }
}
