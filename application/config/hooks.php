<?php
defined('BASEPATH') or exit('No direct script access allowed');

$hook['post_controller_constructor'][] = [
    'class'    => 'CorsHook',
    'function' => 'handle',
    'filename' => 'CorsHook.php',
    'filepath' => 'hooks'
];
