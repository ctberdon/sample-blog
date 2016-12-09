<?php

class User extends Private_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        echo 'User';
    }
}
