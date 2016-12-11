<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller
{
    
    function __construct()
    {
        parent::__construct();
        
        // Load required models
        $this->load->model('post');
    }
    
    public function index()
    {
        $this->template_data['last_posts'] = $this->post->getLastPosts('all', 5);
        $this->template_data['content'] = $this->load->view(config_item('views_path') . 'home/home_index', $this->template_data, true);
        $this->load->view(config_item('views_layout'), $this->template_data);
    }
    
}