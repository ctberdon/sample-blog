<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        
        // Load needed models
        $this->load->model('user');
    }
    
    public function index()
    {
        /*
         *  Ensure we've downloaded the oauth credentials
         */
        if (!$oauth_credentials = $this->googleapi->getOAuthCredentialsFile())
        {
            // @todo
            echo 'Missing credential file';
            return;
        }

        // set redirect url which is this controller's page
        $redirect_uri = base_url('user/authentication/');
        
        $client = new Google_Client();
        $client->setAuthConfig($oauth_credentials);
        $client->setRedirectUri($redirect_uri);
        $client->setScopes('email profile');
        
        // Turn off SSL here for easy testing on local
        $guzzle_client = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $client->setHttpClient($guzzle_client);
        
        if ($this->input->get('code'))
        {
            $token = $client->fetchAccessTokenWithAuthCode($this->input->get('code'));
            $client->setAccessToken($token);

            // store in the session also
            $this->session->set_userdata('token', $token);

            // redirect back
            redirect($redirect_uri);
        }
        
        /**
         * If we have an access token, we can make
         * requests, else we generate an authentication URL.
         */
        $token = $this->session->userdata('token');
        if (!empty($token))
        {
            $client->setAccessToken($token);
        }
        else
        {
            $this->template_data['google_auth_url'] = $client->createAuthUrl();
        }

        /**
         * If we're signed in we can go ahead and retrieve
         * the ID token, which is part of the bundle of
         * data that is exchange in the authenticate step
         * - we only need to do a network call if we have
         * to retrieve the Google certificate to verify it,
         * and that can be cached.
         */
        if ($client->getAccessToken())
        {
            $this->template_data['token_data'] = $client->verifyIdToken();
            
            // retrieve user's information needed to signup
            // into our site
            $oauth2 = new \Google_Service_Oauth2($client);
            $user_info = $oauth2->userinfo->get();
            
            $db_data = array(
                'oauth_provider' => 'google',
                'oauth_uid'      => $user_info['id'],
                'first_name'     => $user_info['given_name'],
                'last_name'      => $user_info['family_name'],
                'email'          => $user_info['email'],
                'gender'         => $user_info['gender'],
                'locale'         => !empty($user_info['locale']) ? $user_info['locale'] : '',
                'profile_url'    => $user_info['link'],
                'picture_url'    => $user_info['picture'],
            );
            
            // insert or update user data
            $userdata = $this->user->checkUser($db_data);
            if ( ! empty($userdata))
            {
                // debugging line
                $template_data['userdata'] = $userdata;
                // store to session
                $this->session->set_userdata('userdata', $userdata);
                // store user id to session
                $this->session->set_userdata('user_id', $userdata['id']);
            }
            
            // debugging line
            $this->template_data['user_info'] = $user_info;
            
            // nothing to do here
            redirect('user');
        }
        
        $this->load->view('default/views/user/authentication_index', $this->template_data);
    }

    public function logout()
    {
        $this->session->unset_userdata('token');
        $this->session->unset_userdata('userdata');
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();
        redirect('user/authentication');
    }

}
