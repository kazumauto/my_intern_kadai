<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>画像投稿</title>
</head>
<body>

    <h1>画像をアップロード</h1>

    <form action="/post/save" method="post" enctype="multipart/form-data">
        
        <p>画像を選択してください：</p>
        <input type="file" name="upload_file" required>
        <br><br>

        <button type="submit">アップロードする</button>

    </form>

    <p><a href="/home">戻る</a></p>

</body>
</html>