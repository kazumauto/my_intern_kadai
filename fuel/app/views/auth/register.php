<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新規登録</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { padding: 8px; width: 100%; max-width: 300px; }
        button { padding: 10px 20px; cursor: pointer; }
    </style>
</head>
<body>

    <h1>新規登録</h1>

    <form action="" method="post">
        
        <div class="form-group">
            <label>ユーザー名</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">登録する</button>

    </form>
    <p>
        <a href="/auth/login">すでに登録済みの方はこちら（ログイン）</a>
    </p>

</body>
</html>