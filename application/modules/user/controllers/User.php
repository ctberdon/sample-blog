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
        $this->template_data['user_last_posts'] = $this->post->getLastPosts($this->session->userdata('user_id'), 5);
        $this->template_data['content'] = $this->load->view(config_item('views_path') . 'user/user_index', $this->template_data, true);
        $this->load->view(config_item('views_layout'), $this->template_data);
    }
}