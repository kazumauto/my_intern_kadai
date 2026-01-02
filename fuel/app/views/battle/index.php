<!DOCTYPE html>
<html>
<head>
    <title>対決 - <?php echo $ranking['name']; ?></title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; background-color: #f0f0f0; }
        h1 { margin-bottom: 10px; }
        .sub-title { color: #666; margin-bottom: 30px; }
        
        /* 対決エリアのレイアウト */
        .battle-container { 
            display: flex; 
            justify-content: center; 
            gap: 40px; 
            align-items: center; 
            max-width: 900px; 
            margin: 0 auto; 
        }
        
        /* 画像カードのデザイン */
        .player-card { 
            background: white; 
            padding: 15px; 
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
            transition: transform 0.2s;
            cursor: pointer;
        }
        .player-card:hover { transform: scale(1.05); border: 3px solid #e74c3c; }
        .player-card img { 
            max-width: 300px; 
            height: 300px; 
            object-fit: cover; /* 画像を正方形に切り抜く */
            border-radius: 5px; 
        }
        .vs { font-size: 3em; font-weight: bold; color: #e74c3c; font-style: italic; }
    </style>
</head>
<body>

    <h1>⚔️ Battle! ⚔️</h1>
    <p class="sub-title"><?php echo $ranking['name']; ?> - どっちが好み？</p>

    <div class="battle-container">
        
        <a href="/battle/vote/<?php echo $ranking['id']; ?>/<?php echo $player1['image_id']; ?>/<?php echo $player2['image_id']; ?>" class="player-card" style="text-decoration: none; color: inherit;">
            <?php echo Asset::img('uploads/' . $player1['url']); ?>
            <p>現在のレート: <?php echo $player1['score']; ?></p>
            <div style="background:#e74c3c; color:white; padding:5px; margin-top:5px; border-radius:5px;">こちらに投票！</div>
        </a>

        <div class="vs">VS</div>

        <a href="/battle/vote/<?php echo $ranking['id']; ?>/<?php echo $player2['image_id']; ?>/<?php echo $player1['image_id']; ?>" class="player-card" style="text-decoration: none; color: inherit;">
            <?php echo Asset::img('uploads/' . $player2['url']); ?>
            <p>現在のレート: <?php echo $player2['score']; ?></p>
            <div style="background:#e74c3c; color:white; padding:5px; margin-top:5px; border-radius:5px;">こちらに投票！</div>
        </a>

    </div>

    <div style="margin-top: 50px;">
        <a href="/home/view/<?php echo $ranking['id']; ?>">ランキングに戻る</a>
    </div>

</body>
</html>