<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:督促メール配信更新処理
'******************************************************/

class reminder_class {
	var $enquete_id;
	var $mail_title;
	var $mail_header;
	var $mail_body;
	var $mail_footer;
	var $send_flag;
	var $send_year;
	var $send_month;
	var $select_day;
	var $send_hour;
	var $sex;
	var $age_type;
	var $age_from;
	var $age_to;
	var $mikikon;
	var $shokugyou;
	var $chain;
	var $chain_andor;
	var $child;
	var $sql;
	var $jitaku_area;

	var $age_cd;
	var $search;

	var $search_set;
	var $mail_set;

	// コンストラクタ
	function reminder_class() {
		$this->enquete_id = $_POST['enquete_id'];
		$this->chain_andor = 'O';
	}

	// 複数データ一括取得
	function read_data(){
		return $this->enquete_id;
	}

	// メールデータセット
	function set_mail_data() {
		$this->mail_title = $_POST['mail_title'];
		$this->mail_header = $_POST['mail_header'];
		$this->mail_body = $_POST['mail_body'];
		$this->mail_footer = $_POST['mail_footer'];
		$this->send_flag = $_POST['send_flag'];
		$this->send_year = $_POST['send_year'];
		$this->send_month = $_POST['send_month'];
		$this->send_day = $_POST['send_day'];
		$this->send_hour = $_POST['send_hour'];

		if($this->mail_title || $this->mail_header || $this->mail_body || $this->mail_footer || $this->send_flag)
			$this->mail_set = true;
	}

	// テンプレート情報取得
	function get_mail_template() {
		$sql = "SELECT * FROM m_enq_template WHERE et_template_id='mye_rem'";
		$result = db_exec($sql);
		$fetch = pg_fetch_object($result, 0);

		$this->mail_title = '%TITLE%';
		$this->mail_header = $fetch->et_header;
		$this->mail_body = $fetch->et_body;
		$this->mail_footer = $fetch->et_footer;
	}

	// 検索条件セット
	function set_search_data(){
		if($_GET['back'] != 1) {
			$this->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
			$this->age_type = $_POST['age_type'];
			if ($this->age_type == '1') {
				$this->age_cd = get_multi_data($_POST['age_cd']);
				$this->age_from = '';
				$this->age_to = '';
			} elseif ($this->age_type == '2') {
				$this->age_cd = '';
				$this->age_from = $_POST['age_from'];
				$this->age_to = $_POST['age_to'];
			} else {
				$this->age_cd = '';
				$this->age_from = '';
				$this->age_to = '';
			}
			$this->mikikon = (count($_POST['mikikon']) == 1) ? $_POST['mikikon'][0] : '';
			$this->jitaku_area = get_multi_data($_POST['jitaku_area']);
			$this->shokugyou = get_multi_data($_POST['shokugyou']);
			$this->chain = get_multi_data($_POST['chain']);
		}
		if($this->sex || $this->age_type || $this->mikikon || $this->jitaku_area || $this->shokugyou || $this->chain)
			$this->search_set = true;
	}

	// 該当無回答者カウント
	function get_noans_num($enq_id, $form = ''){
		$this->search = new search_class;
		if($form){
			$this->search->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
			$this->search->age_type = $_POST['age_type'];
			if ($this->search->age_type == '1') {
				$this->search->age_cd = get_multi_data($_POST['age_cd']);
				$this->search->age_from = '';
				$this->search->age_to = '';
			} elseif ($this->search->age_type == '2') {
				$this->search->age_cd = '';
				$this->search->age_from = $_POST['age_from'];
				$this->search->age_to = $_POST['age_to'];
			} else {
				$this->search->age_cd = '';
				$this->search->age_from = '';
				$this->search->age_to = '';
			}
			$this->search->mikikon = (count($_POST['mikikon']) == 1) ? $_POST['mikikon'][0] : '';
			$this->search->jitaku_area = get_multi_data($_POST['jitaku_area']);
			$this->search->shokugyou = get_multi_data($_POST['shokugyou']);
			$this->search->chain = get_multi_data($_POST['chain']);
		
		} else {
			$this->search->sex = $this->sex;
			$this->search->age_from = $this->age_from;
			$this->search->age_to = $this->age_to;
			$this->search->mikikon = $this->mikikon;
			$this->search->shokugyou = $this->shokugyou;
			$this->search->chain = $this->chain;
			$this->search->chain_andor = $this->chain_andor;
			$this->search->child = $this->child;
			$this->search->sql = $this->sql;
			$this->search->jitaku_area = $this->jitaku_area;
			$this->search->age_cd = $this->age_cd;
		}
		$sub2 = "SELECT an_monitor_id FROM t_answer WHERE an_enquete_id= $enq_id";
		$sub = "SELECT * FROM t_monitor JOIN t_enquete_list ON mn_monitor_id=el_monitor_id WHERE el_enquete_id= $enq_id AND mn_monitor_id NOT IN ($sub2)";
		$sql = "SELECT COUNT(*) FROM ($sub) AS sub WHERE " . $this->search->make_sql();
		return db_fetch1($sql);
	}

	// 該当モニター数
	function get_search_num($enq_id){

		$sub = "SELECT an_monitor_id FROM t_answer WHERE an_enquete_id= $enq_id";
		$sql = "SELECT COUNT(el_monitor_id) FROM t_enquete_list LEFT JOIN t_monitor ON el_monitor_id=mn_monitor_id WHERE el_enquete_id= $enq_id AND mn_status=0 AND el_monitor_id NOT IN ($sub)";
		return db_fetch1($sql);
	}

	// 検索情報保存
	function save_search() {
		$sql = "INSERT INTO t_search (sr_sex,sr_age_from,sr_age_to,sr_mikikon,sr_shokugyou,sr_chain,sr_chain_andor,sr_child,sr_sql,sr_jitaku_area,sr_age_cd,sr_research) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		$sql = sprintf($sql,
				sql_number($this->sex),
				sql_number($this->age_from),
				sql_number($this->age_to),
				sql_number($this->mikikon),
				sql_char($this->shokugyou),
				sql_char($this->chain),
				sql_char($this->chain_andor),
				sql_number($this->child),
				sql_char($this->sql),
				sql_char($this->jitaku_area),
				sql_char($this->age_cd),
				sql_number($this->research));

		db_exec($sql);

		$search_id = get_currval('t_search_sr_search_id_seq');
		return $search_id;
	}

	// 督促メール情報保存
	function save_reminder($search_id){
		if (!$this->send_flag){
			$this->send_year = date('Y');
			$this->send_month = date('m');
			$this->send_day = date('d');
			$this->send_hour = date('H');
		}

		$sql = "INSERT INTO t_reminder (rm_search_id,rm_mail_title,rm_mail_header,rm_mail_body,rm_mail_footer,rm_send_date) VALUES (%s,%s,%s,%s,%s,%s)";
		$sql = sprintf($sql,
				sql_number($search_id),
				sql_char($this->mail_title),
				sql_char($this->mail_header),
				sql_char($this->mail_body),
				sql_char($this->mail_footer),
				sql_date2($this->send_year,$this->send_month,$this->send_day,$this->send_hour));
		db_exec($sql);

		$reminder_id = get_currval('t_reminder_rm_reminder_id_seq ');

		return $reminder_id;
	}


	function update_my_enq($reminder_id, $enquete_id){
		$sql = sprintf("UPDATE t_my_enquete SET me_reminder_id=%s WHERE me_enquete_id=%s",
			sql_number($reminder_id),
			sql_number($enquete_id));
		db_exec($sql);
	}
}

?>