<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function session_userdata($name)
{
    $CI =& get_instance();
    return $CI->session->userdata($name);
}

function session_set_userdata($name, $value = null)
{
    $CI =& get_instance();
    $CI->session->set_userdata($name, $value);
}

function flashdata_value($name)
{
    $CI =& get_instance();
    return $CI->session->flashdata($name);
}