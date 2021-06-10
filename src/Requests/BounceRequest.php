<?php


namespace Oza75\LaravelSesComplaints\Requests;

use Carbon\Carbon;

class BounceRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function notificationData(): array
    {
        $content = json_decode($this->getContent(), true, JSON_UNESCAPED_UNICODE);
        $message = json_decode($content["Message"], true, JSON_UNESCAPED_UNICODE);

        return [
            "topic_arn" => $content["TopicArn"],
            "type" => "bounce",
            "ses_message_id" => $message["mail"]["messageId"],
            "message_id" => $message["mail"]["commonHeaders"]["messageId"] ?? null,
            "sent_at" => Carbon::parse($message["mail"]["timestamp"]),
            "subject" => $message["mail"]["commonHeaders"]["subject"] ?? "Sans Object",
            "source_email" => $message["mail"]["source"]
        ];
    }

    /**
     * @return array
     */
    public function bouncedRecipients(): array
    {
        $content = json_decode($this->getContent(), true, JSON_UNESCAPED_UNICODE);
        $message = json_decode($content["Message"], true, JSON_UNESCAPED_UNICODE);
        $bounce = $message["bounce"];

        $additional = [
            "bounceType" => $bounce["bounceType"] ?? null,
            "bounceSubType" => $bounce["bounceSubType"] ?? null,
            "remoteMtaIp" => $bounce["remoteMtaIp"] ?? null,
            "reportingMTA" => $bounce["reportingMTA"] ?? null,
            "sent_at" => Carbon::parse($bounce["timestamp"]),
        ];

        $destinations = [];

        $recipients = $bounce["bouncedRecipients"] ?? [];

        foreach ($recipients as $recipient) {
            $destinations[] = [
                "email" => $recipient["emailAddress"],
                "action" => $recipient["action"],
                "options" => array_merge($additional, [
                    "status" => $recipient["status"] ?? null,
                    "diagnostic" => $recipient["diagnosticCode"] ?? null,
                ])
            ];
        }

        return $destinations;
    }
}
