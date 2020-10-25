<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報クラス
'******************************************************/
class family {
	var $family_rel;
	var $family_age;
}

class mm_regist2 {
	var $back_page;
	var $active;
	var $mb_seq_no;
	var $mb_id;
	var $mb_id_org;
	var $mb_mail_addr;
	var $mb_mail_addr_org;
	var $mb_password;
	var $mb_password2;
	var $mb_status;
	var $mb_step;
	var $name1;
	var $name2;
	var $name1_kana;
	var $name2_kana;
	var $sex;
	var $birthday_y;
	var $birthday_m;
	var $birthday_d;
	var $zip1;
	var $zip2;
	var $area_cd;
	var $address;
	var $family_ctg;
	var $keitai_cd;
//	var $keitai_etc;
	var $sozai_cd;
	var $hebel_flag;
	var $chikunen_cd;
	var $room_cd;
	var $space_cd;
	var $boiler_cd;
	var $boiler_3cd;
	var $boil_gas_flag;
	var $boil_ele_flag;
	var $boiler_etc;
	var $gas_kind_cd;
	var $gas_type_cd;
	var $water_month;
	var $car_flag;
	var $gcar_num;
	var $dcar_num;
	var $bike_num;
	var $ol_flag;
	var $family;

	// コンストラクタ
	function mm_regist2() {
		$this->active = true;
	}

