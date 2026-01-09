<!DOCTYPE html>
<html>
<head>
    <title>対決 - <?php echo $ranking['name']; ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js"></script>

    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; background-color: #f0f0f0; }
        h1 { margin-bottom: 10px; }
        .sub-title { color: #666; margin-bottom: 30px; }
        
        .battle-container { 
            display: flex; 
            justify-content: center; 
            gap: 40px; 
            align-items: center; 
            max-width: 900px; 
            margin: 0 auto; 
        }
        
        /* 2. anchorタグではなくdivになるので、pointerを指定 */
        .player-card { 
            background: white; 
            padding: 15px; 
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
            transition: transform 0.2s;
            cursor: pointer; /* クリックできるよ！という手アイコン */
            text-decoration: none;
            color: inherit;
            display: block; /* divになってもレイアウトを保つ */
        }
        .player-card:hover { transform: scale(1.05); border: 3px solid #e74c3c; }
        
        .player-card img { 
            max-width: 300px; 
            height: 300px; 
            object-fit: cover; 
            border-radius: 5px; 
        }
        .vs { font-size: 3em; font-weight: bold; color: #e74c3c; font-style: italic; }
    </style>
</head>
<body>

    <h1>⚔️ Battle! ⚔️</h1>
    <p class="sub-title"><?php echo $ranking['name']; ?> - どっちが好み？</p>

    <div class="battle-container">
        
        <div class="player-card" data-bind="click: function() { vote(player1(), player2()) }">
            
            <img data-bind="attr: { src: imageBaseUrl + player1().url }">
            
            <p>現在のレート: <span data-bind="text: player1().score"></span></p>
            
            <div style="background:#e74c3c; color:white; padding:5px; margin-top:5px; border-radius:5px;">こちらに投票！</div>
        </div>

        <div class="vs">VS</div>

        <div class="player-card" data-bind="click: function() { vote(player2(), player1()) }">
            
            <img data-bind="attr: { src: imageBaseUrl + player2().url }">
            
            <p>現在のレート: <span data-bind="text: player2().score"></span></p>
            
            <div style="background:#e74c3c; color:white; padding:5px; margin-top:5px; border-radius:5px;">こちらに投票！</div>
        </div>

    </div>

    <div style="margin-top: 50px;">
        <a href="/home/view/<?php echo $ranking['id']; ?>">ランキングに戻る</a>
    </div>

    <script>
        // 画像フォルダの場所（FuelPHPのpublic/assets/img/uploads/を想定）
        // 環境に合わせて適宜調整してください
        var imageBaseUrl = "/assets/img/uploads/";
        var voteApiUrl   = "<?php echo Uri::create('battle/vote'); ?>";

        function BattleViewModel() {
            var self = this;

            // PHPから渡された初期データを入れる
            self.player1 = ko.observable(<?php echo json_encode($player1); ?>);
            self.player2 = ko.observable(<?php echo json_encode($player2); ?>);

            // 投票関数
            self.vote = function(winner, loser) {
                console.log("投票: " + winner.url + " の勝ち");

                $.ajax({
                    url: voteApiUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        ranking_id: <?php echo $ranking['id']; ?>,
                        winner_id:  winner.image_id, // player1['image_id']に合わせる
                        loser_id:   loser.image_id
                    }
                })
                .done(function(response) {
                    // 成功したら、新しいペアに入れ替える
                    console.log("次のペア受信:", response);
                    
                    // 次の画像の配列 (0番目と1番目)
                    var next = response.next_pair;
                    
                    // データを更新すると、画面の画像とレートが一瞬で変わる
                    self.player1(next[0]);
                    self.player2(next[1]);
                })
                .fail(function(e) {
                    console.error("通信エラー", e);
                    alert("投票に失敗しました");
                });
            };
        }

        // 起動！
        ko.applyBindings(new BattleViewModel());
    </script>

</body>
</html>