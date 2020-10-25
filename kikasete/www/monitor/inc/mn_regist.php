<?
/******************************************************
' System :きかせて・netモニターページ
' Content:モニター登録情報クラス
'******************************************************/

class monitor_regist {
	var $active;
	var	$name1;
	var	$name2;
	var	$name1_kana;
	var	$name2_kana;
	var	$birthday_y;
	var	$birthday_m;
	var	$birthday_d;
	var	$sex;
	var	$mikikon;
	var	$mail_addr;
	var	$mail_addr2;
	var	$password;
	var	$password2;
	var	$jitaku_zip1;
	var	$jitaku_zip2;
	var	$jitaku_area;
	var	$shokugyou_cd;
	var	$gyoushu_cd;
	var	$shokushu_cd;
	var	$kinmu_zip1;
	var	$kinmu_zip2;
	var	$kinmu_area;
	var $conveni;
	var $super;
	var $genre;
	var $have_child;
	var $housing_form;
	var $have_car;
	var $ma_profile;
	var $html_mail;
	var $spouse_flg;
	var $spouse_other;
	var $spouse_birthday_y;
	var $spouse_birthday_m;
	var $spouse_birthday_d;
	var $spouse_shokugyo_cd;
	var $senior_flg;
	var $child;
	var $haimail_flag;

	// コンストラクタ
	function monitor_regist() {
		$this->active = true;
		$this->child = array();
	}

	// DBに保存
	function save_db() {
		global $cookie_pr_id, $cookie_affiliate_id;

		$sql = "SELECT wm_seq_no FROM w_monitor WHERE wm_mail_addr_low=" . sql_char(strtolower($this->mail_addr));
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$seq_no = $fetch->wm_seq_no;
			$sql = "UPDATE w_monitor SET wm_regist_date=current_timestamp,wm_name1=%s,wm_name2=%s,wm_name1_kana=%s,wm_name2_kana=%s,wm_birthday=%s,wm_sex=%s,wm_mikikon=%s,wm_mail_addr=%s,wm_mail_addr_low=%s,wm_password=%s,wm_jitaku_zip=%s,wm_jitaku_area=%s,wm_shokugyou_cd=%s,wm_gyoushu_cd=%s,wm_shokushu_cd=%s,wm_kinmu_zip=%s,wm_kinmu_area=%s,wm_conveni=%s,wm_super=%s,wm_genre=%s,wm_pr_id=%s,wm_affiliate_id=%s,wm_have_child=%s,wm_housing_form=%s,wm_have_car=%s,wm_ma_profile=%s,wm_html_mail=%s,wm_spouse_flg=%s,wm_spouse_other=%s,wm_spouse_birthday=%s,wm_spouse_shokugyo_cd=%s,wm_senior_flg=%s,wm_child_info=%s,wm_haimail_flag=%s WHERE wm_seq_no=$seq_no";
		} else {
			$sql = "INSERT INTO w_monitor (wm_name1,wm_name2,wm_name1_kana,wm_name2_kana,wm_birthday,wm_sex,wm_mikikon,wm_mail_addr,wm_mail_addr_low,wm_password,wm_jitaku_zip,wm_jitaku_area,wm_shokugyou_cd,wm_gyoushu_cd,wm_shokushu_cd,wm_kinmu_zip,wm_kinmu_area,wm_conveni,wm_super,wm_genre,wm_pr_id,wm_affiliate_id,wm_have_child,wm_housing_form,wm_have_car,wm_ma_profile,wm_html_mail,wm_spouse_flg,wm_spouse_other,wm_spouse_birthday,wm_spouse_shokugyo_cd,wm_senior_flg,wm_child_info,wm_haimail_flag) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		}
		$sql = sprintf($sql,
				sql_char($this->name1),
				sql_char($this->name2),
				sql_char($this->name1_kana),
				sql_char($this->name2_kana),
				sql_date("$this->birthday_y/$this->birthday_m/$this->birthday_d"),
				sql_number($this->sex),
				sql_number($this->mikikon),
				sql_char($this->mail_addr),
				sql_char(strtolower($this->mail_addr)),
				sql_char($this->password),
				sql_char($this->jitaku_zip1 . $this->jitaku_zip2),
				sql_number($this->jitaku_area),
				sql_number($this->shokugyou_cd),
				sql_number($this->gyoushu_cd),
				sql_number($this->shokushu_cd),
				sql_char($this->kinmu_zip1 . $this->kinmu_zip2),
				sql_number($this->kinmu_area),
				sql_char($this->conveni),
				sql_char($this->super),
				sql_char($this->genre),
				sql_number($cookie_pr_id),
				sql_number($cookie_affiliate_id),
				sql_number($this->have_child),
				sql_number($this->housing_form),
				sql_number($this->have_car),
				sql_char($this->ma_profile),
				sql_bool($this->html_mail),
				sql_char($this->spouse_flg),
				sql_char($this->spouse_flg == '3' ? $this->spouse_other : ''),
				sql_date2($this->spouse_birthday_y, $this->spouse_birthday_m, $this->spouse_birthday_d),
				sql_char($this->spouse_shokugyo_cd),
				sql_char($this->senior_flg),
				sql_char(serialize($this->child)),
				sql_bool($this->haimail_flag));
		db_exec($sql);

		if (!$seq_no)
			$seq_no = get_currval('w_monitor_wm_seq_no_seq');

		return $seq_no;
	}

