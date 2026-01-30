<!DOCTYPE html>
<html>
<head>
    <title><?php echo $ranking['name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <div class="mb-4">
            <a href="/home" class="btn btn-outline-secondary btn-sm">← 一覧に戻る</a>
        </div>

        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold">🏆 <?php echo $ranking['name']; ?> 🏆</h1>
        </div>

        <?php if ($ranking['user_id'] == Session::get('user_id')): ?>
            <div class="text-center mb-5">
                <a href="/battle/index/<?php echo $ranking['id']; ?>" class="btn btn-danger btn-lg px-5 py-3 shadow">
                    <span class="fs-4">⚔️ このランキングで対決開始！</span>
                </a>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 15%;">順位</th>
                                    <th class="text-center" style="width: 30%;">画像</th>
                                    <th class="text-center">スコア</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($list as $index => $row): ?>
                                    <?php 
                                        $rank = $index + 1; 
                                        
                                        // 順位に応じたデザインをPHP側で判定してクラスを決める
                                        // 1位: 黄色(warning), 2位: 灰色(secondary), 3位: 茶色の代用でアウトライン
                                        if ($rank == 1) {
                                            $badge_class = 'bg-warning text-dark border border-warning shadow-sm';
                                            $icon = '🥇';
                                            $row_class = 'table-warning'; // 行全体も少し黄色くする
                                        } elseif ($rank == 2) {
                                            $badge_class = 'bg-secondary text-white shadow-sm';
                                            $icon = '🥈';
                                            $row_class = '';
                                        } elseif ($rank == 3) {
                                            $badge_class = 'bg-dark text-white shadow-sm'; // 銅色の代用
                                            $icon = '🥉';
                                            $row_class = '';
                                        } else {
                                            $badge_class = 'bg-light text-secondary border';
                                            $icon = ''; // 4位以下は数字のみ
                                            $row_class = '';
                                        }
                                    ?>

                                    <tr class="<?php echo $row_class; ?>">
                                        
                                        <td class="text-center">
                                            <?php if ($rank <= 3): ?>
                                                <div class="fs-4"><?php echo $icon; ?></div>
                                            <?php else: ?>
                                                <span class="badge rounded-circle <?php echo $badge_class; ?>" style="width: 30px; height: 30px; line-height: 20px;">
                                                    <?php echo $rank; ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <img src="/assets/img/uploads/<?php echo $row['url']; ?>" class="img-thumbnail shadow-sm" style="max-width: 100px;">
                                        </td>

                                        <td class="text-center fw-bold fs-5">
                                            <?php echo $row['score']; ?> <span class="fs-6 text-muted">Rate</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
        
        <div class="mb-5"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>