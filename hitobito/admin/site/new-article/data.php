<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビ記事最新投稿データ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class new_article_data_class extends data_class {
	public $keisai_flag;
	public $article_id;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($channel_id) {
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->keisai_flag = isset($_POST['keisai_flag']) ? $_POST['keisai_flag'] : array();
		$this->article_id = $_POST['article_id'];
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		$article_id_ary = explode(':', $this->article_id);
		if (is_array($article_id_ary)) {
			foreach ($article_id_ary as $article_id) {
				if ($article_id) {
					$keisai_flag = in_array($article_id, $this->keisai_flag) ? 0 : 1;
					$rec['bla_keisai_flag'] = sql_number($keisai_flag);
					db_update('t_blog_article', $rec, "bla_article_id=$article_id AND bla_keisai_flag<>$keisai_flag");
				}
			}
		}
	}

	// ■DBから削除
	public function delete_db() {
	}
}
?>