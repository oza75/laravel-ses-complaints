<?php


namespace Oza75\LaravelSesComplaints\Requests;


use Carbon\Carbon;

class ComplaintRequest extends BaseRequest
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
            "type" => "complaint",
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
    public function complainedRecipients(): array
    {
        $content = json_decode($this->getContent(), true, JSON_UNESCAPED_UNICODE);
        $message = json_decode($content["Message"], true, JSON_UNESCAPED_UNICODE);
        $item = $message["complaint"];

        $additional = [
            "complaintFeedbackType" => $item["complaintFeedbackType"] ?? null,
            "arrivalDate" => $item["arrivalDate"] ?? null,
            "userAgent" => $item["userAgent"] ?? null,
            "received_at" => Carbon::parse($item["timestamp"]),
        ];

        $destinations = [];

        $recipients = $item["complainedRecipients"] ?? [];

        foreach ($recipients as $recipient) {
            $destinations[] = [
                "email" => $recipient["emailAddress"],
                "options" => $additional
            ];
        }

        return $destinations;
    }
}
