<?php


namespace Oza75\LaravelSesComplaints\Contracts;


use Closure;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Swift_Message;

interface CheckMiddleware
{
    /**
     * @param Swift_Message $message
     * @param Closure $next
     * @param array $options
     * @return mixed|bool
     */
    public function handle(Swift_Message $message, Closure $next, array $options = []);
}
