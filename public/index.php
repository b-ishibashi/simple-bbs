<?php

//関数読み込み
require_once __DIR__ . '/../functions.php';

require_user_session();

$fp = fopen(__DIR__ . '/../comments.csv', 'a+');

$errors = [];
if (isset($_POST['send'])) {
    $name = $_POST['name'] === '' ? '名無しさん' : $_POST['name'];
    if (mb_strlen($name) > 15) {
        $errors[] = '名前が長すぎます';
    }
    if (mb_strlen($_POST['comment']) > 140) {
        $errors[] = '本文が長すぎます';
    } elseif ($_POST['comment'] === '') {
        $errors[] = "投稿内容がありません";
    }
    if (empty($errors)) {
        fputcsv($fp, [$name, $_POST['comment']]);
        header('Location: /index.php');
        exit;
    }
}

$rows = [];
while ($row = fgetcsv($fp)) {
    $rows[] = $row;
}
$rows = array_reverse($rows);

//template読み込み
include __DIR__ . '/../templates/index.php';