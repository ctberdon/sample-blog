<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Determine if user is logged in
 * 
 * @return boolean
 */
function is_loggedin()
{
    $CI =& get_instance();
    $userdata = $CI->session->userdata('userdata');
    return empty($userdata) ? false : true;
}

/**
 * Get controller name
 * 
 * @return string
 */
function get_controllername()
{
    $CI =& get_instance();
    return $CI->router->fetch_class();
}

/**
 * Convert back <br /> to newline
 * 
 * @param string $string
 * @return string
 */
function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "", $string);
}

/**
 * Render markdown via Markdown library
 * 
 * @param string $string
 * @return string
 */
function render_markdown($string)
{
    $CI =& get_instance();
    return $CI->markdown->parse($string);
}

/**
 * Sanitize array
 * 
 * @param array $data
 * @return array
 */
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