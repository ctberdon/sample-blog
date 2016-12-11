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
    
    /**
     * List down all users post
     * 
     * @param int $page Page number
     */
    public function index($page = 1)
    {
        // check page if valid
        $page = $page < 0 ? 1 : $page;
        
        // get all page by page number
        $all_posts = $this->post->getPosts($this->session->userdata('user_id'), $page, config_item('records_per_page'));
        // setup pagination
        $this->template_data['pagination'] = array(
            'base_url'      => site_url('user/index/'),
            'current_page'  => $all_posts['current_page'],
            'next_page'     => $all_posts['current_page'] < $all_posts['total_pages'] && $all_posts['total_pages'] > 0 ? $all_posts['current_page']+1 : 0,
            'previous_page' => $all_posts['current_page'] >= 2 && $all_posts['total_pages'] > 0 ? $all_posts['current_page'] - 1 : 0,
            'total_pages'   => $all_posts['total_pages'],
        );
        
        // dirty secret for redirected POST
        // do not try this at home
        // this is only to compensate adding more AJAX
        $flasdata_post = $this->session->flashdata('raw_post');
        if ( ! empty($flasdata_post))
        {
            $_POST = $flasdata_post;
        }

        $this->template_data['user_posts'] = $all_posts['records'];
        $this->template_data['content'] = $this->load->view(config_item('views_path') . 'user/user_index', $this->template_data, true);
        $this->load->view(config_item('views_layout'), $this->template_data);
    }
    
    /**
     * Generates random post for the user
     */
    public function generate_random_post()
    {
        if ($this->input->post())
        {
            // load up required helper
            $this->load->helper('lorem_ipsum');
            // let's generate
            // ... this might take a while
            set_time_limit(0);
            
            // get num of posts to generate from POST
            $num_of_posts = $this->input->post('num_of_posts', 1000);
            // tolerate only upto 10000
            (int)$num_of_posts > 10000 and $num_of_posts = 10000;
            
            // Yep, generate it is!
            for ($i=0; $i<$num_of_posts; $i++)
            {
                $post_content = lipsum(5, 'medium', array('decorate', 'link'));

                $data = array(
                    'post_author_id'    => $this->session->userdata('user_id'),
                    'post_title'        => 'Random Post Number ' . ($i+1),
                    'post_content'      => $post_content,
                    'post_status'       => 'published', // tag all as published
                );

                // call on a method specifically intended to insert random post
                $this->post->insertRandomPost($data);
            }
        }
        
        $this->template_data['content'] = $this->load->view(config_item('views_path') . 'user/user_generate_random_post', $this->template_data, true);
        $this->load->view(config_item('views_layout'), $this->template_data);
    }
}