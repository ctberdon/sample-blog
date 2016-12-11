<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Model
{
    
    /**
     * Table name this model represents
     * @var string
     */
    private $table_name = 'posts';
    
    /**
     * The table primary key field name
     * @var string
     */
    private $primary_key = 'id';
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function getLastPosts($user_id = 'all', $num_of_posts = 5)
    {
        $this->db->select("P.*, CONCAT_WS(' ', U.first_name, U.last_name) author, U.picture_url, U.profile_url");
        $this->db->join('users U', 'P.post_author_id = U.id', 'left');
        
        // determine if whose post to get
        if (is_numeric($user_id) && $user_id > 0)
        {
            $this->db->order_by('P.created DESC');
            $this->db->where('P.post_author_id', $user_id);
        }
        else
        {
            $this->db->order_by('P.published DESC, P.modified DESC');
            $this->db->where('P.post_status', 'published');
        }
        
        $posts = $this->db->get($this->table_name . ' P', $num_of_posts, 0);
        
        if ($posts->num_rows() > 0)
        {
            return $posts->result_array();
        }
        
        return array();
    }
    
    public function getPosts($user_id = 'all', $page = 1, $post_per_page = 5)
    {
        $main_sql = $this->db->join('users U', 'P.post_author_id = U.id', 'left');
        
        // determine if whose post to get
        if (is_numeric($user_id) && $user_id > 0)
        {
            $main_sql->order_by('P.created DESC');
            $main_sql->where('P.post_author_id', $user_id);
        }
        else
        {
            $main_sql->order_by('P.published DESC, P.modified DESC');
            $main_sql->where('P.post_status', 'published');
        }
        
        // get total records
        $count_query = clone $main_sql;
        $count_query->select('COUNT(*) total_records');
        $total_query = $count_query->get($this->table_name . ' P');
        
        // debug
        //error_log($count_query->last_query());
        
        // init $total_records
        $total_records = 0;
        // now get $total_records
        if ($total_query->num_rows() > 0)
        {
            $rows = $total_query->row_array();
            $total_records = $rows['total_records'];
        }
        
        // get max page
        $max_page = (int)ceil($total_records/$post_per_page);
        $page > $max_page and $page = $max_page;
        
        // compute offset
        $offset = ($page - 1) * $post_per_page;
        // fix for offset going negative
        $offset < 0 and $offset = 0;
        
        // run main query
        $main_sql->select("P.*, CONCAT_WS(' ', U.first_name, U.last_name) author, U.picture_url, U.profile_url");
        $posts = $main_sql->get($this->table_name . ' P', $post_per_page, $offset);
        
        // debug
        //error_log($this->db->last_query());
        
        if ($posts->num_rows() > 0)
        {
            return array(
                'total_records'    => $total_records,
                'current_page'     => $page,
                'total_pages'      => $max_page,
                'records_per_page' => $post_per_page,
                'records'          => $posts->result_array(),
            );
        }
        
        return array(
            'total_records'    => 0,
            'current_page'     => 1,
            'total_pages'      => 1,
            'records_per_page' => $post_per_page,
            'records'          => array(),
        );
    }
    
    public function insertOrUpdatePost(array $data)
    {
        // sanitize data
        $data = array_sanitizer($data);
        
        // filtered data
        $filtered_data = array();
        
        $allowed_data = array(
            'post_author_id',
            'post_title',
            'post_excerpt',
            'post_content',
            'post_status',
        );
        
        $required_data = array(
            'post_author_id',
            'post_title',
            'post_content',
        );
        
        // now check submitted data
        foreach ($allowed_data as $field_name)
        {
            if ( isset($data[$field_name]))
            {
                $filtered_data[$field_name] = $data[$field_name];
            }
        }
        
        // check if required data are provided
        foreach ($required_data as $field_name)
        {
            if ( empty($filtered_data[$field_name]))
            {
                throw new Exception("$field_name is required");
            }
        }
        
        // do something with the submitted post message
        if (isset($filtered_data['post_content']))
        {
            $filtered_data['post_content'] = nl2br(htmlentities($filtered_data['post_content']));
        }
        
        // and also this
        if (isset($filtered_data['post_excerpt']))
        {
            $filtered_data['post_excerpt'] = nl2br(htmlentities($filtered_data['post_excerpt']));
        }
        
        // as well as this
        if (isset($filtered_data['post_title']))
        {
            $filtered_data['post_title'] = nl2br(htmlentities($filtered_data['post_title']));
        }
        
        // is post_status provided?
        (!isset($filtered_data['post_status']) || !in_array($filtered_data['post_status'], array('unpublished', 'published', 'private')))
                and $filtered_data['post_status'] = 'unpublished';
        // is published?
        if ( strcasecmp($filtered_data['post_status'], 'published') == 0 )
        {
            $filtered_data['published'] = date('Y-m-d H:i:s');
            $filtered_data['published_gmt'] = date('Y-m-d H:i:s');
        }
        
        // now check if this is edit
        if ( ! empty($data['id']))
        {
            // we're going to make sure this is a valid post we are editing
            $this->db->select('id');
            $found = $this->db->get_where($this->table_name, array('id' => $data['id']));
            
            // really exists?
            $rows = $found->num_rows();
            if ( empty($rows))
            {
                throw new Exception('Invalid post id');
            }
            
            $filtered_data['modified'] = date('Y-m-d H:i:s');
            $filtered_data['modified_gmt'] = gmdate('Y-m-d H:i:s');
            
            $this->db->where('id', $data['id']);
            $updated = $this->db->update($this->table_name, $filtered_data);
            
            return true;
        }
        
        // so, we are adding
        $filtered_data['created']  = date('Y-m-d H:i:s');
        $filtered_data['modified'] = date('Y-m-d H:i:s');
        $filtered_data['modified_gmt'] = gmdate('Y-m-d H:i:s');
        
        $inserted = $this->db->insert($this->table_name, $filtered_data);
        
        // say yes
        return true;
    }
    
    public function getPost($post_id, $user_id, $mode = 'read')
    {
        $this->db->where('id', $post_id);
        $this->db->where('post_author_id', $user_id);
        $post = $this->db->get($this->table_name);
        
        if ($post->num_rows() > 0)
        {
            $post_details = $post->row_array();
            
            if (strcasecmp('edit', $mode) == 0)
            {
                // revert back nl2br
                $post_details['post_content'] = html_entity_decode(br2nl($post_details['post_content']));
                $post_details['post_excerpt'] = html_entity_decode(br2nl($post_details['post_excerpt']));
                $post_details['post_title'] = html_entity_decode(br2nl($post_details['post_title']));
            }
            
            return $post_details;
        }
        
        return array();
    }
    
    public function deletePost($post_id, $user_id)
    {
        $this->db->where('id', $post_id);
        $this->db->where('post_author_id', $user_id);
        $post = $this->db->delete($this->table_name);
        
        return true;
    }
    
    public function toggleStatus($post_id, $user_id)
    {
        // check if post exists
        $this->db->where('id', $post_id);
        $this->db->where('post_author_id', $user_id);
        $post = $this->db->get($this->table_name);
        
        $rows = $post->num_rows();
        if (empty($rows))
        {
            return false;
        }
        
        $post_details = $post->row_array();
        
        // init update data
        $db_data = array();
        
        // get current status
        // NOTE: published and unpublished ONLY
        $db_data['post_status'] = $post_details['post_status'] == 'published' ? 'unpublished' : 'published';
        $db_data['modified'] = date('Y-m-d H:i:s');
        $db_data['modified_gmt'] = gmdate('Y-m-d H:i:s');
        
        if ( $db_data['post_status'] == 'published' )
        {
            $db_data['published'] = date('Y-m-d H:i:s');
            $db_data['published_gmt'] = date('Y-m-d H:i:s');
        }
        
        // update it now
        $this->db->where('id', $post_id);
        $this->db->where('post_author_id', $user_id);
        $updated = $this->db->update($this->table_name, $db_data);
        
        // retrieve back updated status
        $this->db->select('post_status');
        $this->db->where('id', $post_id);
        $this->db->where('post_author_id', $user_id);
        $post = $this->db->get($this->table_name);
        
        // we know that this post exists, so proceed
        return $post->row_array();
    }
    
}