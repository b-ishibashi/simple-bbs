<!DOCTYPE html>
<html>
<head>
    <title>ログアウト機能</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<h1>ログアウト</h1>
<p>本当にログアウトしますか?</p>
<form method="post" action="">
    <input type="submit" name="logout" value="はい">
    <input type="button" value="いいえ" onclick="history.back()">
</form>
</body>
</html>