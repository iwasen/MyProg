<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビランキングデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class ranking_navi_data_class extends data_class {
	public $param_id = 1;
	public $ml_member_num;
	public $ml_member_up;
	public $ml_total_remark;
	public $ml_page_view;
	public $ml_member_remark;
	public $blog_new_post;
	public $blog_page_view;
	public $blog_trackback;
	public $blog_comment;
	public $update_mode;
	public $ranking_ary = array();
	public $ranking;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db() {
		// ナビランキングパラメータ取得
		$sql = "SELECT * FROM t_navi_ranking_param WHERE nrp_param_id=$this->param_id";
		$fetch = $this->fetch_db($sql);
		if ($fetch) {
			$this->ml_member_num = $fetch->nrp_ml_member_num;
			$this->ml_member_up = $fetch->nrp_ml_member_up;
			$this->ml_total_remark = $fetch->nrp_ml_total_remark;
			$this->ml_page_view = $fetch->nrp_ml_page_view;
			$this->ml_member_remark = $fetch->nrp_ml_member_remark;
			$this->blog_new_post = $fetch->nrp_blog_new_post;
			$this->blog_page_view = $fetch->nrp_blog_page_view;
			$this->blog_trackback = $fetch->nrp_blog_trackback;
			$this->blog_comment = $fetch->nrp_blog_comment;
			$this->update_mode = $fetch->nrp_update_mode;
		}

		// ナビランキング取得
		$sql = "SELECT nvr_navi_page_id, nvr_ml_member_num, nvr_ml_member_up, nvr_ml_total_remark, nvr_ml_page_view, nvr_ml_member_remark, nvr_blog_new_post, nvr_blog_page_view, nvr_blog_trackback, nvr_blog_comment, nvp_title, nvp_blog_id"
				. " FROM t_navi_ranking"
				. " JOIN t_navi_page ON nvp_navi_page_id=nvr_navi_page_id"
				. " WHERE nvp_status=1 AND nvp_open_flag=1"
				. " ORDER BY nvr_rank_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$ranking = &$this->ranking_ary[$fetch->nvr_navi_page_id];

			$ranking->blog_id = $fetch->nvp_blog_id;
			$ranking->title = $fetch->nvp_title;
			$ranking->ml_member_num = $fetch->nvr_ml_member_num;
			$ranking->ml_member_up = $fetch->nvr_ml_member_up;
			$ranking->ml_total_remark = $fetch->nvr_ml_total_remark;
			$ranking->ml_page_view = $fetch->nvr_ml_page_view;
			$ranking->ml_member_remark = $fetch->nvr_ml_member_remark / 100;
			$ranking->blog_new_post = $fetch->nvr_blog_new_post;
			$ranking->blog_page_view = $fetch->nvr_blog_page_view;
			$ranking->blog_trackback = $fetch->nvr_blog_trackback;
			$ranking->blog_comment = $fetch->nvr_blog_comment;
			$ranking->index = $this->calc_index($ranking);
		}
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->ml_member_num = $_POST['ml_member_num'];
		$this->ml_member_up = $_POST['ml_member_up'];
		$this->ml_total_remark = $_POST['ml_total_remark'];
		$this->ml_page_view = $_POST['ml_page_view'];
		$this->ml_member_remark = $_POST['ml_member_remark'];
		$this->blog_new_post = $_POST['blog_new_post'];
		$this->blog_page_view = $_POST['blog_page_view'];
		$this->blog_trackback = $_POST['blog_trackback'];
		$this->blog_comment = $_POST['blog_comment'];
		$this->update_mode = $_POST['update_mode'];
		$this->ranking = $_POST['ranking'];
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		// ナビランキングパラメータ更新
		$rec['nrp_ml_member_num'] = sql_number($this->ml_member_num);
		$rec['nrp_ml_member_up'] = sql_number($this->ml_member_up);
		$rec['nrp_ml_total_remark'] = sql_number($this->ml_total_remark);
		$rec['nrp_ml_page_view'] = sql_number($this->ml_page_view);
		$rec['nrp_ml_member_remark'] = sql_number($this->ml_member_remark);
		$rec['nrp_blog_new_post'] = sql_number($this->blog_new_post);
		$rec['nrp_blog_page_view'] = sql_number($this->blog_page_view);
		$rec['nrp_blog_trackback'] = sql_number($this->blog_trackback);
		$rec['nrp_blog_comment'] = sql_number($this->blog_comment);
		$rec['nrp_update_mode'] = sql_number($this->update_mode);
		db_insert_update('t_navi_ranking_param', $rec, 'nrp_param_id', sql_number($this->param_id));

		// ナビランキング更新
		$ary = explode(':', $this->ranking);
		$rank_no = 1;
		foreach ($ary as $navi_page_id) {
			if ($navi_page_id) {
				$rec['nvr_rank_no'] = sql_number($rank_no);
				db_update('t_navi_ranking', $rec, "nvr_navi_page_id=$navi_page_id AND nvr_rank_no<>$rank_no");
				$rank_no++;
			}
		}

		db_commit_trans();
	}

	// ■DBから削除
	public function delete_db() {
	}

	// ■ランキング計算
	public function calc_ranking() {
		// 指数計算
		foreach (array_keys($this->ranking_ary) as $navi_page_id)
			$this->ranking_ary[$navi_page_id]->index = $this->calc_index($this->ranking_ary[$navi_page_id]);

		// 指数の大きい順にソート
		uasort($this->ranking_ary, create_function('$a, $b', 'return $b->index - $a->index;'));
	}

	// ■指数計算
	protected function calc_index($ranking) {
		return
				$this->ml_member_num * $ranking->ml_member_num +
				$this->ml_member_up * $ranking->ml_member_up +
				$this->ml_total_remark * $ranking->ml_total_remark +
				$this->ml_page_view * $ranking->ml_page_view +
				$this->ml_member_remark * $ranking->ml_member_remark +
				$this->blog_new_post * $ranking->blog_new_post +
				$this->blog_page_view * $ranking->blog_page_view +
				$this->blog_trackback * $ranking->blog_trackback +
				$this->blog_comment * $ranking->blog_comment;
	}
}
?>