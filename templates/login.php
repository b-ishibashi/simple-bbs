<!DOCTYPE html>
<html>
    <head>
        <title>ログイン機能</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/style.css">
    </head>
    <body>
        <h1>ログイン</h1>
        <form method="post" action="login.php">
            <label for="email">メールアドレス:</label>
            <input id="email" type="email" name="email"><br>
            <label for="password">パスワード:</label>
            <input id="password" type="password" name="password"><br>
            <input type="submit" name="login" value="ログイン">
        </form>
        <?php if(!empty($errors)): ?>
            <ul class="errors">
                <?php foreach($errors as $error): ?>
                    <li><?=h($error)?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <a href="/register.php">新規登録はこちら</a>
    </body>
</html>
