<?php

//関数読み込み
require_once __DIR__ . '/../functions.php';

require_guest_session();

$errors = [];
if (isset($_POST['register'])) {
    if ($_POST['email'] === '') {
        $errors[] = 'メールアドレスを入力してください';
    } elseif (!is_valid_email($_POST['email'], true)) {
        $errors[] = 'メールアドレスが無効です';
    } elseif (read_account_password($_POST['email']) !== null) {
        $errors[] = 'すでに登録されています';
    }
    if (mb_strlen($_POST['password']) < 8) {
        $errors[] = 'パスワードは8文字以上で入力してください';
    }
    if (empty($errors)) {
        write_account($_POST['email'], $_POST['password']);
        header('Location: /login.php');
        exit;
    }
}

include __DIR__ . '/../templates/register.php';
