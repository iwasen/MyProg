<?
/******************************************************
' System :きかせて・net共通
' Content:Myモニター関連
'******************************************************/

class my_monitor_class {
	var $room_id;
	var $status;
	var $marketer_id;
	var $room_name;
	var $start_date_y;
	var $start_date_m;
	var $start_date_d;
	var $end_date_y;
	var $end_date_m;
	var $end_date_d;
	var $period;
	var $condition;
	var $start_mail;
	var $start_mail_flag;
	var $end_mail_flag;

	function my_monitor_class() {
		$this->status = 0;
		$this->period = 6;
	}

	function read_db($room_id) {
		$sql = "SELECT * FROM t_my_monitor WHERE mm_room_id=$room_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->room_id = $fetch->mm_room_id;
			$this->status = $fetch->mm_status;
			$this->marketer_id = $fetch->mm_marketer_id;
			$this->room_name = $fetch->mm_room_name;
			$this->start_date_y = get_datepart('Y', $fetch->mm_start_date);
			$this->start_date_m = get_datepart('M', $fetch->mm_start_date);
			$this->start_date_d = get_datepart('D', $fetch->mm_start_date);
			$this->end_date_y = get_datepart('Y', $fetch->mm_end_date);
			$this->end_date_m = get_datepart('M', $fetch->mm_end_date);
			$this->end_date_d = get_datepart('D', $fetch->mm_end_date);
			$this->period = $fetch->mm_period;
			$this->condition = $fetch->mm_condition;
			$this->start_mail = $fetch->mm_start_mail;
			$this->start_mail_flag = $fetch->mm_start_mail_flag;
			$this->end_mail_flag = $fetch->mm_end_mail_flag;
		}
	}

	function write_db() {
		$end_date = date("Y/m/d", mktime(0,0,0,$this->start_date_m + $this->period,$this->start_date_d,$this->start_date_y));

		if ($this->start_mail == '') {
			$sql = "SELECT mt_body FROM m_mail_template WHERE mt_template_id='mym_mn1'";
			$this->start_mail = str_replace("\r\n", "\n", db_fetch1($sql));
		}

		if ($this->room_id == '') {
			$sql = "INSERT INTO t_my_monitor (mm_status,mm_marketer_id,mm_room_name,mm_start_date,mm_end_date,mm_period,mm_condition,mm_start_mail) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)";
		} else {
			$sql = "UPDATE t_my_monitor SET mm_status=%s,mm_marketer_id=%s,mm_room_name=%s,mm_start_date=%s,mm_end_date=%s,mm_period=%s,mm_condition=%s,mm_start_mail=%s WHERE mm_room_id=$this->room_id";
		}
		$sql = sprintf($sql,
				sql_number($this->status),
				sql_number($this->marketer_id),
				sql_char($this->room_name),
				sql_date2($this->start_date_y, $this->start_date_m, $this->start_date_d),
				sql_date($end_date),
				sql_number($this->period),
				sql_char($this->condition),
				sql_char($this->start_mail));
		db_exec($sql);
	}
}
?>