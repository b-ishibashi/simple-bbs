<?php

//関数読み込み
require_once __DIR__ . '/../functions.php';

require_user_session();

$errors = [];
if (isset($_POST['send'])) {
    if (mb_strlen($_POST['text']) > 140) {
        $errors[] = '本文が長すぎます';
    } elseif ($_POST['text'] === '') {
        $errors[] = "投稿内容がありません";
    }
    if (empty($errors)) {
        create_post($_SESSION['user_id'], $_POST['text']);
        header('Location: /index.php');
        exit;
    }
}

$posts = get_posts();

$user = get_user_by_id($_SESSION['user_id']);

//template読み込み
include __DIR__ . '/../templates/index.php';