<?php

if (!function_exists('get_email_config')) {
    function get_email_config($connection)
    {
        $data = [];

        switch ($connection) {
            case 'plan_on_track': // POT

                $data = [
                    'driver' => 'smtp',
                    'transport' => 'smtp',
                    'host' => env('MAIL_HOST_POT'),
                    'port' =>  env('MAIL_PORT_POT'),
                    'encryption' => env('MAIL_ENCRYPTION_POT'),
                    'username' => env('MAIL_USERNAME_POT'),
                    'password' => env('MAIL_PASSWORD_POT'),
                ];
                break;

            default:
                $data = [
                    'driver' => 'smtp',
                    'transport' => 'smtp',
                    'host' => env('MAIL_HOST'),
                    'port' => env('MAIL_PORT'),
                    'encryption' => env('MAIL_ENCRYPTION'),
                    'username' => env('MAIL_USERNAME'),
                    'password' => env('MAIL_PASSWORD'),
                ];
                break;
        }

        return $data;
    }
}
