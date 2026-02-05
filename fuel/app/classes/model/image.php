<?php

class Model_Image extends Model
{
  // 画像保存（既存）
  public static function add_image($filename)
  {
    return DB::insert('images')->set(array(
      'url'        => $filename,
      'created_at' => time(),
      'updated_at' => time(),
    ))->execute();
  }

  // ★追加: 全ての画像を取得する
  public static function get_all_images()
  {
    return DB::select()
      ->from('images')
      ->order_by('created_at', 'desc') // 新しい順
      ->execute()
      ->as_array(); // 配列として結果を返す
  }

  // ★追加: ID指定で画像を1件取得（削除時のファイル名特定用）
  public static function get_image($id)
  {
    return DB::select()
      ->from('images')
      ->where('id', $id)
      ->execute()
      ->current();
  }

  // ★追加: 画像を削除する
  public static function delete_image($id)
  {
    return DB::delete('images')
      ->where('id', $id)
      ->execute();
  }

  // ★重複チェックメソッド
  // ハッシュ化されたファイル名がDBにあるか調べる
  public static function check_duplicate($filename)
  {
    $query = DB::select('id')
      ->from('images') // ※テーブル名が 'images' の場合
      ->where('url', '=', $filename)
      ->execute();

    // 1件以上あれば true (重複)
    return count($query) > 0;
  }
}
