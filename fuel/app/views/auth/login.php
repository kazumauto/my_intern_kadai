<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ログイン</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { padding: 8px; width: 100%; max-width: 300px; }
        button { padding: 10px 20px; cursor: pointer; }
        .error { color: red; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

    <h1>ログイン</h1>

    <?php if (Session::get_flash('error')): ?>
        <p class="error"><?php echo Session::get_flash('error'); ?></p>
    <?php endif; ?>

    <form action="" method="post">
        
        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">ログインする</button>

    </form>

    <p>
        <a href="/auth/register">まだ登録していない方はこちら（新規登録）</a>
    </p>

</body>
</html>