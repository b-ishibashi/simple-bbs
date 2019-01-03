<?php

//関数読み込み
require_once __DIR__ . '/../functions.php';

require_guest_session();

//ログイン機能
$errors = [];
if (isset($_POST['login'])) {
    if ($_POST['email'] === '') {
        $errors[] = 'メールアドレスを入力してください';
    }
    if ($_POST['password'] === '') {
        $errors[] = 'パスワードを入れてください';
    }
    if (empty($errors)) {
        if (verify_account($_POST['email'], $_POST['password'])) {
            $_SESSION['email'] = $_POST['email'];
            header('Location: index.php');
            exit;
        }
        $errors[] = 'メールアドレスかパスワードが間違っています';
    }
}

//template読み込み
include __DIR__ . '/../templates/login.php';
