<?
/******************************************************
' System :きかせて・netモニターページ
' Content:リサーチパートナー情報クラス
'******************************************************/

class m_partner {
	var $active;
	var $name1;
	var $name2;
	var $birthday_y;
	var $birthday_m;
	var $birthday_d;
	var $mail_addr;
	var $jitaku_zip1;
	var $jitaku_zip2;
	var $jitaku_area;
	var $jitaku_addr1;
	var $jitaku_addr2;
	var $jitaku_addr3;
	var $jitaku_tel1;
	var $jitaku_tel2;
	var $jitaku_tel3;
	var $jitaku_fax1;
	var $jitaku_fax2;
	var $jitaku_fax3;
	var $shokugyou_cd;
	var $gyoushu_cd;
	var $shokushu_cd;
	var $kinmu_name;
	var $kinmu_zip1;
	var $kinmu_zip2;
	var $kinmu_area;

	// コンストラクタ
	function m_partner() {
		$this->active = true;
	}


	function read_db($monitor_id) {
		$sql = "SELECT * FROM t_monitor WHERE mn_monitor_id=$monitor_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$jitaku_tel = explode('-', $fetch->mn_jitaku_tel);
			$jitaku_fax = explode('-', $fetch->mn_jitaku_fax);
			$kinmu_tel = explode('-', $fetch->mn_kinmu_tel);
			$kinmu_fax = explode('-', $fetch->mn_kinmu_fax);

			$this->name1 = $fetch->mn_name1;
			$this->name2 = $fetch->mn_name2;
			$this->birthday_y = get_datepart('Y', $fetch->mn_birthday);
			$this->birthday_m = get_datepart('M', $fetch->mn_birthday);
			$this->birthday_d = get_datepart('D', $fetch->mn_birthday);
			$this->mail_addr = $fetch->mn_mail_addr;
			$this->jitaku_zip1 = substr($fetch->mn_jitaku_zip, 0, 3);
			$this->jitaku_zip2 = substr($fetch->mn_jitaku_zip, 3, 4);
			$this->jitaku_area = $fetch->mn_jitaku_area;
			$this->jitaku_addr1 = $fetch->mn_jitaku_addr1;
			$this->jitaku_addr2 = $fetch->mn_jitaku_addr2;
			$this->jitaku_addr3 = $fetch->mn_jitaku_addr3;
			$this->jitaku_tel1 = $jitaku_tel[0];
			$this->jitaku_tel2 = $jitaku_tel[1];
			$this->jitaku_tel3 = $jitaku_tel[2];
			$this->jitaku_fax1 = $jitaku_fax[0];
			$this->jitaku_fax2 = $jitaku_fax[1];
			$this->jitaku_fax3 = $jitaku_fax[2];
			$this->shokugyou_cd = $fetch->mn_shokugyou_cd;
			$this->gyoushu_cd = $fetch->mn_gyoushu_cd;
			$this->shokushu_cd = $fetch->mn_shokushu_cd;
			$this->kinmu_name = $fetch->mn_kinmu_name;
			$this->kinmu_zip1 = substr($fetch->mn_kinmu_zip, 0, 3);
			$this->kinmu_zip2 = substr($fetch->mn_kinmu_zip, 3, 4);
			$this->kinmu_area = $fetch->mn_kinmu_area;
		}
	}

	function update_db($monitor_id) {
		$sql = sprintf("UPDATE t_monitor SET mn_update_date=current_timestamp,mn_jitaku_zip=%s,mn_jitaku_area=%s,mn_jitaku_addr1=%s,mn_jitaku_addr2=%s,mn_jitaku_addr3=%s,mn_jitaku_tel=%s,mn_jitaku_fax=%s WHERE mn_monitor_id=$monitor_id",
			sql_char($this->jitaku_zip1 . $this->jitaku_zip2),
			sql_char($this->jitaku_area),
			sql_char($this->jitaku_addr1),
			sql_char($this->jitaku_addr2),
			sql_char($this->jitaku_addr3),
			sql_char(format_tel($this->jitaku_tel1, $this->jitaku_tel2, $this->jitaku_tel3)),
			sql_char(format_tel($this->jitaku_fax1, $this->jitaku_fax2, $this->jitaku_fax3)));
		db_exec($sql);
	}
}
?>