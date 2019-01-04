<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>bbs</title>
        <link rel="stylesheet" href="/style.css">
    </head>
    <body>
        <h1>掲示板</h1>
        <div>こんにちは! <?=h($user['email'])?>さん。</div>
        <a href="/logout.php">ログアウト</a>
        <form action="" method="post">
            <label>投稿内容:<textarea name="text"></textarea></label>
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
        <?php foreach($posts as $post): ?>
        <article>
            <div class="">
                No.<?=h($post['id'])?>
            </div>
            <div class="name">
                Name:<?=h($post['email'])?>
            </div>
            <div class="comment">
                本文:<?=h($post['text'])?>
            </div>
        </article>
        <?php endforeach; ?>
    </body>
</html>
