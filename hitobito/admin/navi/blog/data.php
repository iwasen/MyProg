<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビページ（Blog）登録情報編集ページ
 *
 *
 * @package
 * @author
 * @version
 */

class ml_data_class extends data_class {
	public $navi_page_id;
	public $blog_id;
	public $channel_id;
	public $sub_channel_id;
	public $navi_page_title;
	public $navi_id;
	public $open_date;
	public $open_flag;
	public $banner_url;
	public $regist_date;
	public $udpate_date;
	public $kcom_category_id;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■サブチャネル選択
	public function chg_select_subchannel($default, $selected, $channel_id) {
		if ($default)
			$tag = option_tag('', $selected, $default);

		$sql = sprintf("SELECT sch_subchannel_id, sch_subchannel_name FROM m_sub_channel WHERE sch_status = 1 AND sch_channel_id = %s ORDER BY sch_order", sql_number($channel_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$tag .= option_tag($fetch->sch_subchannel_id, $selected, $fetch->sch_subchannel_name);
		}

		return $tag;
	}

	// ■DBからデータ読み込み
	public function read_db($blog_id) {
		$sql = sprintf("SELECT * FROM t_navi_page LEFT JOIN t_blog ON nvp_blog_id = blg_blog_id"
			. " LEFT JOIN m_sub_channel ON nvp_subchannel_id = sch_subchannel_id"
			. " LEFT JOIN t_banner ON nvp_banner_id=bnr_banner_id"
			. " WHERE blg_blog_id = %s", sql_number($blog_id));
		$fetch = $this->fetch_db($sql);

		$this->navi_page_id = $fetch->nvp_navi_page_id;
		$this->blog_id = $fetch->blg_blog_id;
		$this->channel_id = $fetch->nvp_channel_id;
		$this->sub_channel_id = $fetch->nvp_subchannel_id;
		$this->navi_page_title = $fetch->nvp_title;
		$this->navi_id = $fetch->nvp_navi_id;
		$this->open_date = get_datepart($fetch->nvp_open_date);
		$this->open_flag = $fetch->nvp_open_flag;
		$this->banner_id = $fetch->nvp_banner_id;
		$this->banner_image_url = $fetch->bnr_image_url;
		$this->banner_link_url = $fetch->bnr_link_url;
		$this->regist_date = $fetch->blg_regist_date;
		$this->udpate_date = $fetch->blg_update_date;

		// BlogUU/BlogPV
		$sql = sprintf("SELECT COUNT(*) AS pv_num, COUNT(DISTINCT pvl_ip_addr) AS uu_num, MAX(pvl_date) AS last_date"
			. " FROM l_page_view JOIN t_blog_article ON pvl_pv_id=bla_pv_id"
			. " WHERE bla_blog_id=%s"
			, sql_number($blog_id));
		$fetch = $this->fetch_db($sql);
		$this->pv_num = $fetch->pv_num;
		$this->uu_num = $fetch->uu_num;
		$this->last_date = $fetch->last_date;

		if ($this->banner_id) {
			// バナークリック数
			$sql = "SELECT bnc_click_count FROM c_banner_click WHERE bnc_banner_id=$this->banner_id";
			$this->banner_click_num = db_fetch1($sql);

			// 最終クリック日時
			$sql = "SELECT max(bnl_date) FROM l_banner_click WHERE bnl_banner_id=$this->banner_id";
			$this->banner_click_last_date = db_fetch1($sql);
		}

		// ナビ記事数
		$sql = sprintf("SELECT COUNT(*) AS article_num, MAX(bla_date) AS last_date FROM t_blog_article WHERE bla_blog_id=%s", sql_number($blog_id));
		$fetch = $this->fetch_db($sql);
		$this->article_num = $fetch->article_num;
		$this->article_last_date = $fetch->last_date;

		// トラックバック数
		$sql = sprintf("SELECT COUNT(*) AS trackback_num, MAX(blt_date) AS last_date"
			. " FROM t_blog_trackback LEFT JOIN t_blog_article ON blt_article_id=bla_article_id"
			. " WHERE bla_blog_id=%s"
			, sql_number($blog_id));
		$fetch = $this->fetch_db($sql);
		$this->trackback_num = $fetch->trackback_num;
		$this->trackback_last_date = $fetch->last_date;

		// コメント数
		$sql = sprintf("SELECT COUNT(*) AS comment_num, MAX(blo_date) AS last_date"
			. " FROM t_blog_comment LEFT JOIN t_blog_article ON blo_article_id=bla_article_id"
			. " WHERE bla_blog_id=%s"
			, sql_number($blog_id));
		$fetch = $this->fetch_db($sql);
		$this->comment_num = $fetch->comment_num;
		$this->comment_last_date = $fetch->last_date;

		// pickup数
		$sql = sprintf("SELECT COUNT(*) AS pickup_num, MAX(bla_date) AS last_date FROM t_blog_article WHERE bla_pickup > 0 AND  bla_blog_id=%s", sql_number($blog_id));
		$fetch = $this->fetch_db($sql);
		$this->pickup_num = $fetch->pickup_num;
		$this->pickup_last_date = $fetch->last_date;


		// ナビセレクト数
		$sql = sprintf("SELECT COUNT(*) AS navi_seelct_num, MAX(bla_date) AS last_date FROM t_blog_article WHERE bla_navi_select_flag > 0 AND  bla_blog_id=%s", sql_number($blog_id));
		$fetch = $this->fetch_db($sql);
		$this->navi_select_num = $fetch->navi_seelct_num;
		$this->navi_select_last_date = $fetch->last_date;

		// ナビアンケート数
		$sql = sprintf("SELECT COUNT(*) AS enquete_num, MAX(nen_date) AS last_date"
			. " FROM t_navi_enquete LEFT JOIN t_navi_page ON nen_navi_page_id=nvp_navi_page_id"
			. " WHERE nvp_blog_id=%s"
			, sql_number($blog_id));
		$fetch = $this->fetch_db($sql);
		$this->enquete_num = $fetch->enquete_num;
		$this->enquete_last_date = $fetch->last_date;

		// ナビアンケート回答数
		$sql = sprintf("SELECT COUNT(*) AS ans_num, MAX(nel_date) AS last_date"
			. " FROM l_navi_enq_answer LEFT JOIN t_navi_enquete ON nel_enquete_id=nen_enquete_id"
			. " LEFT JOIN t_navi_page ON nen_navi_page_id=nvp_navi_page_id"
			. " WHERE nvp_blog_id=%s"
			, sql_number($blog_id));
		$fetch = $this->fetch_db($sql);
		$this->ans_num = $fetch->ans_num;
		$this->ans_last_date = $fetch->last_date;

		// メルマガ数
		$sql = sprintf("SELECT mmn_publish_cnt,mmn_reader_cnt,mmn_last_publish_date"
			. " FROM c_melmaga_count LEFT JOIN t_navi_page ON mmn_melmaga_id=nvp_melmaga_id"
			. " WHERE nvp_blog_id=%s"
			, sql_number($blog_id));
		$fetch = $this->fetch_db($sql);
		$this->melmaga_num = $fetch->mmn_publish_cnt;
		$this->reader_num = $fetch->mmn_reader_cnt;
		$this->melmaga_last_date = $fetch->mmn_last_publish_date;

		// 最終読者
		$sql = sprintf("SELECT MAX(mmm_regist_date)"
			. " FROM t_melmaga_member"
			. " JOIN t_navi_page ON mmm_melmaga_id=nvp_melmaga_id"
			. " WHERE nvp_blog_id=%s"
			, sql_number($blog_id));
		$this->reader_last_date = db_fetch1($sql);
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->navi_page_id = $_POST['navi_page_id'];
		$this->blog_id = $_POST['blog_id'];
		$this->channel_id = $_POST['channel_id'];
		$this->sub_channel_id = $_POST['sub_channel_id'];
		$this->navi_page_title = $_POST['navi_page_title'];
		$this->navi_id = $_POST['navi_id'];
		$this->open_date = $this->get_date('open_date');
		$this->open_flag = $_POST['open_flag'];
		$this->banner_id = $_POST['banner_id'];
		$this->banner_image_url = $_POST['banner_image_url'];
		$this->banner_link_url = $_POST['banner_link_url'];
		$this->kcom_category_id = $_POST['kcom_category_id'];
	}

	// ■データチェック
	public function check() {
		return true;
	}

/*
	// ■DBに追加
	public function insert_db() {
		db_insert('t_member', $rec);
	}
*/

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		if ($this->banner_id) {
			// バナー情報更新
			$rec['bnr_image_url'] = sql_char($this->banner_image_url);
			$rec['bnr_link_url'] = sql_char($this->banner_link_url);
			db_update('t_banner',$rec , "bnr_banner_id=$this->banner_id");
		} else {
			// バナー情報追加
			$rec['bnr_image_url'] = sql_char($this->banner_image_url);
			$rec['bnr_link_url'] = sql_char($this->banner_link_url);
			db_insert('t_banner',$rec);

			$this->banner_id = get_current_seq('t_banner', 'bnr_banner_id');
		}

		// ナビページクチコミカテゴリ更新
		$this->write_kcom_category();

		// ナビページ情報更新
		$rec['nvp_navi_id'] = sql_number($this->navi_id);
		$rec['nvp_title'] = sql_char($this->navi_page_title);
		$rec['nvp_channel_id'] = sql_number($this->channel_id);
		$rec['nvp_subchannel_id'] = sql_number($this->sub_channel_id);
		$rec['nvp_open_date'] = sql_date2($this->open_date);
		$rec['nvp_open_flag'] = sql_number($this->open_flag);
		$rec['nvp_banner_id'] = sql_number($this->banner_id);
		db_update('t_navi_page',$rec , "nvp_blog_id=$this->blog_id");

		// Blog情報更新
		$rec['blg_update_date'] = sql_date(now);
		db_update('t_blog',$rec , "blg_blog_id=$this->blog_id");

		db_commit_trans();
	}
/*
	// ■DBから削除
	public function delete_db() {
		db_delete('t_member', "mbr_member_id=$this->member_id");
	}
*/

	// ■クチコミWEBカテゴリを更新
	protected function write_kcom_category() {
		db_delete('t_navi_kcom', "nvk_navi_page_id=$this->navi_page_id");

		$ary = explode("\t", $this->kcom_category_id);
		foreach ($ary as $tmp) {
			if ($tmp != '') {
				$category_id = explode(':', $tmp);
				$rec['nvk_navi_page_id'] = sql_number($this->navi_page_id);
				$rec['nvk_kcom_l1'] = sql_number($category_id[0]);
				$rec['nvk_kcom_l2'] = sql_number($category_id[1]);
				$rec['nvk_kcom_l3'] = sql_number($category_id[2]);
				$rec['nvk_kcom_l4'] = sql_number($category_id[3]);
				$rec['nvk_kcom_l5'] = sql_number($category_id[4]);
				db_insert('t_navi_kcom', $rec);
			}
		}
	}

}
?>