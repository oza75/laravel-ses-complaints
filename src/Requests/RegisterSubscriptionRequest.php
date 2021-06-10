<?php


namespace Oza75\LaravelSesComplaints\Requests;


use Illuminate\Foundation\Http\FormRequest;

class RegisterSubscriptionRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'topic_arn' => 'required|string',
            'subscribe_url' => 'required|string',
        ];
    }

    /**
     * @return array
     */
    public function data(): array
    {
        $content = json_decode($this->getContent(), true);

        return [
            'token' => $content["Token"],
            "topic_arn" => $content["TopicArn"],
            "subscribe_url" => $content["SubscribeURL"],
        ];
    }

    /**
     * @return array
     */
    public function validationData(): array
    {
        return $this->data();
    }
}
