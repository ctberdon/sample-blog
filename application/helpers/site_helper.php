<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function is_loggedin()
{
    $CI =& get_instance();
    $userdata = $CI->session->userdata('userdata');
    return empty($userdata) ? false : true;
}

function get_controllername()
{
    $CI =& get_instance();
    return $CI->router->fetch_class();
}

function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "", $string);
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