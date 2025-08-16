<?php

function debug($data, string $type = '', bool $exit = true): void
{
    error_reporting(-1);
    ini_set('display_errors', '1');

    switch (strtolower($type)) {
        case 'vard':
        case 'dump':
            echo "<pre>";
            var_dump($data);
            echo "</pre>";
            break;

        case 'json':
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;

        case 'html':
            echo htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
            break;

        default: // print_r default
            echo "<pre>";
            print_r($data);
            echo "</pre>";
    }

    if ($exit) {
        exit;
    }
}

function setArray(bool $status = true, string $message = "", array $extra = []): array
{
    $result = [
        'error'   => $status,
        'message' => $message,
    ];

    if (!$status) {
        $result = array_merge($result, $extra);
    }

    return $result;
}

function loadView($file)
{
    $CI = &get_instance();
    return $CI->load->view($file);
}

function isEmptyNull($val)
{
    if ($val === null || empty($val) || $val == "") {
        return true;
    } else {
        return false;
    }
}

function setErrorResponse($message = "Error Occured. Please contact system administrator!")
{
    return setArray(true, $message);
}

function getPost()
{
    $CI = &get_instance();

    $post = $CI->input->post();
    unset($post['route']);

    return $post;
}

function getGet()
{
    $CI = &get_instance();

    $get = $CI->input->get();
    unset($get['route']);

    return $get;
}

function sessData($param, $all = false)
{
    $CI = &get_instance();
    if ($all) {
        return ($_SESSION);
    } else {
        return $CI->session->userdata($param);
    }
}

function getTimeNow()
{
    return date('Y-m-d H:i:s');
}

function getUri($key)
{
    $CI = &get_instance();
    return $CI->uri->segment($key);
}

function tglIndo($tanggal)
{
    if (isEmptyNull($tanggal)) {
        return "-";
        exit;
    }

    $tanggal = date('Y-m-d', strtotime($tanggal));

    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
