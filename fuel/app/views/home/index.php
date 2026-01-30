<!DOCTYPE html>
<html>
<head>
    <title>ランキング一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">

        <div class="text-end mb-3">
            <a href="/auth/logout" class="btn btn-outline-secondary btn-sm">ログアウト</a>
        </div>

        <h1 class="text-center mb-4">開催中のランキング</h1>

        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <div class="list-group">
                    <?php if (!empty($rankings)): ?>
                        <?php foreach ($rankings as $ranking): ?>
                            
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                
                                <a href="/home/view/<?php echo $ranking['id']; ?>" class="text-dark text-decoration-none fw-bold">
                                    🏆 <?php echo $ranking['name']; ?>
                                </a>

                                <?php if ($ranking['user_id'] == Session::get('user_id')): ?>
                                    <form action="/home/delete/<?php echo $ranking['id']; ?>" method="post" class="m-0">
                                        <?php echo Form::csrf(); ?>
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('本当に削除しますか？');">
                                            削除
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            まだランキングがありません。
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card mt-4 bg-light">
                    <div class="card-body">
                        <h5 class="card-title">新しいランキングを開催</h5>
                        <form action="/home" method="post" class="d-flex gap-2">
                            <?php echo Form::csrf(); ?>
                            <input type="text" name="name" class="form-control" placeholder="大会名を入力" required>
                            <button type="submit" class="btn btn-primary">作成</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>