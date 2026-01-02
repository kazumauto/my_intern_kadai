<!DOCTYPE html>
<html>
<head>
    <title>ランキング一覧</title>
    </head>
<body>

    <div style="text-align: right;">
        <a href="/battle">⚔️ 対決へ</a> | 
        <a href="/auth/login">ログアウト</a>
    </div>

    <h1>開催中のランキング</h1>

    <div style="max-width: 500px; margin: 0 auto; text-align: left;">
        
        <?php if (!empty($rankings)): ?>
            <?php foreach ($rankings as $r): ?>
                
                <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd;">
                    <a href="/home/view/<?php echo $r['id']; ?>">
                        🏆 <?php echo $r['name']; ?>
                    </a>
                </div>

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