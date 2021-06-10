<?php

namespace Oza75\LaravelSesComplaints\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Oza75\LaravelSesComplaints\Contracts\LaravelSesComplaints;
use Oza75\LaravelSesComplaints\Exceptions\CannotConfirmSubscriptionException;
use Oza75\LaravelSesComplaints\Requests\BounceRequest;
use Oza75\LaravelSesComplaints\Requests\ComplaintRequest;
use Oza75\LaravelSesComplaints\Requests\RegisterSubscriptionRequest;
use Symfony\Component\HttpFoundation\Response;

class SNSController extends Controller
{
    /**
     * @param RegisterSubscriptionRequest $request
     * @param LaravelSesComplaints $repository
     * @return JsonResponse
     */
    public function registerSubscription(RegisterSubscriptionRequest $request, LaravelSesComplaints $repository): JsonResponse
    {
        try {
            $repository->confirmSubscriptionRequest($request->data());
        } catch (CannotConfirmSubscriptionException $exception) {
            report($exception);
            return response()->json('failed')->setStatusCode(422);
        }

        return \response()->json('ok');
    }

    /**
     * @param BounceRequest $request
     * @param LaravelSesComplaints $repository
     * @return Response
     */
    public function bounces(BounceRequest $request, LaravelSesComplaints $repository): Response
    {
        $content = json_decode($request->getContent(), true);

        if (($content['Type'] ?? null) === 'SubscriptionConfirmation') {
            return app()->call([$this, 'registerSubscription']);
        }

        $attributes = $request->notificationData();
        $recipients = collect($request->bouncedRecipients())->filter(function ($recipient) {
            return $recipient['action'] === 'failed';
        });

        $repository->createBounceNotification($attributes, $recipients);

        return response()->json('ok');
    }

    /**
     * @param ComplaintRequest $request
     * @param LaravelSesComplaints $repository
     * @return JsonResponse
     */
    public function complaints(ComplaintRequest $request, LaravelSesComplaints $repository): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        if (($content['Type'] ?? null) === 'SubscriptionConfirmation') {
            return app()->call([$this, 'registerSubscription']);
        }

        $attributes = $request->notificationData();
        $recipients = collect($request->complainedRecipients());

        $repository->createComplaintNotification($attributes, $recipients);

        return response()->json('ok');
    }
}

