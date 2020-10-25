<?
/******************************************************
' System :きかせて・netマーケターページ
' Content:マーケター登録情報クラス
'******************************************************/

class marketer_regist {
	var $marketer_id;
	var $type;
	var $name1;
	var $name2;
	var $name1_kana;
	var $name2_kana;
	var $birthday_y;
	var $birthday_m;
	var $birthday_d;
	var $sex;
	var $mail_addr;
	var $mail_addr2;
	var $password;
	var $password2;
	var $shokugyou_cd;
	var $gyoushu_cd;
	var $shokushu_cd;
	var $kinmu_zip1;
	var $kinmu_zip2;
	var $kinmu_area;
	var $kinmu_addr1;
	var $kinmu_addr2;
	var $kinmu_addr3;
	var $kinmu_tel1;
	var $kinmu_tel2;
	var $kinmu_tel3;
	var $kinmu_fax1;
	var $kinmu_fax2;
	var $kinmu_fax3;
	var $kinmu_name;
	var $kinmu_unit;
	var $kinmu_post;
	var $agent_id;
	var $choice_email1;
	var $choice_email2;
	var $choice_email3;
	var $parent_id;
	var $permission;

	// DBに書き込み
	function save_db() {
		$sql = "SELECT wr_seq_no FROM w_marketer WHERE wr_mail_addr_low=" . sql_char(strtolower($this->mail_addr));
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$seq_no = $fetch->wr_seq_no;
			$sql = "UPDATE w_marketer SET wr_regist_date=current_timestamp,wr_type=%s,wr_name1=%s,wr_name2=%s,wr_name1_kana=%s,wr_name2_kana=%s,wr_birthday=%s,wr_sex=%s,wr_mail_addr=%s,wr_mail_addr_low=%s,wr_password=%s,wr_shokugyou_cd=%s,wr_gyoushu_cd=%s,wr_shokushu_cd=%s,wr_kinmu_zip=%s,wr_kinmu_area=%s,wr_kinmu_addr1=%s,wr_kinmu_addr2=%s,wr_kinmu_addr3=%s,wr_kinmu_tel=%s,wr_kinmu_fax=%s,wr_kinmu_name=%s,wr_kinmu_unit=%s,wr_kinmu_post=%s,wr_agent_id=%s,wr_choice_email1=%s,wr_choice_email2=%s,wr_choice_email3=%s,wr_marketer_id=%s WHERE wr_seq_no=$seq_no";
		} else {
			$sql = "INSERT INTO w_marketer (wr_type,wr_name1,wr_name2,wr_name1_kana,wr_name2_kana,wr_birthday,wr_sex,wr_mail_addr,wr_mail_addr_low,wr_password,wr_shokugyou_cd,wr_gyoushu_cd,wr_shokushu_cd,wr_kinmu_zip,wr_kinmu_area,wr_kinmu_addr1,wr_kinmu_addr2,wr_kinmu_addr3,wr_kinmu_tel,wr_kinmu_fax,wr_kinmu_name,wr_kinmu_unit,wr_kinmu_post,wr_agent_id,wr_choice_email1,wr_choice_email2,wr_choice_email3,wr_marketer_id) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		}
		$sql = sprintf($sql,
				sql_char($this->type),
				sql_char($this->name1),
				sql_char($this->name2),
				sql_char($this->name1_kana),
				sql_char($this->name2_kana),
				sql_date("$this->birthday_y/$this->birthday_m/$this->birthday_d"),
				sql_number($this->sex),
				sql_char($this->mail_addr),
				sql_char(strtolower($this->mail_addr)),
				sql_char($this->password),
				sql_number($this->shokugyou_cd),
				sql_number($this->gyoushu_cd),
				sql_number($this->shokushu_cd),
				sql_char($this->kinmu_zip1 . $this->kinmu_zip2),
				sql_number($this->kinmu_area),
				sql_char($this->kinmu_addr1),
				sql_char($this->kinmu_addr2),
				sql_char($this->kinmu_addr3),
				sql_char("$this->kinmu_tel1-$this->kinmu_tel2-$this->kinmu_tel3"),
				sql_char("$this->kinmu_fax1-$this->kinmu_fax2-$this->kinmu_fax3"),
				sql_char($this->kinmu_name),
				sql_char($this->kinmu_unit),
				sql_char($this->kinmu_post),
				sql_number($this->agent_id),
				sql_char($this->choice_email1),
				sql_char($this->choice_email2),
				sql_char($this->choice_email3),
				sql_number($this->marketer_id));
		db_exec($sql);

		if (!$seq_no)
			$seq_no = get_currval('w_marketer_wr_seq_no_seq');

		return $seq_no;
	}

