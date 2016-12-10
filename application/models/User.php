<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model
{
    
    /**
     * Table name this model represents
     * @var string
     */
    private $table_name = 'users';
    
    /**
     * The table primary key field name
     * @var string
     */
    private $primary_key = 'id';
    
    function __construct()
    {
        parent::__construct();
    }

    public function checkUser(array $data = array())
    {
        // sanitize data
        $data = array_sanitizer($data);
        
        // filtered data
        $filtered_data = array();
        
        /**
         * Cleanup or validate data
         */
        $allowed_data = array(
            'oauth_provider',
            'oauth_uid',
            'first_name',
            'last_name',
            'email',
            'gender',
            'locale',
            'picture_url',
            'profile_url',
        );
        
        // Filter data
        foreach ($allowed_data as $field_name)
        {
            // we'll consider zero here as non-empty
            $filtered_data[$field_name] = !empty($data[$field_name]) ? $data[$field_name] : '';
        }
        
        $this->db->select();
        $this->db->from($this->table_name);
        $this->db->where(array('oauth_provider' => $filtered_data['oauth_provider'], 'oauth_uid' => $filtered_data['oauth_uid']));
        
        // run query
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            // get resulting rows
            $rows = $query->row_array();
            // update modified date
            $filtered_data['modified'] = date('Y-m-d H:i:s');
            // do the update
            $updated = $this->db->update($this->table_name, $filtered_data, array('id' => $rows['id']));
        }
        else
        {
            $filtered_data['created']  = date('Y-m-d H:i:s');
            $filtered_data['modified'] = date('Y-m-d H:i:s');
            $inserted = $this->db->insert($this->table_name, $filtered_data);
            $user_id = $this->db->insert_id();
            
            // fetch inserted data back
            $query = $this->db->get_where($this->table_name, array('id' => $user_id), 1, 0);
            // get resulting rows
            $rows = $query->num_rows() > 0 ? $query->row_array() : array();
        }

        return !empty($rows) ? $rows : false;
    }

}
