<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Retrieve session data
 * 
 * @param string $name Session key name
 * @return mixed
 */
function session_userdata($name)
{
    $CI =& get_instance();
    return $CI->session->userdata($name);
}

/**
 * Set session data
 * 
 * @param string $name Session key name
 * @param mixed $value Session key value
 */
function session_set_userdata($name, $value = null)
{
    $CI =& get_instance();
    $CI->session->set_userdata($name, $value);
}

/**
 * Retrieve flash session value
 * 
 * @param string $name Session key name
 * @return mixed
 */
function flashdata_value($name)
{
    $CI =& get_instance();
    return $CI->session->flashdata($name);
}