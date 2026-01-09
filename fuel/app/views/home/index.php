<!DOCTYPE html>
<html>
<head>
    <title>ランキング一覧</title>
    </head>
<body>

    <div style="text-align: right;">
        <a href="/auth/login">ログアウト</a>
    </div>

    <h1>開催中のランキング</h1>

    <div style="max-width: 500px; margin: 0 auto; text-align: left;">
        
        <?php if (!empty($rankings)): ?>
            <?php foreach ($rankings as $ranking): ?>
                
                <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd;">
                    <a href="/home/view/<?php echo $ranking['id']; ?>">
                        🏆 <?php echo $ranking['name']; ?>
                    </a>
                </div>

                <?php if ($ranking['user_id'] == Session::get('user_id')): ?>
                    <a href="/home/delete/<?php echo $ranking['id']; ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('本当にこのランキングを削除してもよろしいですか？\n※この操作は取り消せません。');">
                       削除する
                    </a>
                <?php endif; ?>

            <?php endforeach; ?>
        <?php else: ?>
            <p>まだランキングがありません。</p>
        <?php endif; ?>

    </div>

    <div style="margin-top: 30px; border-top: 1px solid #ccc; padding-top: 20px;">
        <h3>新しいランキングを開催</h3>
        <form action="/home" method="post">
            <input type="text" name="name" placeholder="大会名を入力" required>
            <button type="submit">作成</button>
        </form>
    </div>

</body>
</html>