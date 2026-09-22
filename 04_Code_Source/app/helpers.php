<?php
function e($v){
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
function start_session(){
    if (session_status() === PHP_SESSION_NONE) session_start();
}
function set_flash($type, $msg){
    start_session();
    $_SESSION['flash'] = ['type'=>$type, 'message'=>$msg];
}
function get_flash(){
    start_session();
    if (!isset($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}
