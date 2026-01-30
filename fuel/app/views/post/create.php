<!DOCTYPE html>
<html>
<head>
    <title>画像管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">

        <h1 class="mb-4">画像アップロード（管理者用）</h1>

        <?php if (Session::get_flash('success')): ?>
            <div class="alert alert-success">
                <?php echo Session::get_flash('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (Session::get_flash('error')): ?>
            <div class="alert alert-danger">
                <?php echo Session::get_flash('error'); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-5">
            <div class="card-body">
                <h5 class="card-title mb-3">新規アップロード</h5>
                
                <!-- enctypeは、ファイルを送るための特別な梱包をするという意味 -->
                <?php echo Form::open(array('action' => 'post/save', 'enctype' => 'multipart/form-data', 'class' => 'row g-3 align-items-center')); ?>
                    
                    <?php echo Form::csrf(); ?>

                    <div class="col-auto">
                        <!-- type="file"のおかげで、ファイル選択画面が開く -->
                        <input type="file" name="upload_file[]" multiple="multiple" class="form-control" required>
                    </div>
                    
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">アップロード</button>
                    </div>

                <?php echo Form::close(); ?>
            </div>
        </div>

        <hr class="mb-5">

        <h2 class="mb-4">画像一覧</h2>

        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
            <?php foreach ($images as $img): ?>
                
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="p-2">
                            <img src="/assets/img/uploads/<?php echo $img['url']; ?>" class="card-img-top">
                        </div>
                        
                        <div class="card-body text-center pt-0">
                            <form action="/post/delete/<?php echo $img['id']; ?>" method="post">
                                <?php echo Form::csrf(); ?>
                                <button type="submit" class="btn btn-danger btn-sm w-100" 
                                        onclick="return confirm('本当に削除しますか？');">
                                    削除
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        <div class="mt-5 mb-5">
            <a href="/home" class="btn btn-outline-secondary">← トップへ戻る</a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>