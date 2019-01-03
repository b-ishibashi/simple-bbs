<!DOCTYPE html>
<html>
<head>
    <title>会員登録</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<h1>会員登録</h1>
<form method="post" action="">
    <label for="email">メールアドレス:</label>
    <input id="email" type="email" name="email"><br>
    <label for="password">パスワード:</label>
    <input id="password" type="password" name="password"><br>
    <input type="submit" name="register" value="登録">
</form>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
        <li><?=h($error)?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>
