<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Private_Controller
{
    function __construct()
    {
        parent::__construct();
        
        // Load required models
        $this->load->model('post');
    }
    
    public function index()
    {
        $this->template_data['userdata'] = $this->session->userdata('userdata');
        $this->load->view('default/views/user/user_index', $this->template_data);
    }
    
    public function home()
    {
        $this->template_data['all_last_posts'] = $this->post->getLastPosts('all', 5);
        $this->template_data['user_last_posts'] = $this->post->getLastPosts($this->session->userdata('user_id'), 5);
        $this->load->view('default/views/user/user_home', $this->template_data);
    }
}