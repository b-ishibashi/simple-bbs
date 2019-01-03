<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>bbs</title>
        <link rel="stylesheet" href="/style.css">
    </head>
    <body>
        <h1>掲示板</h1>
        <div>こんにちは! <?=h($_SESSION["email"])?>さん。</div>
        <a href="/logout.php">ログアウト</a>
        <form action="" method="post">
            <label>名前:<input type="text" name="name"></label><br>
            <label>投稿内容:<textarea name="comment"></textarea></label>
            <input type="submit" value="送信" name="send">
            <?php if(!empty($errors)): ?>
            <ul class="errors">
                <?php foreach($errors as $error): ?>
                    <li><?=h($error)?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </form>
        <h2>投稿一覧</h2>
        <?php foreach($rows as $row): ?>
        <article>
            <div class="name">
                名前:<?=h($row[0])?>
            </div>
            <div class="comment">
                本文:<?=h($row[1])?>
            </div>
        </article>
        <?php endforeach; ?>
    </body>
</html>
