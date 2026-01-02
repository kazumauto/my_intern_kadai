<!DOCTYPE html>
<html>
<head>
    <title>画像管理</title>
    <style>
        .image-list { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
        .image-item { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .image-item img { max-width: 150px; height: auto; display: block; margin-bottom: 5px; }
        .btn-delete { color: white; background: red; text-decoration: none; padding: 5px 10px; font-size: 12px; }
    </style>
</head>
<body>

    <h1>画像アップロード（管理者用）</h1>

    <?php if (Session::get_flash('success')): ?>
        <p style="color: green;"><?php echo Session::get_flash('success'); ?></p>
    <?php endif; ?>
    <?php if (Session::get_flash('error')): ?>
        <p style="color: red;"><?php echo Session::get_flash('error'); ?></p>
    <?php endif; ?>

    <?php echo Form::open(array('action' => 'post/save', 'enctype' => 'multipart/form-data')); ?>
        
        <input type="file" name="upload_file[]" multiple="multiple">
        
        <button type="submit">アップロード</button>
    <?php echo Form::close(); ?>

    <hr>

    <h2>画像一覧</h2>
    <div class="image-list">
        <?php foreach ($images as $img): ?>
            <div class="image-item">
                <?php echo Asset::img('uploads/' . $img['url']); ?>
                
                <a href="/post/delete/<?php echo $img['id']; ?>" 
                   class="btn-delete"
                   onclick="return confirm('本当に削除しますか？');">
                   削除
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <br>
    <a href="/home">トップへ戻る</a>

</body>
</html>