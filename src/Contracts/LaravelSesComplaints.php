<?php

namespace Oza75\LaravelSesComplaints\Contracts;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Oza75\LaravelSesComplaints\Exceptions\CannotConfirmSubscriptionException;
use Oza75\LaravelSesComplaints\Models\Notification;
use Oza75\LaravelSesComplaints\Models\Subscription;

interface LaravelSesComplaints
{
    /**
     * @param string $key
     * @param mixed|null $default
     * @return Repository|Application|mixed
     */
    public function config(string $key, $default = null);

    /**
     * @return bool
     */
    public function enabled(): bool;

    /**
     * @return array
     */
    public function checkMiddlewares(): array;

    /**
     * @return Model|Subscription
     */
    public function subscriptionModel();

    /**
     * @return Model|Notification
     */
    public function notificationModel();

    /**
     * @param array $attributes
     * @return Builder|Model|Subscription
     * @throws CannotConfirmSubscriptionException
     */
    public function confirmSubscriptionRequest(array $attributes);

    /***
     * @param array $attributes
     * @param array|Collection $recipients
     * @return Collection
     */
    public function createBounceNotification(array $attributes, $recipients): Collection;

    /**
     * @param array $attributes
     * @param Collection $recipients
     * @return Collection
     */
    public function createComplaintNotification(array $attributes, Collection $recipients): Collection;

}