	function read_db($marketer_id) {
		$sql = "SELECT * FROM t_marketer WHERE mr_marketer_id=$marketer_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$kinmu_tel = explode('-', $fetch->mr_kinmu_tel);
			$kinmu_fax = explode('-', $fetch->mr_kinmu_fax);

			$this->marketer_id = $fetch->mr_marketer_id;
			$this->type = $fetch->mr_type;
			$this->name1 = $fetch->mr_name1;
			$this->name2 = $fetch->mr_name2;
			$this->name1_kana = $fetch->mr_name1_kana;
			$this->name2_kana = $fetch->mr_name2_kana;
			$this->birthday_y = get_datepart('Y', $fetch->mr_birthday);
			$this->birthday_m = get_datepart('M', $fetch->mr_birthday);
			$this->birthday_d = get_datepart('D', $fetch->mr_birthday);
			$this->sex = $fetch->mr_sex;
			$this->mail_addr = $fetch->mr_mail_addr;
			$this->mail_addr2 = $fetch->mr_mail_addr;
			$this->password = $fetch->mr_password;
			$this->password2 = $fetch->mr_password;
			$this->shokugyou_cd = $fetch->mr_shokugyou_cd;
			$this->gyoushu_cd = $fetch->mr_gyoushu_cd;
			$this->shokushu_cd = $fetch->mr_shokushu_cd;
			$this->kinmu_zip1 = substr($fetch->mr_kinmu_zip, 0, 3);
			$this->kinmu_zip2 = substr($fetch->mr_kinmu_zip, 3, 4);
			$this->kinmu_area = $fetch->mr_kinmu_area;
			$this->kinmu_addr1 = $fetch->mr_kinmu_addr1;
			$this->kinmu_addr2 = $fetch->mr_kinmu_addr2;
			$this->kinmu_addr3 = $fetch->mr_kinmu_addr3;
			$this->kinmu_tel1 = $kinmu_tel[0];
			$this->kinmu_tel2 = $kinmu_tel[1];
			$this->kinmu_tel3 = $kinmu_tel[2];
			$this->kinmu_fax1 = $kinmu_fax[0];
			$this->kinmu_fax2 = $kinmu_fax[1];
			$this->kinmu_fax3 = $kinmu_fax[2];
			$this->kinmu_name = $fetch->mr_kinmu_name;
			$this->kinmu_unit = $fetch->mr_kinmu_unit;
			$this->kinmu_post = $fetch->mr_kinmu_post;
			$this->agent_id = $fetch->mr_agent_id;
			$this->parent_id = $fetch->mr_parent_id;
			$this->permission = $fetch->mr_permission;
		}
	}

	function update_db() {
		if ($this->marketer_id == '') {
			$sql = "INSERT INTO t_marketer (mr_type,mr_name1,mr_name2,mr_name1_kana,mr_name2_kana,mr_birthday,mr_sex,mr_mail_addr,mr_mail_addr_low,mr_password,mr_shokugyou_cd,mr_gyoushu_cd,mr_shokushu_cd,mr_kinmu_zip,mr_kinmu_area,mr_kinmu_addr1,mr_kinmu_addr2,mr_kinmu_addr3,mr_kinmu_tel,mr_kinmu_fax,mr_kinmu_name,mr_kinmu_unit,mr_kinmu_post,mr_agent_id,mr_parent_id,mr_permission) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		} else {
			$sql = "UPDATE t_marketer SET mr_update_date=current_timestamp,mr_type=%s,mr_name1=%s,mr_name2=%s,mr_name1_kana=%s,mr_name2_kana=%s,mr_birthday=%s,mr_sex=%s,mr_mail_addr=%s,mr_mail_addr_low=%s,mr_password=%s,mr_shokugyou_cd=%s,mr_gyoushu_cd=%s,mr_shokushu_cd=%s,mr_kinmu_zip=%s,mr_kinmu_area=%s,mr_kinmu_addr1=%s,mr_kinmu_addr2=%s,mr_kinmu_addr3=%s,mr_kinmu_tel=%s,mr_kinmu_fax=%s,mr_kinmu_name=%s,mr_kinmu_unit=%s,mr_kinmu_post=%s,mr_agent_id=%s,mr_parent_id=%s,mr_permission=%s WHERE mr_marketer_id=$this->marketer_id";
		}
		$sql = sprintf($sql,
				sql_char($this->type),
				sql_char($this->name1),
				sql_char($this->name2),
				sql_char($this->name1_kana),
				sql_char($this->name2_kana),
				sql_date("$this->birthday_y/$this->birthday_m/$this->birthday_d"),
				sql_number($this->sex),
				sql_char($this->mail_addr),
				sql_char(strtolower($this->mail_addr)),
				sql_char($this->password),
				sql_number($this->shokugyou_cd),
				sql_number($this->gyoushu_cd),
				sql_number($this->shokushu_cd),
				sql_char($this->kinmu_zip1 . $this->kinmu_zip2),
				sql_number($this->kinmu_area),
				sql_char($this->kinmu_addr1),
				sql_char($this->kinmu_addr2),
				sql_char($this->kinmu_addr3),
				sql_char("$this->kinmu_tel1-$this->kinmu_tel2-$this->kinmu_tel3"),
				sql_char("$this->kinmu_fax1-$this->kinmu_fax2-$this->kinmu_fax3"),
				sql_char($this->kinmu_name),
				sql_char($this->kinmu_unit),
				sql_char($this->kinmu_post),
				sql_number($this->agent_id),
				sql_number($this->parent_id),
				sql_char($this->permission));
		db_exec($sql);

		if ($this->marketer_id == '')
				$this->marketer_id = get_currval('t_marketer_mr_marketer_id_seq');

		// 不達状態をリセット
		$sql = "UPDATE t_marketer SET mr_status=0 WHERE mr_marketer_id=$this->marketer_id AND mr_status=2";
		db_exec($sql);
		$sql = "DELETE FROM t_err_mail WHERE er_member_kind=2 AND er_member_id=$this->marketer_id";
		db_exec($sql);
	}
}