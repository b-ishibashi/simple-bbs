<?php

/**
 * HTMLエスケープ
 *
 * <?=h($str)?> の形で使う
 *
 * @param $str
 * @return string
 */
function h(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * session_start() して，ユーザがログインしていることを要求
 * ログインしていなければ /login.php に飛ばす
 */
function require_user_session(): void
{
    session_start();
    // user_id がセッションに記録されていない場合は未ログインとみなす
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * session_start() して，ユーザがログインしていないを要求
 * ログインしていれば /index.php に飛ばす
 */
function require_guest_session(): void
{
    session_start();
    // user_id がセッションに記録されていればログイン済みとみなす
    if (isset($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
}

/**
 * メールアドレスのバリデーション
 *
 * @param mixed $email
 * @param bool $check_dns
 * @return bool
 */
function is_valid_email($email, bool $check_dns = false): bool
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

/**
 * ユーザの新規登録
 *
 * @param string $email
 * @param string $password
 */
function create_user(string $email, string $password): void
{
    $pdo = pdo();
    $stmt = $pdo->prepare('insert into users (email, password) values (?, ?)');
    $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
}

/**
 * メールアドレスからユーザを取得
 *
 * @param string $email
 * @return array|bool
 */
function get_user_by_email(string $email)
{
    $stmt = pdo()->prepare('select * from users where email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch();
}

/**
 * IDからユーザを取得
 *
 * @param string|int $id
 * @return array|bool
 */
function get_user_by_id($id)
{
    $stmt = pdo()->prepare('select * from users where id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * 投稿の新規作成
 *
 * @param string|int $user_id
 * @param string $text
 */
function create_post($user_id, string $text): void
{
    $stmt = pdo()->prepare('insert into posts (user_id, text) values (?, ?)');
    $stmt->execute([$user_id, $text]);
}

/**
 * 投稿全件を新しい順に取得
 *
 * @return array
 */
function get_posts(): array
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

/**
 * ユーザ認証
 *
 * @param string $email
 * @param string $password
 * @return array|bool
 */
function verify_user(string $email, string $password)
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

/**
 * データベース接続してPDOを返す
 *
 * @return PDO
 */
function pdo(): PDO
{
    // 2回目以降の呼び出しでも再利用する
    static $pdo;

    // 初回のみ実行
    if (!$pdo) {
        $pdo = new PDO('sqlite:' . __DIR__ . '/database.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // テーブル作成
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
    }

    return $pdo;
}
