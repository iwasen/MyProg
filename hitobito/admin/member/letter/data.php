<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ひとびと便り情報処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class letter_data_class extends data_class {
	public $letter_id;
	public $status = 1;
	public $job_name;
	public $target = 1;
	public $mail_format = 1;
	public $send_date;
	public $enquete_date;
	public $subject;
	public $from_addr;
	public $header;
	public $body;
	public $footer;
	public $enq_title;
	public $enq_question;
	public $enq_nickname;
	public $enq_comment;
	public $enq_option = array();

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();

		// 発信開始日時、アンケート〆切日時の年を現在年にセット
		$this->send_date['y'] = date('Y');
		$this->enquete_date['y'] = date('Y');
	}

	// ■DBからデータ読み込み
	public function read_db($letter_id) {
		$sql = sprintf("SELECT * FROM t_hitobito_letter WHERE hbl_letter_id = %s", sql_number($letter_id));
		$fetch = $this->fetch_db($sql);

		$this->letter_id = $fetch->hbl_letter_id;
		$this->job_name = $fetch->hbl_job_name;
		$this->status = $fetch->hbl_status;
		$this->send_date = get_datepart($fetch->hbl_send_date);
		$this->enquete_date = get_datepart($fetch->hbl_enquete_date);
		$this->subject = $fetch->hbl_subject;
		$this->header = $fetch->hbl_header;
		$this->body = $fetch->hbl_body;
		$this->footer = $fetch->hbl_footer;
		$this->enq_title = $fetch->hbl_enq_title;
		$this->enq_question = $fetch->hbl_enq_question;
		$this->enq_nickname = $fetch->hbl_enq_nickname;
		$this->enq_comment = $fetch->hbl_enq_comment;

		$sql = sprintf("SELECT hlo_option_no,hlo_option_text FROM t_hitobito_letter_option WHERE hlo_letter_id = %s", sql_number($letter_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$this->enq_option[$fetch->hlo_option_no] = $fetch->hlo_option_text;
		}
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->letter_id = $_POST['letter_id'];
		$this->job_name = $_POST['job_name'];
		$this->send_date = $this->get_date('send_date');
		$this->enquete_date = $this->get_date('enquete_date');
	}

	// ■DBに追加
	public function insert_db() {
		db_begin_trans();

		// ひとびと便り登録
		$rec['hbl_job_name'] = sql_char($this->job_name);
		$rec['hbl_target'] = sql_number($this->target);
		$rec['hbl_mail_format'] = sql_char($this->mail_format);
		$rec['hbl_send_date'] = sql_date2($this->send_date);
		$rec['hbl_enquete_date'] = sql_date2($this->enquete_date);
		db_insert('t_hitobito_letter', $rec);
		$this->letter_id = get_current_seq('t_hitobito_letter', 'hbl_letter_id');

		// ひとびと便り回答・クリック数
		$rec['hlc_letter_id'] = $this->letter_id;
		db_insert('c_hitobito_letter_count', $rec);

		db_commit_trans();
	}

	// ■DBを更新
	public function update_db($update_option = false) {
		db_begin_trans();

		$this->set_click_counter();

		$rec['hbl_job_name'] = sql_char($this->job_name);
		$rec['hbl_target'] = sql_number($this->target);
		$rec['hbl_mail_format'] = sql_char($this->mail_format);
		$rec['hbl_subject'] = sql_char($this->subject);
		$rec['hbl_from_addr'] = sql_char($this->from_addr);
		$rec['hbl_header'] = sql_char($this->header);
		$rec['hbl_body'] = sql_char($this->body);
		$rec['hbl_footer'] = sql_char($this->footer);
		$rec['hbl_send_date'] = sql_date2($this->send_date);
		$rec['hbl_enquete_date'] = sql_date2($this->enquete_date);
		$rec['hbl_enq_title'] = sql_char($this->enq_title);
		$rec['hbl_enq_question'] = sql_char($this->enq_question);
		$rec['hbl_enq_nickname'] = sql_number($this->enq_nickname);
		$rec['hbl_enq_comment'] = sql_number($this->enq_comment);
		db_update('t_hitobito_letter', $rec, "hbl_letter_id=$this->letter_id");

		if ($update_option) {
			db_delete('t_hitobito_letter_option', "hlo_letter_id=$this->letter_id");

			foreach ($this->enq_option as $option_no => $option_text) {
				if ($option_text != '') {
					$rec['hlo_letter_id'] = sql_number($this->letter_id);
					$rec['hlo_option_no'] = sql_number($option_no);
					$rec['hlo_option_text'] = sql_char($option_text);
					db_insert('t_hitobito_letter_option', $rec);
				}
			}
		}

		db_commit_trans();
	}

	// ■DBから削除
	public function delete_db() {
		db_begin_trans();

		db_delete('t_hitobito_letter', "hbl_letter_id=$this->letter_id");
		db_delete('c_hitobito_letter_count', "hlc_letter_id=$this->letter_id");
		db_delete('t_hitobito_letter_option', "hlo_letter_id=$this->letter_id");

		db_commit_trans();
	}

	// ■フォームからメール文読み込み
	public function read_form_mail() {
		$this->subject = $_POST['subject'];
		$this->header = $_POST['header'];
		$this->body = $_POST['body'];
		$this->footer = $_POST['footer'];
	}

	// ■フォームから応募フォーム読み込み
	public function read_form_enq() {
		$this->enq_title = $_POST['enq_title'];
		$this->enq_question = $_POST['enq_question'];
		$this->enq_nickname = $_POST['enq_nickname'];
		$this->enq_comment = $_POST['enq_comment'];
		$this->enq_option = $_POST['enq_option'];
	}

	// ■クリックカウンタ登録
	protected function set_click_counter() {
		if ($_POST['ct_id'] != '') {
			$cc_ct_id = explode('||', $_POST['ct_id']);
			$cc_url_name = explode('||', $_POST['url_name']);
			$cc_jump_url = explode('||', $_POST['jump_url']);

			for ($j = 0; $j < count($cc_ct_id); $j++) {
				// クリックカウンタ登録
				$rec['tcc_letter_id'] = sql_number($this->letter_id);
				$rec['tcc_url_name'] = sql_char($cc_url_name[$j]);
				$rec['tcc_jump_url'] = sql_char($cc_jump_url[$j]);
				$rec['tcc_start_date'] = sql_date($_POST['cc_start_date']);
				$rec['tcc_end_date'] = sql_date($_POST['cc_end_date']);
				db_insert('t_click_count', $rec);
				$click_id = get_current_seq('t_click_count', 'tcc_click_id');

				// クリックカウンタURLのパラメータを置換
				$this->body = str_replace("%CTID".$cc_ct_id[$j]."%", $click_id, $this->body);
			}
		}
	}
}
?>