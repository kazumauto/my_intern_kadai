<!DOCTYPE html>
<html>
<head>
    <title><?php echo $ranking['name']; ?></title>
    <h1>🏆 <?php echo $ranking['name']; ?> 🏆</h1>

    <div style="margin-bottom: 30px;">
        <a href="/battle/index/<?php echo $ranking['id']; ?>" 
           style="background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 5px;">
           ⚔️ このランキングで対決開始！
        </a>
    </div>
</head>
<body>
    <a href="/home">← 一覧に戻る</a>
    <h1><?php echo $ranking['name']; ?></h1>

    <table border="1" style="margin: 0 auto; border-collapse: collapse;">
        <?php foreach ($list as $row): ?>
            <tr>
                <td style="padding: 10px;">
                    <?php echo Asset::img('uploads/' . $row['url'], array('style' => 'max-width:100px;')); ?>
                </td>
                <td style="padding: 10px; font-weight: bold;">
                    <?php echo $row['score']; ?> Rate
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>