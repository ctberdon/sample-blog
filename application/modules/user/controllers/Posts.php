<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends Private_Controller
{
    
    function __construct()
    {
        parent::__construct();
        
        // Load required model
        $this->load->model('post');
        
        // load need library
        $this->load->library('form_validation');
    }
    
    public function update_post()
    {
        // we require POST data
        if ( empty($this->input->post()) )
        {
            // redirect to where it belongs
            redirect('user/home');
        }
        
        // Set validation here
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('post_title', 'Title', 'trim|required');
        $this->form_validation->set_rules('post_content', 'Content', 'trim|required');
        
        // Validate form
        if ($this->form_validation->run() == false)
        {
            // Invalid form data
            $this->session->set_flashdata('form_validation_errors', validation_errors());
            redirect('user');
        }
        
        $db_data = $this->input->post();
        
        // also include current user's id
        $db_data = array_merge($db_data, array(
            'post_author_id'    => $this->session->userdata('user_id'),
        ));
        
        try
        {
            $this->post->insertOrUpdatePost($db_data);
        }
        catch (Exception $ex)
        {
            // Invalid form data
            $this->session->set_flashdata('form_validation_errors', $ex->getMessage());
            redirect('user');
        }

        $this->session->set_flashdata('success_message', 'Post successfully saved.');
        redirect('user');
    }
    
    public function edit_post($post_id)
    {
        $this->template_data['post_details'] = $this->post->getPost($post_id, $this->session->userdata('user_id'), 'edit');
        
        if (empty($this->template_data['post_details']))
        {
            // User not allowed to modify this post
            redirect('user');
        }
        
        $this->template_data['content'] = $this->load->view(config_item('views_path') . 'user/posts_form', $this->template_data, true);
        $this->load->view(config_item('views_layout'), $this->template_data);
    }
    
    public function remove_post($post_id = '')
    {
        if (empty($post_id))
        {
            // try to get it from get or post
            $post_id = $this->input->get_post('post_id');
            
            // if it's really none, kill it
            if (empty($post_id))
            {
                // is ajax request?
                if ($this->input->is_ajax_request())
                {
                    $data = array(
                        'status'    => 'failed',
                        'message'   => 'Post id invalid',
                    );

                    $this->output->set_output(json_encode($data));
                    return;
                }
                
                // this is if it's via url
                $this->session->flashdata('error_message', 'Post id invalid');
                redirect('user');
            }
        }
        
        $this->post->deletePost($post_id, $this->session->userdata('user_id'));
        
        // is ajax request?
        if ($this->input->is_ajax_request())
        {
            $data = array(
                'status'    => 'success',
                'message'   => 'Post successfully removed',
            );
            
            $this->output->set_output(json_encode($data));
            return;
        }
        
        // this is if it's via url
        $this->session->flashdata('success_message', 'Post successfully removed');
        redirect('user');
    }
    
    public function toggle_status($post_id = '')
    {
        if (empty($post_id))
        {
            // try to get it from get or post
            $post_id = $this->input->get_post('post_id');
            
            // if it's really none, kill it
            if (empty($post_id))
            {
                // is ajax request?
                if ($this->input->is_ajax_request())
                {
                    $data = array(
                        'status'    => 'failed',
                        'message'   => 'Invalid post id',
                    );

                    $this->output->set_output(json_encode($data));
                    return;
                }
                
                // this is if it's via url
                $this->session->flashdata('error_message', 'Invalid post id');
                redirect('user');
            }
        }
        
        $updated = $this->post->toggleStatus($post_id, $this->session->userdata('user_id'));
        
        if ($updated === false)
        {
            // is ajax request?
            if ($this->input->is_ajax_request())
            {
                $data = array(
                    'status'    => 'failed',
                    'message'   => 'Invalid post id',
                );

                $this->output->set_output(json_encode($data));
                return;
            }
            
             // this is if it's via url
            $this->session->flashdata('error_message', 'Invalid post id');
            redirect('user');
        }
        
        // is ajax request?
        if ($this->input->is_ajax_request())
        {
            $data = array(
                'status'    => 'success',
                'message'   => 'Post status successuflly changed',
                'data'      => $updated
            );
            
            $this->output->set_output(json_encode($data));
            return;
        }
        
        // this is if it's via url
        $this->session->flashdata('success_message', 'Post status successuflly changed');
        redirect('user');
    }
    
}