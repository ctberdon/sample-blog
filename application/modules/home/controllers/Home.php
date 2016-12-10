<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller
{
    
    function __construct()
    {
        parent::__construct();
        
        // is user authenticated?
        if (is_loggedin() === true)
        {
            redirect('user');
        }
        
        // Load required models
        $this->load->model('post');
    }
    
    public function index()
    {
        $this->template_data['last_posts'] = $this->post->getLastPosts('all', 5);
        $this->load->view('default/views/home/home_index', $this->template_data);
    }
    
}