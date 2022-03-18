<?php


namespace Oza75\LaravelSesComplaints\Contracts;


use Closure;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Symfony\Component\Mime\Email;

interface CheckMiddleware
{
    /**
     * @param Email   $message
     * @param Closure $next
     * @param array   $options
     *
     * @return mixed|bool
     */
    public function handle(Email $message, Closure $next, array $options = []);
}
