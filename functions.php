<?php

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function require_user_session()
{
    session_start();
    //未ログイン
    if (!isset($_SESSION['user_id'])) {
        header("location: /login.php");
        exit;
    }
}

function require_guest_session()
{
    session_start();
    //ログイン済み
    if (isset($_SESSION['user_id'])) {
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

function create_user($email, $password)
{
    $pdo = pdo();
    $stmt = $pdo->prepare('insert into users (email, password) values (?, ?)');
    $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
}

function get_user_by_email($email)
{
    $stmt = pdo()->prepare('select * from users where email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function get_user_by_id($id)
{
    $stmt = pdo()->prepare('select * from users where id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function create_post($user_id, $text)
{
    $stmt = pdo()->prepare('insert into posts (user_id, text) values (?, ?)');
    $stmt->execute([$user_id, $text]);
}

function get_posts()
{
//    $stmt = pdo()->prepare('select * from posts order by id desc');
//    $stmt->execute();
    $stmt = pdo()->query('
      select posts.*, users.email from posts
      inner join users
              on posts.user_id = users.id
      order by id desc
    ');
    return $stmt->fetchAll();
}

function verify_user($email, $password)
{
    $user = get_user_by_email($email);
    if (!$user) {
        return false;
    }
    if (!password_verify($password, $user['password'])) {
        return false;
    }
    return $user;
}

function pdo()
{
    $pdo = new PDO('sqlite:' . __DIR__ . '/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    //テーブル作成
    $pdo->exec('
        create table if not exists users(
          id integer primary key autoincrement,
          email text not null,
          password text not null  
        );
        create table if not exists posts(
          id integer primary key autoincrement,
          user_id integer not null,
          text text not null
        );
    ');

    return $pdo;
}
