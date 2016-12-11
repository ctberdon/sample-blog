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
        /**
         * If we've got ?code from Google, GOOD
         */
        if ($this->input->get('code'))
        {
            $token = $this->google_client->fetchAccessTokenWithAuthCode($this->input->get('code'));
            $this->google_client->setAccessToken($token);

            // store in the session also
            $this->session->set_userdata('token', $token);

            // redirect back to page in-charge of validating data
            redirect($this->google_redirect_uri);
        }
        
        /**
         * If we have an access token, we can make
         * requests, else redirect home
         */
        $token = $this->session->userdata('token');
        if (!empty($token))
        {
            $this->google_client->setAccessToken($token);
        }
        else
        {
            redirect('home');
        }

        /**
         * If we're signed in we can go ahead and retrieve
         * the ID token, which is part of the bundle of
         * data that is exchange in the authenticate step
         * - we only need to do a network call if we have
         * to retrieve the Google certificate to verify it,
         * and that can be cached.
         */
        if ($this->google_client->getAccessToken())
        {
            $this->template_data['token_data'] = $this->google_client->verifyIdToken();
            
            // retrieve user's information needed to signup
            // into our site
            $oauth2 = new \Google_Service_Oauth2($this->google_client);
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
                //$template_data['userdata'] = $userdata;
                // store to session
                $this->session->set_userdata('userdata', $userdata);
                // store user id to session
                $this->session->set_userdata('user_id', $userdata['id']);
            }
            
            // debugging line
            //$this->template_data['user_info'] = $user_info;
            
            // nothing to do here
            redirect('user');
        }
        
        // if else failed
        redirect('home');
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
