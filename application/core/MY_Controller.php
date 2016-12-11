<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{
    /**
     * Template views data
     * @var array
     */
    public $template_data = array();
    
    /**
     * Google API instance
     * @var object
     */
    public $google_client = null;
    
    /**
     * Google OAuth redirect URI
     * @var string
     */
    public $google_redirect_uri = '';
    
    
    function __construct()
    {
        parent::__construct();
        
        /*
         * INIT Google Sign In
         * Do this only if user's not signed in yet
         *  Ensure we've downloaded the oauth credentials
         */
        if (is_loggedin() === false)
        {
            if (!$oauth_credentials = $this->googleapi->getOAuthCredentialsFile())
            {
                // @todo
                echo 'Missing google API credential file.';
                return;
            }

            // set redirect url which is this controller's page
            $this->google_redirect_uri = site_url('user/authentication/');

            $this->google_client = new Google_Client();
            $this->google_client->setAuthConfig($oauth_credentials);
            $this->google_client->setRedirectUri($this->google_redirect_uri);
            $this->google_client->setScopes('email profile');

            // Turn off SSL here for easy testing on local
            $guzzle_client = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
            $this->google_client->setHttpClient($guzzle_client);
        }
        else
        {
            // Yepey!! The user logs in
            // Let's get to know him/her
            // and tell our views about it
            $this->template_data['userdata'] = $this->session->userdata('userdata');
        }
        
        /**
         * If we have an Google's access token, we can make
         * requests, else we generate an authentication URL.
         */
        $token = $this->session->userdata('token');
        if (empty($token))
        {
            $this->template_data['google_auth_url'] = $this->google_client->createAuthUrl();
        }
    }
}

class Public_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }
}

class Private_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
        if ( empty($this->session->userdata('userdata')) )
        {
            redirect('user/authentication');
        }
    }
}