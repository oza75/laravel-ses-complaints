# Laravel SES complaints and bounces manager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oza75/laravel-ses-complaints.svg?style=flat-square)](https://packagist.org/packages/oza75/laravel-ses-complaints)
[![Total Downloads](https://img.shields.io/packagist/dt/oza75/laravel-ses-complaints.svg?style=flat-square)](https://packagist.org/packages/oza75/laravel-ses-complaints)
![GitHub Actions](https://github.com/oza75/laravel-ses-complaints/actions/workflows/main.yml/badge.svg)

This package listens for AWS SNS notifications and stops sending mail to email addresses that have received a permanent rebound notification or users who have marked an email as spam.

## How it works

This package intercepts each mail sent by your laravel application and check if 
the receiver has not marked your mail as SPAM or if the user email address received 
a permanent bounce notification from AWS SNS before. And according to your strategy
defined in the [config file](#config-file), it stops the email sending process or sends the email.

## Installation

You can install the package via composer:

```bash
composer require oza75/laravel-ses-complaints
```
### Publish migrations files and config file
```bash
php artisan vendor:publish --provider="Oza75\LaravelSesComplaints\LaravelSesComplaintsServiceProvider"
```
### Run migration
```bash
php artisan migrate
```
This command will create 2 tables in your database. `sns_subscriptions` table for sns subscription confirmation request 
and `ses_notifications` table to store complaint and bounce notifications received from SNS.

## Usage

### Create SNS topics
Go to your AWS SNS console and create two HTTP/S topic with these endpoints: 
- https://yourapp.tld/aws/sns/ses/bounces to listen to bounce notifications
- https://yourapp.tld/aws/sns/ses/complaints to listen to complaint notifications

These endpoints can be customized in the [config file](#config-file). Note that as soon as you create
these endpoints, they will be automatically confirmed. If not, you can use `php artisan aws:sns:subscribe-url` command to print out 
the `SubscribeURL` required to confirm subscription directly in your aws console. [More details](https://docs.aws.amazon.com/sns/latest/dg/SendMessageToHttp.confirm.html)

### Create SNS topics
Add SNS topics to your SES domain. [More details](https://docs.aws.amazon.com/ses/latest/DeveloperGuide/configure-sns-notifications.html)

## Config file

```php
<?php

return [
    /**
     * If enabled is set to true, this package will intercept each mail then check
     * if the mail passes all middlewares defined in this config file. It will also
     * listen to sns notifications and store them in database. You may set enabled to false
     * to completed disabled this package
     */
    'enabled' => true,
    /*
     * Models used to created a new subscription confirmation request and
     * to store a sns notification received from aws.
     */
    'models' => [
        'subscription' => \Oza75\LaravelSesComplaints\Models\Subscription::class,
        'notification' => \Oza75\LaravelSesComplaints\Models\Notification::class,
    ],

    /**
     * Routes used to handle bounces notification and complaints notifications
     */
    'routes' => [
        'bounces' => '/aws/sns/ses/bounces',
        'complaints' => '/aws/sns/ses/complaints',
    ],

    // Controller used to handle all actions. You can override this if you want to add
    // more specific logic
    'controller' => \Oza75\LaravelSesComplaints\Controllers\SNSController::class,

    /**
     * An array of middleware that the email will go through. If only one return false
     * we do not send the email. All middlewares must implement the \Oza75\LaravelSesComplaints\Contracts\CheckMiddleware::class
     * interface.
     */
    'middlewares' => [
        \Oza75\LaravelSesComplaints\Middlewares\ComplaintCheckMiddleware::class => [
            /**
             * The max number of sns complaint notification before stop sending email to the user
             */
            'max_entries' => 1,
            /**
             * If the check_by_subject is set to true, we will count
             * the amount of complaint notification  received from sns and that has the same subject as
             * the email we are trying to send. If the count is greater or equal to max_entry we don't send
             * the email.
             */
            'check_by_subject' => true,
        ],
        \Oza75\LaravelSesComplaints\Middlewares\BounceCheckMiddleware::class => [
            /**
             * The max number of sns bounced notification before stop sending email to the user
             */
            'max_entries' => 3,
            /**
             * If the check_by_subject is set to true, we will count
             * the amount of bounced notification  received from sns and that has the same subject as
             * the email we are trying to send. If the count is greater or equal to max_entry we don't send
             * the email.
             */
            'check_by_subject' => false,
        ]
    ],
];
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email abouba181@gmail.com instead of using the issue tracker.

## Credits

-   [Aboubacar OUATTARA](https://github.com/oza75)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
