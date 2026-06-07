<?php

function getAssets($path = '')
{
    return base_url('assets/' . $path);
}

function getTemplates($path = '')
{
    return base_url('templates/' . $path);
}

function loadView($view = '', $data = array())
{
    $CI = &get_instance();
    $CI->load->view($view, $data);
}
