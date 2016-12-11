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
    
    public function index($page = 1)
    {
        // check page if valid
        $page = $page < 0 ? 1 : $page;
        
        // get all page by page number
        $all_posts = $this->post->getPosts('all', $page, config_item('records_per_page'));
        // setup pagination
        $this->template_data['pagination'] = array(
            'base_url'      => site_url('home/index/'),
            'current_page'  => $all_posts['current_page'],
            'next_page'     => $all_posts['current_page'] < $all_posts['total_pages'] && $all_posts['total_pages'] > 0 ? $all_posts['current_page']+1 : 0,
            'previous_page' => $all_posts['current_page'] >= 2 && $all_posts['total_pages'] > 0 ? $all_posts['current_page'] - 1 : 0,
            'total_pages'   => $all_posts['total_pages'],
        );

        $this->template_data['all_posts'] = $all_posts['records'];
        $this->template_data['content'] = $this->load->view(config_item('views_path') . 'home/home_index', $this->template_data, true);
        $this->load->view(config_item('views_layout'), $this->template_data);
    }
    
}