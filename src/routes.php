<?php

use Illuminate\Support\Facades\Route;
use Oza75\LaravelSesComplaints\LaravelSesComplaintsFacade as SesFacade;

if (SesFacade::enabled()) {
    Route::any(SesFacade::config('routes.bounces'), [SesFacade::config('controller'), 'bounces'])->name('aws.sns.ses.bounces');
    Route::any(SesFacade::config('routes.complaints'), [SesFacade::config('controller'), 'complaints'])->name('aws.sns.ses.complaints');
}


