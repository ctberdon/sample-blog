<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function is_loggedin()
{
    $CI =& get_instance();
    
    return empty($CI->session->userdata('userdata')) ? false : true;
}

function array_sanitizer(array $data)
{
    if (empty($data))
    {
        return $data;
    }

    foreach ($data as $k => &$v)
    {
        $v = trim($v);
    }

    return $data;
}