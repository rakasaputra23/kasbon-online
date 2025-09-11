<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers to be used while
    | sending an e-mail. You will specify which one you are using for your
    | mailers below. You are free to add additional mailers as required.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array", "failover"
    |
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => env('MAIL_TIMEOUT', 120), // Increased timeout
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
            // Additional options for better performance
            'stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ],
        ],

        'ses' => [
            'transport' => 'ses',
            // SES has high sending limits by default
            'options' => [
                'ConfigurationSetName' => env('SES_CONFIGURATION_SET'),
                'Tags' => [
                    [
                        'Name' => 'Application',
                        'Value' => 'Kasbon Online System',
                    ],
                ],
            ],
        ],

        'mailgun' => [
            'transport' => 'mailgun',
            'client' => [
                'timeout' => 120, // Increased timeout
                'connect_timeout' => 60,
            ],
        ],

        'postmark' => [
            'transport' => 'postmark',
            'client' => [
                'timeout' => 120, // Increased timeout
                'connect_timeout' => 60,
            ],
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        // Custom SMTP configuration for high volume sending
        'bulk' => [
            'transport' => 'smtp',
            'host' => env('BULK_MAIL_HOST', env('MAIL_HOST')),
            'port' => env('BULK_MAIL_PORT', env('MAIL_PORT', 587)),
            'encryption' => env('BULK_MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls')),
            'username' => env('BULK_MAIL_USERNAME', env('MAIL_USERNAME')),
            'password' => env('BULK_MAIL_PASSWORD', env('MAIL_PASSWORD')),
            'timeout' => 300, // 5 minutes timeout for bulk operations
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
            'stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@kasbon-system.com'),
        'name' => env('MAIL_FROM_NAME', 'Kasbon Online System'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for email sending to prevent overwhelming
    | the mail server and avoid being flagged as spam.
    |
    */

    'rate_limiting' => [
        'enabled' => env('MAIL_RATE_LIMITING_ENABLED', false), // Disable rate limiting
        'max_attempts' => env('MAIL_RATE_LIMITING_MAX_ATTEMPTS', 1000), // High limit
        'decay_minutes' => env('MAIL_RATE_LIMITING_DECAY_MINUTES', 1), // Short decay time
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration for Mail
    |--------------------------------------------------------------------------
    |
    | Configure queue settings for mail sending to handle high volume
    | email sending efficiently.
    |
    */

    'queue' => [
        'connection' => env('MAIL_QUEUE_CONNECTION', 'database'),
        'queue' => env('MAIL_QUEUE', 'emails'),
        'retry_after' => env('MAIL_QUEUE_RETRY_AFTER', 600), // 10 minutes
        'max_exceptions' => env('MAIL_QUEUE_MAX_EXCEPTIONS', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];