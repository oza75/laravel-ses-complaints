<?php


namespace Oza75\LaravelSesComplaints\Requests;


use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->isValidAwsMessage();
    }

    public function rules()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isValidAwsMessage(): bool
    {
        try {
            $message = Message::fromRawPostData();

            $validator = new MessageValidator();

            return $validator->isValid($message);
        } catch (\Exception $exception) {
            report($exception);
            return false;
        }
    }
}
