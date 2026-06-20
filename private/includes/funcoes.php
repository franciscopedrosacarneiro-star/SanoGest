
<?php

require_once __DIR__ . '/../../config/config.php';

function start_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function check_session()
{
    start_session();

    return isset($_SESSION['utilizador']);
}

function redirect_if_not_logged()
{
    start_session();

    if (!check_session()) {
        header('Location: ' . BASE_URL . '/public/login.php');
        exit;
    }
}

function redirect_if_logged()
{
    start_session();

    if (check_session()) {
        header('Location: ' . BASE_URL . '/private/index.php');
        exit;
    }
}

function logout()
{
    start_session();

    session_unset();
    session_destroy();

    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

function limpar_texto($valor)
{
    return trim(htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8'));
}