<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Site name
$config['sitename'] = "Coding Avenue Demo Blog";
$config['theme'] = "default";
$config['layout'] = "template"; // main template file
$config['theme_url'] = "themes/{$config['theme']}/";
$config['views_path'] = "{$config['theme']}/views/";
$config['views_layout'] = "{$config['theme']}/{$config['layout']}";