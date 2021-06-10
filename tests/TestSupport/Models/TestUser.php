<?php


namespace Oza75\LaravelSesComplaints\Tests\TestSupport\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Oza75\LaravelSesComplaints\Database\Factories\TestUserFactory;

class TestUser extends Model
{
    use Notifiable, HasFactory;

    protected $table = 'test_users';

    protected static function newFactory()
    {
        return new TestUserFactory();
    }
}
