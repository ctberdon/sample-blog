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
    
    /**
     * Add or update post
     */
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
            // Repopulate POST
            $this->session->set_flashdata('raw_post', $this->input->post());
            // check for referer
            $redirect = $this->input->post('referer', '') == 'edit_post' && $this->input->get_post('id', '') != '' ?
                    'user/posts/edit_post/' . $this->input->get_post('id', '') :
                    'user';
            redirect($redirect);
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
            // Repopulate POST
            $this->session->set_flashdata('raw_post', $this->input->post());
            redirect('user');
        }

        $this->session->set_flashdata('success_message', 'Post successfully saved.');
        redirect('user');
    }
    
    /**
     * Edit post with given id
     * 
     * @param int $post_id Post id
     */
    public function edit_post($post_id)
    {
        $this->template_data['post_details'] = $this->post->getPost($post_id, $this->session->userdata('user_id'), 'edit');
        
        if (empty($this->template_data['post_details']))
        {
            // User not allowed to modify this post
            redirect('user');
        }
        
        // dirty secret for redirected POST
        // do not try this at home
        // this is only to compensate adding more AJAX
        $flasdata_post = $this->session->flashdata('raw_post');
        if ( ! empty($flasdata_post))
        {
            $_POST = $flasdata_post;
        }
        
        $this->template_data['referer'] = 'edit_post';
        $this->template_data['content'] = $this->load->view(config_item('views_path') . 'user/posts_form', $this->template_data, true);
        $this->load->view(config_item('views_layout'), $this->template_data);
    }
    
    /**
     * Remove post with given id
     * 
     * @param int $post_id
     * @return mixed
     */
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
    
    /**
     * Toggle published and unpublished status of the post
     * 
     * @param int $post_id
     * @return mixed
     */
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
    
    /**
     * Markdown text viewer. Called via AJAX request.
     */
    public function preview_markdown()
    {
        // this is strictly for ajax call
        if ( ! $this->input->is_ajax_request())
        {
            // get out please
            // show this guy a 404
            show_404();
        }
        
        // strictly POST
        $post_content = $this->input->post('post_content');
        // htmlentities you like?
        $post_content = nl2br(htmlentities($post_content));
        
        $data = array(
            'status'  => 'success',
            'message' => $this->markdown->parse($post_content),
        );

        $this->output->set_output(json_encode($data));
    }
    
}