<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビセレクト最新投稿データ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class new_selection_data_class extends data_class {
	public $navi_select_flag;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($channel_id) {
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->navi_select_flag = isset($_POST['navi_select_flag']) ? $_POST['navi_select_flag'] : array();
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		foreach ($this->navi_select_flag as $article_id) {
			if ($article_id) {
				$rec['bla_navi_select_flag'] = sql_number(0);
				db_update('t_blog_article', $rec, "bla_article_id=$article_id");
			}
		}
	}

	// ■DBから削除
	public function delete_db() {
	}
}
?>