	// 基本情報読み込み
	function read_db_b($monitor_id) {
		$sql = "SELECT * FROM t_monitor WHERE mn_monitor_id=$monitor_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$this->name1 = $fetch->mn_name1;
			$this->name2 = $fetch->mn_name2;
			$this->name1_kana = $fetch->mn_name1_kana;
			$this->name2_kana = $fetch->mn_name2_kana;
			$this->birthday_y = get_datepart('Y', $fetch->mn_birthday);
			$this->birthday_m = get_datepart('M', $fetch->mn_birthday);
			$this->birthday_d = get_datepart('D', $fetch->mn_birthday);
			$this->sex = $fetch->mn_sex;
			$this->mikikon = $fetch->mn_mikikon;
			$this->mail_addr = $fetch->mn_mail_addr;
			$this->mail_addr2 = $fetch->mn_mail_addr;
			$this->password = $fetch->mn_password;
			$this->password2 = $fetch->mn_password;
			$this->jitaku_zip1 = substr($fetch->mn_jitaku_zip, 0, 3);
			$this->jitaku_zip2 = substr($fetch->mn_jitaku_zip, 3, 4);
			$this->jitaku_area = $fetch->mn_jitaku_area;
			$this->shokugyou_cd = $fetch->mn_shokugyou_cd;
			$this->gyoushu_cd = $fetch->mn_gyoushu_cd;
			$this->shokushu_cd = $fetch->mn_shokushu_cd;
			$this->kinmu_zip1 = substr($fetch->mn_kinmu_zip, 0, 3);
			$this->kinmu_zip2 = substr($fetch->mn_kinmu_zip, 3, 4);
			$this->kinmu_area = $fetch->mn_kinmu_area;
			$this->html_mail = $fetch->mn_html_mail;
			$this->haimail_flag = $fetch->mn_haimail_flag;
		}
	}

	// 基本情報更新
	function update_db_b($monitor_id) {
		$sql = sprintf("UPDATE t_monitor SET mn_update_date=current_timestamp,mn_name1=%s,mn_name2=%s,mn_name1_kana=%s,mn_name2_kana=%s,mn_birthday=%s,mn_sex=%s,mn_mikikon=%s,mn_mail_addr=%s,mn_mail_addr_low=%s,mn_password=%s,mn_jitaku_zip=%s,mn_jitaku_area=%s,mn_shokugyou_cd=%s,mn_gyoushu_cd=%s,mn_shokushu_cd=%s,mn_kinmu_zip=%s,mn_kinmu_area=%s,mn_html_mail=%s,mn_haimail_flag=%s WHERE mn_monitor_id=$monitor_id",
				sql_char($this->name1),
				sql_char($this->name2),
				sql_char($this->name1_kana),
				sql_char($this->name2_kana),
				sql_date2($this->birthday_y, $this->birthday_m, $this->birthday_d),
				sql_number($this->sex),
				sql_number($this->mikikon),
				sql_char($this->mail_addr),
				sql_char(strtolower($this->mail_addr)),
				sql_char($this->password),
				sql_char($this->jitaku_zip1 . $this->jitaku_zip2),
				sql_number($this->jitaku_area),
				sql_number($this->shokugyou_cd),
				sql_number($this->gyoushu_cd),
				sql_number($this->shokushu_cd),
				sql_char($this->kinmu_zip1 . $this->kinmu_zip2),
				sql_number($this->kinmu_area),
				sql_bool($this->html_mail),
				sql_bool($this->haimail_flag));
		db_exec($sql);

		// 不達状態をリセット
		$sql = "UPDATE t_monitor SET mn_status=0 WHERE mn_monitor_id=$monitor_id AND mn_status=2";
		db_exec($sql);
		$sql = "DELETE FROM t_err_mail WHERE er_member_kind=1 AND er_member_id=$monitor_id";
		db_exec($sql);
	}

	// 生活情報読み込み
	function read_db_l($monitor_id) {
		$sql = "SELECT mn_have_child,mn_housing_form,mn_have_car,mn_spouse_flg,mn_spouse_other,mn_spouse_birthday,mn_spouse_shokugyo_cd,mn_senior_flg FROM t_monitor WHERE mn_monitor_id=$monitor_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$this->have_child = $fetch->mn_have_child;
			$this->housing_form = $fetch->mn_housing_form;
			$this->have_car = $fetch->mn_have_car;
			$this->spouse_flg = $fetch->mn_spouse_flg;
			$this->spouse_other = $fetch->mn_spouse_other;
			$this->spouse_birthday_y = get_datepart('Y', $fetch->mn_spouse_birthday);
			$this->spouse_birthday_m = get_datepart('M', $fetch->mn_spouse_birthday);
			$this->spouse_birthday_d = get_datepart('D', $fetch->mn_spouse_birthday);
			$this->spouse_shokugyo_cd = $fetch->mn_spouse_shokugyo_cd;
			$this->senior_flg = $fetch->mn_senior_flg;
		}

		$sql = "SELECT dc_conveni_cd FROM t_conveni WHERE dc_monitor_id=$monitor_id ORDER BY dc_conveni_cd";
		$this->conveni = $this->read_life_info($sql);

		$sql = "SELECT ds_super_cd FROM t_super WHERE ds_monitor_id=$monitor_id ORDER BY ds_super_cd";
		$this->super = $this->read_life_info($sql);

		$sql = "SELECT dk_genre_cd FROM t_genre WHERE dk_monitor_id=$monitor_id ORDER BY dk_genre_cd";
		$this->genre = $this->read_life_info($sql);

		$sql = "SELECT mnp_profile_cd FROM t_monitor_profile WHERE mnp_monitor_id=$monitor_id ORDER BY mnp_profile_cd";
		$this->ma_profile = $this->read_life_info($sql);

		$this->child = array();
		$sql = "SELECT mc_sex,mc_birthday FROM t_monitor_child WHERE mc_monitor_id=$monitor_id ORDER BY mc_child_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$this->child[$i]->sex = $fetch->mc_sex;
			$this->child[$i]->birthday_y = get_datepart('Y', $fetch->mc_birthday);
			$this->child[$i]->birthday_m = get_datepart('M', $fetch->mc_birthday);
			$this->child[$i]->birthday_d = get_datepart('D', $fetch->mc_birthday);
		}
	}

	function read_life_info($sql) {
		$result = db_exec($sql);
		$nrow = pg_numrows($result);

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_row($result, $i);
			if ($code != '')
				$code .= ',';
			$code .= $fetch[0];
		}

		return $code;
	}

	// 生活情報書き込み
	function update_db_l($monitor_id) {
		db_begin_trans();

		// 子供の有無・住居形態・車の所有
		$sql = sprintf("UPDATE t_monitor SET mn_update_date=current_timestamp,mn_have_child=%s,mn_housing_form=%s,mn_have_car=%s,mn_spouse_flg=%s,mn_spouse_other=%s,mn_spouse_birthday=%s,mn_spouse_shokugyo_cd=%s,mn_senior_flg=%s WHERE mn_monitor_id=$monitor_id",
				sql_number($this->have_child),
				sql_number($this->housing_form),
				sql_number($this->have_car),
				sql_number($this->spouse_flg),
				sql_char($this->spouse_flg == '3' ? $this->spouse_other : ''),
				sql_date2($this->spouse_flg == '1' ? $this->spouse_birthday_y : '', $this->spouse_birthday_m, $this->spouse_birthday_d),
				sql_number($this->spouse_flg == '1' ? $this->spouse_shokugyo_cd : ''),
				sql_number($this->senior_flg));
		db_exec($sql);

		// よく行くコンビニ
		$sql = "DELETE FROM t_conveni WHERE dc_monitor_id=$monitor_id";
		db_exec($sql);
		if ($this->conveni != '') {
			$sql = "INSERT INTO t_conveni (dc_monitor_id,dc_conveni_cd)"
					. " SELECT $monitor_id,cv_conveni_cd FROM m_conveni WHERE cv_conveni_cd IN ($this->conveni)";
			db_exec($sql);
		}

		// よく行くスーパー
		$sql = "DELETE FROM t_super WHERE ds_monitor_id=$monitor_id";
		db_exec($sql);
		if ($this->super != '') {
			$sql = "INSERT INTO t_super (ds_monitor_id,ds_super_cd)"
					. " SELECT $monitor_id,sp_super_cd FROM m_super WHERE sp_super_cd IN ($this->super)";
			db_exec($sql);
		}

		// 興味ジャンル
		$sql = "DELETE FROM t_genre WHERE dk_monitor_id=$monitor_id";
		db_exec($sql);
		if ($this->genre != '') {
			$sql = "INSERT INTO t_genre (dk_monitor_id,dk_genre_cd)"
					. " SELECT $monitor_id,kg_genre_cd FROM m_genre WHERE kg_genre_cd IN ($this->genre)";
			db_exec($sql);
		}

		// 追加プロファイル
		$sql = "DELETE FROM t_monitor_profile WHERE mnp_monitor_id=$monitor_id";
		db_exec($sql);
		if ($this->ma_profile != '') {
			$sql = "INSERT INTO t_monitor_profile (mnp_monitor_id,mnp_profile_cd)"
					. " SELECT $monitor_id,pfs_profile_cd FROM t_profile_sel WHERE pfs_profile_cd IN ($this->ma_profile)";
			db_exec($sql);
		}

		$sql = "DELETE FROM t_monitor_child WHERE mc_monitor_id=$monitor_id";
		db_exec($sql);
		if ($this->have_child == '1') {
			$child_no = 1;
			foreach ($this->child as $child) {
				$sql = sprintf("INSERT INTO t_monitor_child (mc_monitor_id,mc_child_no,mc_sex,mc_birthday) VALUES (%s,%s,%s,%s)",
						sql_number($monitor_id),
						sql_number($child_no++),
						sql_number($child->sex),
						sql_date2($child->birthday_y, $child->birthday_m, $child->birthday_d));
				db_exec($sql);
			}
		}

		db_commit_trans();
	}
}
?>