	// ID,メールアドレス取得
	function read_db_b($seq_no) {
		$sql = "select mb_id,mb_password,mb_mail_addr from t_member where mb_seq_no=" . sql_number($seq_no);
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->mb_id = $fetch->mb_id;
			$this->mb_password = $fetch->mb_password;
			$this->mb_mail_addr = $fetch->mb_mail_addr;
		}
	}

	// 基本情報取得
	function read_db1($mb_seq_no) {
		$sql = "select * from t_member where mb_seq_no=" . sql_number($mb_seq_no);
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->mb_id_org = $fetch->mb_id;
			$this->mb_id = $fetch->mb_id;
			$this->mb_password = $fetch->mb_password;
			$this->mb_mail_addr_org = $fetch->mb_mail_addr;
			$this->mb_mail_addr = $fetch->mb_mail_addr;
			$this->name1 = $fetch->mb_name1;
			$this->name2 = $fetch->mb_name2;
			$this->name1_kana = $fetch->mb_name1_kana;
			$this->name2_kana = $fetch->mb_name2_kana;
			$this->sex = $fetch->mb_sex;
			$this->birthday_y = get_datepart('Y', $fetch->mb_birthday);
			$this->birthday_m = get_datepart('M', $fetch->mb_birthday);
			$this->birthday_d = get_datepart('D', $fetch->mb_birthday);
			$this->zip1 = substr($fetch->mb_zip, 0, 3);
			$this->zip2 = substr($fetch->mb_zip, 3, 4);
			$this->area_cd = $fetch->mb_area_cd;
			$this->address = $fetch->mb_address;
			$this->family_ctg = $fetch->mb_family_ctg;
			$this->keitai_cd = $fetch->mb_keitai_cd;
			$this->keitai_etc = $fetch->mb_keitai_etc;
			$this->sozai_cd = $fetch->mb_sozai_cd;
			$this->hebel_flag = $fetch->mb_hebel_flag;
			$this->chikunen_cd = $fetch->mb_chikunen_cd;
			$this->space_cd = $fetch->mb_space_cd;
			$this->boiler_cd = $fetch->mb_boiler_cd;
			$this->boiler_etc = $fetch->mb_boiler_etc;
			$this->gas_kind_cd = $fetch->mb_gas_kind_cd;
			$this->gas_type_cd = $fetch->mb_gas_type_cd;
			$this->water_month = $fetch->mb_water_month;
			$this->gcar_num = $fetch->mb_gcar_num;
			$this->dcar_num = $fetch->mb_dcar_num;
			$this->bike_num = $fetch->mb_bike_num;
			$this->ol_flag = $fetch->mb_ol_flag;
			$this->room_cd = $fetch->mb_room_cd;

			// 給湯器情報セット
			switch ($this->boiler_cd) {
			case 1:
				$this->boiler_3cd = 1;
				$this->boil_ele_flag = 2;
				$this->boil_gas_flag = '';
				break;
			case 2:
				$this->boiler_3cd = 1;
				$this->boil_ele_flag = 1;
				$this->boil_gas_flag = '';
				break;
			case 3:
				$this->boiler_3cd = 3;
				$this->boil_ele_flag = '';
				$this->boil_gas_flag = 2;
				break;
			case 4:
				$this->boiler_3cd = 3;
				$this->boil_ele_flag = '';
				$this->boil_gas_flag = 1;
				break;
			case 5:
				$this->boiler_3cd = 5;
				$this->boil_ele_flag = '';
				$this->boil_gas_flag = '';
				break;
			case 6:
				$this->boiler_3cd = 1;
				$this->boil_ele_flag = 3;
				$this->boil_gas_flag = '';
				break;
			case 7:
				$this->boiler_3cd = 3;
				$this->boil_ele_flag = '';
				$this->boil_gas_flag = 3;
				break;
			}

			// 車所有フラグセット
			if ($this->gcar_num <= 0 && $this->dcar_num <= 0 && $this->bike_num <= 0 )
				$this->car_flag = 0;
			else
				$this->car_flag = 1;
		}
		$sql = "select * from t_family where fm_mb_seq_no=" . sql_number($mb_seq_no) . " order by fm_sub_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$family = &$this->family[$i + 1];
			$family->family_rel = $fetch->fm_family_rel_cd;
			$family->family_age = $fetch->fm_age;
		}
	}

	// DBに保存
	function save_db($mb_seq_no,$step) {

		$sql = "SELECT mb_step FROM t_member WHERE mb_seq_no=" . sql_number($mb_seq_no);
		if (db_fetch1($sql) == 1 || $step == 3) {
			db_begin_trans();

			// 車所有なしの場合
			if ($this->car_flag == 0) {
				$this->gcar_num = 0;
				$this->dcar_num = 0;
				$this->bike_num = 0;
			}

/*
			// ガスタイプセット
			if ($this->gas_type_cd == '99')
				$this->gas_type_cd = 13;
*/

			// 家族構成
			$sql = "DELETE FROM t_family WHERE fm_mb_seq_no=$mb_seq_no";
			db_exec($sql);

			$adult_num = 1; $child_num = 0;
			foreach ($this->family as $sub_no => $family) {
				if ($family->family_rel != '') {
					$sql = sprintf("INSERT INTO t_family (fm_mb_seq_no,fm_sub_no,fm_family_rel_cd,fm_age) VALUES (%s,%s,%s,%s)",
						sql_number($mb_seq_no),
						sql_number($sub_no),
						sql_number($family->family_rel),
						sql_number($family->family_age));
					db_exec($sql);

					if ($family->family_age >= 20 && $adult_num < 5)
						$adult_num = $adult_num + 1;
					if ($family->family_age < 20 && $child_num <4)
						$child_num = $child_num + 1;
				}
			}

			$sql = "select mb_seq_no from t_member where mb_step <> 9 and mb_seq_no=" . sql_number($mb_seq_no);
			$result = db_exec($sql);
			if (pg_numrows($result)) {
	//			$fetch = pg_fetch_object($result, 0);
	//			$seq_no = $fetch->mb_seq_no;

				$sub = "select fc_family_ctg_cd from m_family_ctg where fc_adult_num=$adult_num and fc_child_num=$child_num";	// 家族カテゴリ
				$sql= "update t_member set "
					. "mb_id=%s,"
					. "mb_password=%s,"
					. "mb_mail_addr=%s,"
					. "mb_step=%s,"
					. "mb_name1=%s,"
					. "mb_name2=%s,"
					. "mb_name1_kana=%s,"
					. "mb_name2_kana=%s,"
					. "mb_sex=%s,mb_birthday=%s,"
					. "mb_zip=%s,mb_area_cd=%s,"
					. "mb_address=%s,"
					. "mb_family_ctg=($sub),"
					. "mb_keitai_cd=%s,"
	//				. "mb_keitai_etc=%s,"
					. "mb_sozai_cd=%s,"
					. "mb_hebel_flag=%s,"
					. "mb_chikunen_cd=%s,"
					. "mb_room_cd=%s,"
					. "mb_space_cd=%s,"
					. "mb_boiler_cd=%s,"
	//				. "mb_boiler_etc=%s,"
					. "mb_gas_kind_cd=%s,"
					. "mb_gas_type_cd=%s,"
					. "mb_water_month=%s,"
					. "mb_gcar_num=%s,"
					. "mb_dcar_num=%s,"
					. "mb_bike_num=%s,"
					. "mb_ol_flag=%s,"
					. "mb_modify_date='now'"
					. " where mb_seq_no=" . sql_number($mb_seq_no);
			}
			$sql = sprintf($sql,
					sql_char($this->mb_id),
					sql_char($this->mb_password),
					sql_char($this->mb_mail_addr),
					sql_number($step),
					sql_char($this->name1),
					sql_char($this->name2),
					sql_char($this->name1_kana),
					sql_char($this->name2_kana),
					sql_number($this->sex),
					sql_date("$this->birthday_y/$this->birthday_m/$this->birthday_d"),
					sql_char($this->zip1 . $this->zip2),
					sql_number($this->area_cd),
					sql_char($this->address),
					sql_number($this->keitai_cd),
	//				sql_char($this->keitai_etc),
					sql_number($this->sozai_cd),
					sql_number($this->hebel_flag),
					sql_number($this->chikunen_cd),
					sql_number($this->room_cd),
					sql_number($this->space_cd),
					sql_number($this->boiler_cd),
	//				sql_char($this->boiler_etc),
					sql_number($this->gas_kind_cd),
					sql_number($this->gas_type_cd),
					sql_number($this->water_month),
					sql_number($this->gcar_num),
					sql_number($this->dcar_num),
					sql_number($this->bike_num),
					sql_number($this->ol_flag));
			db_exec($sql);
			$_SESSION['ss_member_step'] = $step;
			db_commit_trans();
		}
	}
}

?>