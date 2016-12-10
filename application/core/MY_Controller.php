<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{
    // template data
    public $template_data = array();
    
    function __construct()
    {
        parent::__construct();
    }
}

class Public_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }
}

class Private_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
        if ( empty($this->session->userdata('userdata')) )
        {
            redirect('user/authentication');
        }
    }
}