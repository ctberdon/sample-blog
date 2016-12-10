<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class GoogleAPI
{
    function __construct()
    {
        include_once __DIR__ . '/google-api-php-client/vendor/autoload.php';
    }
    
    public function getOAuthCredentialsFile()
    {
        // oauth2 creds
        $oauth_creds = __DIR__ . '/google-api-php-client/oauth-credentials.json';

        if (file_exists($oauth_creds))
        {
            return $oauth_creds;
        }

        return false;
    }

}