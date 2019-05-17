<?php

return [

    // ...

    /*
    |--------------------------------------------------------------------------
    | Firebase Settings section
    |--------------------------------------------------------------------------
    |
    | Here you may specify some configs for FCM.
    |
    */

    'fcm' => [

        /*
         |----------------------------------------------------------------------
         | Firebase service driver
         |----------------------------------------------------------------------
         |
         | Value `file` or `config`:
         |   - Select `file` option to make service read json file
         |   - Select `config` option to set up all section in config file
         |
         */
        'server_key' => env('FIREBASE_SERVER_KEY'),
        'driver' => env('FCM_DRIVER', ''),

        /*
         |---------------------------------------------------------------------
         | FCM Drivers
         |---------------------------------------------------------------------
         |
         | Here are each of the firebase.
         |
         */

        'drivers' => [

            'file' => [
                'path' => env('FCM_FILE_PATH', base_path('storage/fcm.json')),
            ],

            'config' => [

                /*
                |------------------------------------------------------------
                | Credentials
                |------------------------------------------------------------
                |
                | Content of `firebase.json` file in config. Using if
                | `fcm.driver` is `config`. All fields required!
                |
                */

                'credentials' => [
                    'private_key_id'              => env('FCM_CREDENTIALS_PRIVATE_KEY_ID',
                        'da80b3bbceaa554442ad67e6be361a66'),
                    'private_key'                 => env('FCM_CREDENTIALS_PRIVATE_KEY',
                        '-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n'),
                    'client_email'                => env('FCM_CREDENTIALS_CLIENT_EMAIL',
                        'firebase-adminsdk-mwax6@test.iam.gserviceaccount.com'),
                    'client_id'                   => env('FCM_CREDENTIALS_CLIENT_ID', '22021520333507180281'),
                    'auth_uri'                    => env('FCM_CREDENTIALS_AUTH_URI',
                        'https://accounts.google.com/o/oauth2/auth'),
                    'token_uri'                   => env('FCM_CREDENTIALS_TOKEN_URI',
                        'https://oauth2.googleapis.com/token'),
                    'auth_provider_x509_cert_url' => env('FCM_CREDENTIALS_AUTH_PROVIDER_CERT',
                        'https://www.googleapis.com/oauth2/v1/certs'),
                    'client_x509_cert_url'        => env('FCM_CREDENTIALS_CLIENT_CERT',
                        'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-mwax6%40test.iam.gserviceaccount.com'),
                ],
            ],
        ],
    ],

];
