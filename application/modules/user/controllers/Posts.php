<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends Private_Controller
{
    
    function __construct()
    {
        parent::__construct();
        
        // Load required model
        $this->load->model('post');
    }
    
    public function update_post()
    {
        // we require POST data
        if ( empty($this->input->post()) )
        {
            // redirect to where it belongs
            redirect('user/home');
        }
        
        $db_data = $this->input->post();
        
        // also include current user's id
        $db_data = array_merge($db_data, array(
            'post_author_id'    => $this->session->userdata('user_id'),
        ));
        
        $success = $this->post->insertOrUpdatePost($db_data);
        
        redirect('user/home');
    }
    
    public function edit_post($post_id)
    {
        $this->template_data['post_details'] = $this->post->getPosts($post_id, $this->session->userdata('user_id'));
        
        $this->template_data['all_last_posts'] = $this->post->getLastPosts('all', 5);
        $this->template_data['user_last_posts'] = $this->post->getLastPosts($this->session->userdata('user_id'), 5);
        $this->load->view('default/views/user/user_home', $this->template_data);
    }
    
    public function remove_post($post_id)
    {
        $this->post->deletePosts($post_id, $this->session->userdata('user_id'));
        redirect('user/home');
    }
    
}