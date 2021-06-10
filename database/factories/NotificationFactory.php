<?php

namespace Oza75\LaravelSesComplaints\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Oza75\LaravelSesComplaints\Models\Notification;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "topic_arn" => $this->faker->sentence,
            "source_email" => $this->faker->email,
            "destination_email" => $this->faker->email,
            "subject" => $this->faker->words(5),
            "message_id" => Hash::make("hello"),
            "ses_message_id" => Hash::make('hello world'),
            "type" => $this->faker->randomElement(["bounce", 'complaint']),
            "sent_at" => $this->faker->dateTime(),
        ];
    }
}
