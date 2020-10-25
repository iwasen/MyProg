<?php
/**
 * ひとびと･net 共通ファイル
 *
 * データ処理共通クラス
 *
 *
 * @package
 * @author
 * @version
 */

class data_class {
	public $new = true;
	public $action;
	public $button;

	// ■コンストラクタ
	public function __construct() {
	}

	// ■DBからデータ読み込み
	protected function fetch_db($sql) {
		$this->new = false;

		$result = db_exec($sql);
		return pg_num_rows($result) ? pg_fetch_object($result, 0) : null;
	}

	// ■処理名取得
	public function action_db($action) {
		switch ($action) {
		case 'new':
			$this->insert_db();
			$this->action = '登録';
			break;
		case 'update':
			$this->update_db();
			$this->action = '更新';
			break;
		case 'delete':
			$this->delete_db();
			$this->action = '削除';
			break;
		default:
			$func = "action_$action";
			$this->action = $this->$func();
			break;
		}
	}

	// ■日付をフォームから取得
	protected function get_date($base_name) {
		if (isset($_POST["{$base_name}_y"]))
			$date['y'] = $_POST["{$base_name}_y"];

		if (isset($_POST["{$base_name}_m"]))
			$date['m'] = $_POST["{$base_name}_m"];

		if (isset($_POST["{$base_name}_d"]))
			$date['d'] = $_POST["{$base_name}_d"];

		if (isset($_POST["{$base_name}_h"]))
			$date['h'] = $_POST["{$base_name}_h"];

		if (isset($_POST["{$base_name}_n"]))
			$date['n'] = $_POST["{$base_name}_n"];

		if (isset($_POST["{$base_name}_s"]))
			$date['s'] = $_POST["{$base_name}_s"];

		return $date;
	}

	// ■フォームから複数選択肢をカンマ区切りで取得
	protected function get_multi_select($ary) {
		if (is_array($ary))
			return join(',', $ary);
		else
			return '';
	}
}
?>