<?php

//関数読み込み
require_once __DIR__ . '/../functions.php';

require_user_session();

//ログアウト機能
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: /login.php');
    exit;
}

include __DIR__ . '/../templates/logout.php';
