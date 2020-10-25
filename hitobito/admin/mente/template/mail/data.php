<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メールテンプレートマスタデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class template_data_class extends data_class {
	public $template_id;
	public $guide;
	public $subject;
	public $from;
	public $cc;
	public $bcc;
	public $reply_to;
	public $body;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($template_id) {
		$sql = sprintf("SELECT * FROM m_mail_template WHERE mtm_template_id = '%s'", sql_number($template_id));
		$fetch = $this->fetch_db($sql);

		$this->template_id = $fetch->mtm_template_id;
		$this->guide = $fetch->mtm_guide;
		$this->subject = $fetch->mtm_subject;
		$this->from = $fetch->mtm_from;
		$this->cc = $fetch->mtm_cc;
		$this->bcc = $fetch->mtm_bcc;
		$this->reply_to = $fetch->mtm_reply_to;
		$this->body = $fetch->mtm_body;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->template_id = $_POST['template_id'];
		$this->guide = $_POST['guide'];
		$this->subject = $_POST['subject'];
		$this->from = $_POST['from'];
		$this->cc = $_POST['cc'];
		$this->bcc = $_POST['bcc'];
		$this->reply_to = $_POST['reply_to'];
		$this->body = $_POST['body'];
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		$rec['mtm_guide'] = sql_char($this->guide);
		$rec['mtm_subject'] = sql_char($this->subject);
		$rec['mtm_from'] = sql_char($this->from);
		$rec['mtm_cc'] = sql_char($this->cc);
		$rec['mtm_bcc'] = sql_char($this->bcc);
		$rec['mtm_reply_to'] = sql_char($this->reply_to);
		$rec['mtm_body'] = sql_char($this->body);
		db_update('m_mail_template', $rec, "mtm_template_id='$this->template_id'");
	}

	// ■DBから削除
	public function delete_db() {
	}
}
?>