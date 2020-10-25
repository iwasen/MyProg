<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 人気記事ランキングデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class ranking_article_data_class extends data_class {
	public $param_id = 1;
	public $page_view;
	public $trackback;
	public $comment;
	public $update_period;
	public $ranking_ary = array();
	public $adjust_num = 14;
	public $adjust_ary = array();

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($channel_id) {
		// 人気記事ランキングパラメータ取得
		$sql = "SELECT * FROM t_blog_ranking_param WHERE brp_param_id=$this->param_id";
		$fetch = $this->fetch_db($sql);
		if ($fetch) {
			$this->page_view = $fetch->brp_page_view;
			$this->trackback = $fetch->brp_trackback;
			$this->comment = $fetch->brp_comment;
			$this->update_period = $fetch->brp_update_period;
		}

		// 人気記事ランキング取得
		$sql = "SELECT blr_article_id, chn_channel_name, nvp_title, bla_title, bla_date, blr_page_view, blr_trackback, blr_comment, blr_index, bla_keisai_flag, bla_blog_id"
				. " FROM t_blog_ranking"
				. " JOIN t_blog_article ON bla_article_id=blr_article_id"
				. " JOIN t_navi_page ON nvp_blog_id=bla_blog_id"
				. " JOIN m_channel ON chn_channel_id=blr_channel_id"
				. " WHERE bla_status=1 AND bla_open_flag=1" . ($channel_id ? " AND blr_channel_id=$channel_id" : '')
				. " ORDER BY blr_index DESC"
				. " LIMIT 20";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$ranking = &$this->ranking_ary[$fetch->blr_article_id];

			$ranking->article_id = $fetch->blr_article_id;
			$ranking->channel_name = $fetch->chn_channel_name;
			$ranking->navi_page_title = $fetch->nvp_title;
			$ranking->article_title = $fetch->bla_title;
			$ranking->date = $fetch->bla_date;
			$ranking->page_view = $fetch->blr_page_view;
			$ranking->trackback = $fetch->blr_trackback;
			$ranking->comment = $fetch->blr_comment;
			$ranking->index = $fetch->blr_index;
			$ranking->keisai_flag = $fetch->bla_keisai_flag;
			$ranking->blog_id = $fetch->bla_blog_id;
		}
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		// 指数設定
		$this->page_view = $_POST['page_view'];
		$this->trackback = $_POST['trackback'];
		$this->comment = $_POST['comment'];
		$this->update_period = $_POST['update_period'];

		// 掲載フラグ
		foreach ($this->ranking_ary as $ranking)
			$ranking->keisai_flag = (is_array($_POST['keisai']) && in_array($ranking->article_id, $_POST['keisai'])) ? 0 : 1;

		// 指数修正
		for ($i = 0; $i < $this->adjust_num; $i++) {
			$adjust = &$this->adjust_ary[$i];

			$adjust->navi_page_id = $_POST['navi_page_id'][$i];
			$adjust->article_id = $adjust->navi_page_id ? $_POST['article_id'][$i] : '';
			$adjust->adjust = $adjust->article_id ? $_POST['adjust'][$i] : '';

			if ($adjust->article_id) {
				$sql = "SELECT blr_page_view, blr_trackback, blr_comment"
						. " FROM t_blog_ranking"
						. " WHERE blr_article_id=$adjust->article_id";
				$fetch = $this->fetch_db($sql);
				$adjust->page_view = $fetch->blr_page_view;
				$adjust->trackback = $fetch->blr_trackback;
				$adjust->comment = $fetch->blr_comment;
				$adjust->index = $this->calc_index($adjust);
			} else {
				$adjust->page_view = '';
				$adjust->trackback = '';
				$adjust->comment = '';
				$adjust->index = '';
				$adjust->adjust = '';
			}
		}
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		// 人気記事ランキングパラメータ更新
		$rec['brp_page_view'] = sql_number($this->page_view);
		$rec['brp_trackback'] = sql_number($this->trackback);
		$rec['brp_comment'] = sql_number($this->comment);
		$rec['brp_update_period'] = sql_number($this->update_period);
		db_insert_update('t_blog_ranking_param', $rec, 'brp_param_id', sql_number($this->param_id));

		// 人気記事ランキング更新
		foreach ($this->ranking_ary as $ranking) {
			$index = $this->calc_index($ranking);

			// 指数更新
			$rec['blr_index'] = sql_number($index);
			db_update('t_blog_ranking', $rec, sprintf("blr_article_id=%s AND blr_index<>%s", sql_number($ranking->article_id), sql_number($index)));

			// 掲載フラグ更新
			$rec['bla_keisai_flag'] = sql_number($ranking->keisai_flag);
			db_update('t_blog_article', $rec, sprintf("bla_article_id=%s AND bla_keisai_flag<>%s", sql_number($ranking->article_id), sql_number($ranking->keisai_flag)));
		}

		db_commit_trans();
	}

	// ■DBから削除
	public function delete_db() {
	}

	// ■ランキング計算
	public function calc_ranking() {
		// 指数計算
		foreach (array_keys($this->ranking_ary) as $article_id)
			$this->ranking_ary[$article_id]->index = $this->calc_index($this->ranking_ary[$article_id]);

		// ソート
		uasort($this->ranking_ary, create_function('$a, $b', 'return $b->index - $a->index;'));
	}

	// ■指数計算
	protected function calc_index($ranking) {
		foreach ($this->adjust_ary as $adjust) {
			if ($ranking->article_id == $adjust->article_id && $adjust->adjust != '')
				return $adjust->adjust;
		}

		return
				$this->page_view * $ranking->page_view +
				$this->trackback * $ranking->trackback +
				$this->comment * $ranking->comment;
	}
}
?>