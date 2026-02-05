<!DOCTYPE html>
<html>

<head>
  <title>対決 - <?php echo $ranking['name']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js"></script>
</head>

<body class="bg-light text-center">

  <div class="container py-5">

    <h1 class="display-5 fw-bold mb-2">⚔️ Battle! ⚔️</h1>
    <p class="text-muted mb-5"><?php echo $ranking['name']; ?> - どっちが好み？</p>

    <div class="row justify-content-center align-items-center g-4">

      <div class="col-md-5">

        <!-- function(){}で囲むと、クリックした瞬間に実行される -->
        <div class="card shadow border-0 h-100" style="cursor: pointer;"
          data-bind="click: function() { vote(player1(), player2()) }">

          <div class="ratio ratio-1x1">
            <!-- attr:HTMLの「属性（Attribute）」を操作します、という宣言。
                            src: その中でも「src（画像の場所）」という属性を操作します、という指定。
                            player1 は ko.observable -->
            <img class="card-img-top object-fit-cover" data-bind="attr: { src: imageBaseUrl + player1().url }">
          </div>

          <div class="card-body">
            <p class="card-text fw-bold mb-2">
              現在のレート: <span data-bind="text: player1().score"></span>
            </p>
            <span class="badge bg-danger rounded-pill px-3 py-2">こちらに投票！</span>
          </div>
        </div>

      </div>

      <div class="col-md-auto">
        <div class="display-3 text-danger fw-bold fst-italic">VS</div>
      </div>

      <div class="col-md-5">

        <div class="card shadow border-0 h-100" style="cursor: pointer;"
          data-bind="click: function() { vote(player2(), player1()) }">

          <div class="ratio ratio-1x1">
            <img class="card-img-top object-fit-cover" data-bind="attr: { src: imageBaseUrl + player2().url }">
          </div>

          <div class="card-body">
            <p class="card-text fw-bold mb-2">
              現在のレート: <span data-bind="text: player2().score"></span>
            </p>
            <span class="badge bg-danger rounded-pill px-3 py-2">こちらに投票！</span>
          </div>
        </div>

      </div>

    </div>

    <div class="mt-5">
      <a href="/home/view/<?php echo $ranking['id']; ?>" class="btn btn-outline-secondary">ランキングに戻る</a>
    </div>

  </div>

  <script>
    // 画像フォルダの場所
    const imageBaseUrl = "/assets/img/uploads/";
    const voteApiUrl = "<?php echo Uri::create('battle/vote'); ?>";

    function BattleViewModel() {
      const self = this;

      // PHPから渡された初期データを入れる
      //1.json_encode は、「PHPのデータを、JSでも読める形式（JSON文字列）に変換する関数」
      //2.echoで、変換後のデータが直接書き込まれる。
      //3.ko.observableで監視機能を付ける。
      self.player1 = ko.observable(<?php echo json_encode($player1); ?>);
      self.player2 = ko.observable(<?php echo json_encode($player2); ?>);

      // 投票関数
      self.vote = function(winner, loser) {
        console.log("投票: " + winner.url + " の勝ち");

        //ajax通信をする
        $.ajax({
            //宛先
            url: voteApiUrl,
            //通信方法
            type: 'POST',
            //返信の形式
            dataType: 'json',
            //荷物
            data: {
              ranking_id: <?php echo $ranking['id']; ?>,
              winner_id: winner.image_id,
              loser_id: loser.image_id
            }
          })
          //ajaxが成功したら
          // responseにはサーバーからの返信が入っている。
          .done(function(response) {
            // 成功したら、新しいペアに入れ替える
            console.log("次のペア受信:", response);

            //responseのnext_pair。ドット記法
            const next = response.next_pair;

            self.player1(next[0]);
            self.player2(next[1]);
          })
          //ajaxが失敗したら
          .fail(function(e) {
            console.error("通信エラー", e);
            alert("投票に失敗しました");
          });
      };
    }

    //起動コマンド
    ko.applyBindings(new BattleViewModel());
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>