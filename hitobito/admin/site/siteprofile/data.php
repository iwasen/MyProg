<?php
/**
 * ひとびと･net 事務局ページ
 *
 * サイトプロファイルデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class site_profile_data_class extends data_class {
	public $date;
	public $navi_num;
	public $member_num;
	public $letter_member;
	public $blog_num;
	public $room_num;
	public $article_num;
	public $remark_num;
	public $remark_count;
	public $mail_num;
	public $ram_rate;
	public $page_view;
	public $visitor_num;
	public $click_num;
	public $trackback_num;
	public $comment_num;
	public $navi_page_ary = array();

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($date) {
		$date = sql_date2($date);

		$sql = "SELECT * FROM t_site_profile WHERE stp_date=$date";
		$fetch = $this->fetch_db($sql);
		if ($fetch) {
			$this->navi_num = $fetch->stp_navi_num;
			$this->member_num = $fetch->stp_member_num;
			$this->letter_member = $fetch->stp_letter_member;
			$this->blog_num = $fetch->stp_blog_num;
			$this->room_num = $fetch->stp_room_num;
			$this->article_num = $fetch->stp_article_num;
			$this->remark_num = $fetch->stp_remark_num;
			$this->remark_count = $fetch->stp_remark_count;
			$this->mail_num = $fetch->stp_mail_num;
			$this->ram_rate = $fetch->stp_ram_rate;
			$this->page_view = $fetch->stp_page_view;
			$this->visitor_num = $fetch->stp_visitor_num;
			$this->click_num = $fetch->stp_click_num;
			$this->trackback_num = $fetch->stp_trackback_num;
			$this->comment_num = $fetch->stp_comment_num;
		}

		$sql = "SELECT snp_channel_id, snp_navi_page_num, chn_channel_name"
				. " FROM t_site_navi_page"
				. " JOIN m_channel ON chn_channel_id=snp_channel_id"
				. " WHERE snp_date=$date"
				. " ORDER BY chn_order";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$this->navi_page_ary[$fetch->snp_channel_id]->channel_name = $fetch->chn_channel_name;
			$this->navi_page_ary[$fetch->snp_channel_id]->navi_page_num = $fetch->snp_navi_page_num;
		}
	}

	// ■フォームからデータ読み込み
	public function read_form() {
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
	}

	// ■DBから削除
	public function delete_db() {
	}
}
?>