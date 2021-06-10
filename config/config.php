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
