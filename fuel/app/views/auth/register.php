<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新規登録</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        
        <div class="row justify-content-center">
            <div class="col-md-4">
                
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        <h1 class="h3 text-center mb-4">新規登録</h1>

                        <?php if (Session::get_flash('error')): ?>
                            <div class="alert alert-danger text-center font-sm">
                                <?php echo Session::get_flash('error'); ?>
                            </div>
                        <?php endif; ?>

                        <?php echo Form::open(array('action' => '', 'method' => 'post')); ?>
                            
                            <?php echo Form::csrf(); ?>

                            <div class="mb-3">
                                <label for="name" class="form-label">ユーザー名</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="表示名を入力" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">メールアドレス</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="example@email.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">パスワード</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="パスワードを設定" required>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">登録する</button>
                            </div>

                        <?php echo Form::close(); ?>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="small text-muted mb-1">すでにアカウントをお持ちの方</p>
                            <a href="/auth/login" class="text-decoration-none">ログインはこちら</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>