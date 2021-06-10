<?php


namespace Oza75\LaravelSesComplaints\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'ses_notifications';

    protected $guarded = [];

    protected $casts = [
        'options' => 'array'
    ];
}
