<?php

class Model_Ranking extends Model
{
  // ランキングを新規作成するメソッド
  public static function create_ranking($user_id, $name)
  {
    // 1. rankigs テーブルに大会名を保存
    // DB::insert は、成功すると [id, row_count] の配列を返すので、idだけ受け取ります
    list($ranking_id, $rows) = DB::insert('rankings')->set(array(
      'user_id' => $user_id,
      'name'    => $name,
    ))->execute();

    // ▼▼▼ 追加：Configファイルから初期レートを読み込む ▼▼▼
    // 第一引数：ファイル名、第二引数：true（グループ化して名前の衝突を防ぐ）
    Config::load('battle', true);

    // 値を取得（もし設定ファイルが無かった時のために、第2引数で保険の 1500 を入れておくとプロっぽいです）
    $default_rate = Config::get('battle.default_rate', 1500);
    // ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

    // 2. 既存のすべての画像をこのランキングに登録（初期スコア1500）
    // ※Model_Imageのメソッドを再利用します
    $images = Model_Image::get_all_images();

    //idはオートインクリメントなので、書いてはいけない。
    foreach ($images as $img) {
      DB::insert('rates')->set(array(
        'ranking_id' => $ranking_id,
        'image_id'   => $img['id'],
        // ▼ 修正：直接 1500 と書かず、変数を使う
        'score'      => $default_rate,
      ))->execute();
    }

    //createするメソッドは、作成したもののidや実体を返すのが定石。
    return $ranking_id;
  }

  // 全てのランキングを取得
  public static function get_all()
  {
    return DB::select()->from('rankings')->execute()->as_array();
  }

  // ★追加：特定のユーザーのランキングだけを取得するメソッド
  public static function get_by_user($user_id)
  {
    return DB::select()
      ->from('rankings')             // テーブル名
      ->where('user_id', $user_id)   // ★ここで「自分のID」で絞り込む！
      ->order_by('created_at', 'desc') // 新しい順
      ->execute()
      ->as_array();
  }

  // ★追加: IDを指定してランキング情報を1件取得
  public static function get_by_id($id)
  {
    return DB::select()
      ->from('rankings')
      ->where('id', $id)
      ->execute()
      ->current();
  }

  // ランキングとその関連データを削除する
  public static function delete_ranking($ranking_id)
  {
    // 1. まず、このランキングに関連するスコア(rates)を全削除
    // (これを忘れると、データベースにゴミが残ります)
    DB::delete('rates')
      ->where('ranking_id', $ranking_id)
      ->execute();

    // 2. 最後に、ランキング本体(rankings)を削除
    return DB::delete('rankings')
      ->where('id', $ranking_id)
      ->execute();
  }
}
