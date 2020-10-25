<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メンバーアンケート処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class member_enq_data_class extends data_class {
	public $job_bname;
	public $start_date;
	public $end_date;
	public $room_flag;
	public $room_id;
	public $enq_target;
	public $status;
	public $title;
	public $enq_text;
	public $quest_text = array();
	public $quest_type = array();
	public $quest_option = array();
	public $free_flag = array();

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($enquete_id) {
		$sql = sprintf("SELECT * FROM t_room_enquete WHERE ren_enquete_id=%s", sql_number($enquete_id));
		$fetch = $this->fetch_db($sql);

		$this->enquete_id = $fetch->ren_enquete_id;
		$this->status = $fetch->ren_status;
		$this->job_name = $fetch->ren_job_name;
		$this->start_date = get_datepart($fetch->ren_start_date);
		$this->end_date = get_datepart($fetch->ren_end_date);
		$this->room_id = $fetch->ren_room_id;
		$this->enq_target = $fetch->ren_target;
		$this->title = $fetch->ren_title;
		$this->enq_text = $fetch->ren_enquete_text;

		$sql = sprintf("SELECT * FROM t_room_enq_question WHERE req_enquete_id=%s ORDER BY req_question_no", sql_number($enquete_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->quest_text[] = $fetch->req_question_text;
			$this->quest_type[] = $fetch->req_type;
			$this->free_flag[] = $fetch->req_free_flag == 1 ? 1 : '';

			$options = array();
			$quest_no = $fetch->req_question_no;
			$sql = sprintf("SELECT reo_option_text FROM t_room_enq_option WHERE reo_enquete_id=%s AND reo_question_no=%s ORDER BY reo_option_no"
				, sql_number($enquete_id), sql_number($quest_no));
			$result2 = db_exec($sql);
			$nrow2 = pg_numrows($result2);
			for ($j = 0; $j < $nrow2; $j++) {
				$fetch = pg_fetch_object($result2, $j);

				$options[] = $fetch->reo_option_text;
			}

			$this->quest_option[] = implode("\n", $options);
		}
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->enquete_id = $_POST['enquete_id'];
		$this->job_name = $_POST['job_name'];
		$this->start_date = $this->get_date('start');
		$this->end_date = $this->get_date('end');
		$this->room_flag = $_POST['room_flag'];
		$this->room_id = $_POST['room_id'];
		$this->enq_target = $_POST['enq_target'];
		$this->status = $_POST['status'];
		$this->title = $_POST['title'];
		$this->enq_text = $_POST['enq_text'];

		$this->quest_text = $_POST['quest_text'];
		$this->quest_type = $_POST['quest_type'];
		$this->quest_option = $_POST['quest_option'];
		$this->free_flag = $_POST['free_flag'];


	}

	// ■データチェック
	public function check() {
		return true;
	}

	// ■DBに追加
	public function insert_db() {
		db_begin_trans();

		// メンバーアンケート情報登録
		$rec['ren_job_name'] = sql_char($this->job_name);
		$rec['ren_start_date'] = sql_date2($this->start_date);
		$rec['ren_end_date'] = sql_date2($this->end_date);
		$rec['ren_room_id'] = sql_number($this->room_flag ? $this->room_id : '');
		$rec['ren_target'] = sql_number($this->enq_target);
		$rec['ren_title'] = sql_char($this->title);
		$rec['ren_enquete_text'] = sql_char($this->enq_text);
		db_insert('t_room_enquete', $rec);

		$this->enquete_id = get_current_seq('t_room_enquete', 'ren_enquete_id');

		// 設問、選択肢情報登録
		$this->add_question();

		db_commit_trans();
	}

	// ■DBを更新
	public function update_db() {
		$options = array();

		db_begin_trans();

		// メンバーアンケート情報更新
		$rec['ren_status'] = sql_char($this->status);
		$rec['ren_job_name'] = sql_char($this->job_name);
		$rec['ren_start_date'] = sql_date2($this->start_date);
		$rec['ren_end_date'] = sql_date2($this->end_date);
		$rec['ren_room_id'] = sql_number($this->room_flag ? $this->room_id : '');
		$rec['ren_target'] = sql_number($this->enq_target);
		$rec['ren_title'] = sql_char($this->title);
		$rec['ren_enquete_text'] = sql_char($this->enq_text);
		db_update('t_room_enquete',$rec , "ren_enquete_id=$this->enquete_id");

		// 設問情報を削除
		db_delete('t_room_enq_question', 'req_enquete_id=' . $this->enquete_id);
		// 選択肢情報を削除
		db_delete('t_room_enq_option', 'reo_enquete_id=' . $this->enquete_id);

		// 設問、選択肢情報登録
		$this->add_question();

		db_commit_trans();

	}

	// ■設問、選択肢情報登録
	private function add_question() {

		for ($i = 0; $i < count($this->quest_text); $i++) {

			if ($this->quest_text[$i]) {
				$rec['req_enquete_id'] = sql_number($this->enquete_id);
				$rec['req_question_no'] = sql_number($i + 1);
				$rec['req_type'] = sql_number($this->quest_type[$i]);
				$rec['req_free_flag'] = sql_number($this->free_flag[$i] ? 1 : 0);
				$rec['req_question_text'] = sql_char($this->quest_text[$i]);
				db_insert('t_room_enq_question', $rec);

				if ($this->quest_option[$i] != '' && $this->quest_type[$i] != 3) {
					$options = explode("\n", trim($this->quest_option[$i]));

					for($j = 0; $j < count($options); $j++) {

						$rec['reo_enquete_id'] = sql_number($this->enquete_id);
						$rec['reo_question_no'] = sql_number($i + 1);
						$rec['reo_option_no'] = sql_number($j + 1);
						$rec['reo_option_text'] = sql_char($options[$j]);
						db_insert('t_room_enq_option', $rec);
					}
				}
			}
		}
	}

/*
	// ■DBから削除
	public function delete_db() {
		db_delete('t_member', "mbr_member_id=$this->member_id");
	}
*/
}
?>