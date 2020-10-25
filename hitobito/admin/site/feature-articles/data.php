<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 特集記事データ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class feature_articles_data_class extends data_class {
	public $article_id;
	public $status;
	public $regist_date;
	public $update_date;
	public $order;
	public $image_id;
	public $image_alt;
	public $link_url;
	public $link_page;
	public $start_date;
	public $end_date;
	public $description;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();

		$this->start_date['y'] = date('Y');
		$this->end_date['y'] = date('Y');
	}

	// ■DBからデータ読み込み
	public function read_db($article_id) {
		$sql = sprintf("SELECT * FROM t_feature_articles WHERE far_article_id=%s", sql_number($article_id));
		$fetch = $this->fetch_db($sql);

		$this->article_id = $fetch->far_article_id;
		$this->status = $fetch->far_status;
		$this->regist_date = $fetch->far_regist_date;
		$this->update_date = $fetch->far_update_date;
		$this->order = $fetch->far_order;
		$this->image_id = $fetch->far_image_id;
		$this->image_alt = $fetch->far_image_alt;
		$this->link_url = $fetch->far_link_url;
		$this->link_page = $fetch->far_link_page;
		$this->start_date = get_datepart($fetch->far_start_date);
		$this->end_date = get_datepart($fetch->far_end_date);
		$this->description = $fetch->far_description;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->article_id = $_POST['article_id'];
		$this->status = $_POST['status'];
		$this->order = $_POST['order'];
		$this->image_alt = $_POST['image_alt'];
		$this->link_url = $_POST['link_url'];
		$this->link_page = $_POST['link_page'];
		$this->start_date = $this->get_date('start_date');
		$this->end_date = $this->get_date('end_date');
		$this->description = $_POST['description'];
	}

	// ■DBに追加
	public function insert_db() {
		db_begin_trans();

		$rec['far_status'] = sql_number($this->status);
		$rec['far_order'] = sql_number($this->order);
		$rec['far_image_id'] = sql_number(save_image('image', $this->image_id));
		$rec['far_image_alt'] = sql_char($this->image_alt);
		$rec['far_link_url'] = sql_char($this->link_url);
		$rec['far_link_page'] = sql_number($this->link_page);
		$rec['far_start_date'] = sql_date2($this->start_date);
		$rec['far_end_date'] = sql_date2($this->end_date);
		$rec['far_description'] = sql_char($this->description);
		db_insert('t_feature_articles', $rec);
		$this->article_id = get_current_seq('t_feature_articles', 'far_article_id');

		$rec['fac_article_id'] = sql_number($this->article_id);
		db_insert('c_feature_articles', $rec);

		db_commit_trans();
	}

	// ■DBを更新
	public function update_db() {
		$rec['far_article_id'] = sql_number($this->article_id);
		$rec['far_status'] = sql_number($this->status);
		$rec['far_update_date'] = 'current_timestamp';
		$rec['far_order'] = sql_number($this->order);
		$rec['far_image_id'] = sql_number(save_image('image', $this->image_id));
		$rec['far_image_alt'] = sql_char($this->image_alt);
		$rec['far_link_url'] = sql_char($this->link_url);
		$rec['far_link_page'] = sql_number($this->link_page);
		$rec['far_start_date'] = sql_date2($this->start_date);
		$rec['far_end_date'] = sql_date2($this->end_date);
		$rec['far_description'] = sql_char($this->description);
		db_update('t_feature_articles', $rec, sprintf("far_article_id=%s", sql_number($this->article_id)));
	}

	// ■DBから削除
	public function delete_db() {
		db_begin_trans();

		db_delete('t_feature_articles', sprintf("far_article_id=%s", sql_number($this->article_id)));
		db_delete('c_feature_articles', sprintf("fac_article_id=%s", sql_number($this->article_id)));

		delete_image($this->image_id);

		db_commit_trans();
	}
}
?>