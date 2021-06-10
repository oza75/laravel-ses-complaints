<?php


namespace Oza75\LaravelSesComplaints\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Oza75\LaravelSesComplaints\Models\Notification;
use Oza75\LaravelSesComplaints\Tests\TestSupport\Models\TestUser;

class TestUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TestUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "email" => $this->faker->email,
            "name" => $this->faker->name,
        ];
    }
}
