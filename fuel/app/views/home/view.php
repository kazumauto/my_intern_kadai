<!DOCTYPE html>
<html>
<head>
    <title><?php echo $ranking['name']; ?></title>
    <style>
        /* 順位バッジの共通デザイン */
        .rank-badge {
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            border-radius: 50%;
            background-color: #7f8c8d; /* 4位以降はグレー */
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        /* 🥇 1位：ゴールド */
        .rank-1 {
            background: linear-gradient(45deg, #FFD700, #FDB931);
            border: 2px solid #5f3b3bff;
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.8);
            width: 45px; height: 45px; line-height: 45px; font-size: 20px; /* 1位だけ少し大きく */
        }

        /* 🥈 2位：シルバー */
        .rank-2 {
            background: linear-gradient(45deg, #E0E0E0, #BDBDBD);
            border: 1px solid #fff;
        }

        /* 🥉 3位：ブロンズ */
        .rank-3 {
            background: linear-gradient(45deg, #CD7F32, #A0522D);
            border: 1px solid #fff;
        }
    </style>
</head>
<body>
    <div style="text-align: left; padding: 10px;">
        <a href="/home">← 一覧に戻る</a>
    </div>

    <h1>🏆 <?php echo $ranking['name']; ?> 🏆</h1>

    <div style="margin-bottom: 30px; text-align: center;">
        <a href="/battle/index/<?php echo $ranking['id']; ?>" 
           style="background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 5px;">
           ⚔️ このランキングで対決開始！
        </a>
    </div>

    <table border="1" style="margin: 0 auto; border-collapse: collapse; width: 80%; max-width: 600px;">
        <tr style="background-color: #f9f9f9;">
            <th style="padding: 10px;">順位</th>
            <th style="padding: 10px;">画像</th>
            <th style="padding: 10px;">スコア</th>
        </tr>

        <?php foreach ($list as $index => $row): ?>
            <?php 
                // 0から始まるので+1して順位にする
                $rank = $index + 1; 
            ?>
            <tr>
                <td style="padding: 10px; text-align: center; width: 60px;">
                    <span class="rank-badge rank-<?php echo $rank; ?>">
                        <?php echo $rank; ?>
                    </span>
                </td>

                <td style="padding: 10px; text-align: center;">
                    <?php echo Asset::img('uploads/' . $row['url'], array('style' => 'max-width:100px; border-radius:5px;')); ?>
                </td>

                <td style="padding: 10px; font-weight: bold; text-align: center;">
                    <?php echo $row['score']; ?> Rate
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>