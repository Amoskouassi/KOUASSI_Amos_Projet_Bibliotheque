<?php
require_once __DIR__ . '/helpers.php';

function require_auth(){
    start_session();
    if (!isset($_SESSION['user_id'])) {
        set_flash('error','Veuillez vous connecter.');
        header('Location: login.php');
        exit;
    }
}

function require_admin(){
    require_auth();
    if ($_SESSION['role'] !== 'admin') {
        set_flash('error','Accès réservé aux administrateurs.');
        header('Location: index.html');
        exit;
    }
}

function is_logged_in(){
    start_session();
    return isset($_SESSION['user_id']);
}

function is_admin(){
    start_session();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
