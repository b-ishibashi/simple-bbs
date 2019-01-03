<?php

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function require_user_session()
{
    session_start();
    //未ログイン
    if (!isset($_SESSION["email"])) {
        header("location: /login.php");
        exit;
    }
}

function require_guest_session()
{
    session_start();
    //ログイン済み
    if (isset($_SESSION["email"])) {
        header('location: /index.php');
        exit;
    }
}

function is_valid_email($email, $check_dns = false)
{
    switch (true) {
        case false === filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE):
        case !preg_match('/@([^@\[]++)\z/', $email, $m):
            return false;

        case !$check_dns:
        case checkdnsrr($m[1], 'MX'):
        case checkdnsrr($m[1], 'A'):
        case checkdnsrr($m[1], 'AAAA'):
            return true;

        default:
            return false;
    }
}

function write_account($email, $password)
{
    $fp = fopen(__DIR__ . '/accounts.csv', 'a');
    fputcsv($fp, [$email, password_hash($password, PASSWORD_DEFAULT)]);

}

function read_account_password($email)
{
    $fp = fopen(__DIR__ . '/accounts.csv', 'a+');
    while ($row = fgetcsv($fp)) {
        if ($email === $row[0]) {
            return $row[1];
        }
    }
    return null;
}

function verify_account($email, $password)
{
    $hash = read_account_password($email);
    if ($hash === null) {
        return false;
    }
    return password_verify($password, $hash);
}