<?php
@session_start();
if(!isset($_SESSION["ses_login"]))
    exit(0);

$image_id = isset($_GET["id"]) ? $_GET["id"] : false;
if ($image_id === false) {
    exit(0);
}
$path = isset($_GET["path"]) ? $_GET["path"] : false;
if ($path === false) {
    exit(0);
}

$file = "../" . $path;
if (file_exists($file)) {
    header('Content-Type: image/$ext');
    $data = file_get_contents($file);
    echo $data;
    exit;
}
