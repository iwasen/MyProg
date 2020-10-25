<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報クラス
'******************************************************/

class eng {
	var $bd_seq_no;
	var $bd_y;
	var $bd_m;

	var $el_use;
	var $el_use_flag;
	var $el_sum;
	var $el_sum_flag;
	var $gs_use;
	var $gs_use_flag;
	var $gs_sum;
	var $gs_sum_flag;
	var $ol_use;
	var $ol_use_flag;
	var $ol_sum;
	var $ol_sum_flag;
	var $wt_use;
	var $wt_use_flag;
	var $wt_sum;
	var $wt_sum_flag;
	var $gm_use;
	var $gm_use_flag;
	var $gl_use;
	var $gl_use_flag;
	var $gl_sum;
	var $gl_sum_flag;

	var $edit_use;
	var $edit_sum;
}

class fiducial1 {
	var $active;
	var $start_ym;
	var $start_y;
	var $start_m;
	var $bd_m;
	var $en;
	var $edit_no;

	var $edit_el_use;
	var $edit_el_sum;
	var $edit_gs_use;
	var $edit_gs_sum;
	var $edit_wt_use;
	var $edit_wt_sum;
	var $edit_gm_use;
	var $edit_ol_use;
	var $edit_ol_sum;
	var $edit_gl_use;
	var $edit_gl_sum;

	// コンストラクタ
	function fiducial() {
		$this->active = true;
	}

	// 開始年月取得
	function set_start_ym() {
		$fetch = get_system_info('sy_batch_month');
		if ($_SESSION['ss_cr_ym']) {
			$this->start_ym = add_date($_SESSION['ss_cr_ym'], 0, -11, 0);
			$this->start_y = get_datepart('Y', $this->start_ym);
			$this->start_m = get_datepart('M', $this->start_ym);
		} else {
			$this->start_ym = add_date($fetch->sy_batch_month, -1, 0, 0);
			$this->start_y = get_datepart('Y', $this->start_ym);
			$this->start_m = get_datepart('M', $this->start_ym);
		}
		if ($_SESSION['ss_cr_ym'] == $fetch->sy_batch_month) {
			$this->bd_m = date(m);
		} else {
			$this->bd_m = get_datepart('M', $fetch->sy_batch_month);
		}
	}

	function read_db1() {

		$year = $this->start_y;
		$month = $this->start_m;

		$sql = "select * from t_base_data where bd_mb_seq_no=" . sql_number($_SESSION['ss_seq_no']) ." and bd_month >= " . sql_date("$this->start_y/$this->start_m/01") . " order by bd_month limit 12";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$eng = &$this->eng[$i];
			$eng->bd_seq_no = $fetch->bd_seq_no;
			$eng->bd_y = get_datepart('Y', $fetch->bd_month);
			$eng->bd_m = get_datepart('M', $fetch->bd_month);
			$eng->el_use = $fetch->bd_el_use;
			$eng->el_use_flag = $fetch->bd_el_use_flag;
			$eng->el_sum = $fetch->bd_el_sum;
			$eng->el_sum_flag = $fetch->bd_el_sum_flag;
			$eng->gs_use = $fetch->bd_gs_use;
			$eng->gs_use_flag = $fetch->bd_gs_use_flag;
			$eng->gs_sum = $fetch->bd_gs_sum;
			$eng->gs_sum_flag = $fetch->bd_gs_sum_flag;
			$eng->ol_use = $fetch->bd_ol_use;
			$eng->ol_use_flag = $fetch->bd_ol_use_flag;
			$eng->ol_sum = $fetch->bd_ol_sum;
			$eng->ol_sum_flag = $fetch->bd_ol_sum_flag;
			$eng->wt_use = $fetch->bd_wt_use;
			$eng->wt_use_flag = $fetch->bd_wt_use_flag;
			$eng->wt_sum = $fetch->bd_wt_sum;
			$eng->wt_sum_flag = $fetch->bd_wt_sum_flag;
			$eng->gm_use = $fetch->bd_gm_use;
			$eng->gm_use_flag = $fetch->bd_gm_use_flag;
			$eng->gl_use = $fetch->bd_gl_use;
			$eng->gl_use_flag = $fetch->bd_gl_use_flag;
			$eng->gl_sum = $fetch->bd_gl_sum;
			$eng->gl_sum_flag = $fetch->bd_gl_sum_flag;
		}
	}

	// データ保存(登録から２ヶ月未満の場合)
	function save_db1(){
		$use = $this->en . "_use";
		$sum = $this->en . "_sum";
		$use_column = "bd_" . $this->en . "_use";
		$sum_column = "bd_" . $this->en . "_sum";
		$use_flag_column = "bd_" . $this->en . "_use_flag";
		$sum_flag_column = "bd_" . $this->en . "_sum_flag";

		// トランザクション開始
		db_begin_trans();

		for ($i = 0; $i < 12; $i++) {
			$eng = &$this->eng[$i];
			$use_flag = $eng->edit_use == $eng->$use?'':1;
			$sum_flag = $eng->edit_sum == $eng->$sum?'':1;
			if ($use_flag == 1 || $sum_flag == 1) {
				if ($this->en != 'gm') {
					$sql = "UPDATE t_base_data SET $use_column=" . sql_number($eng->edit_use)
						. ", $use_flag_column=1"
						. ", $sum_column=" . sql_number($eng->edit_sum)
						. ", $sum_flag_column=1"
						. ", bd_regist_date='now'"
						. " WHERE bd_seq_no=" . $eng->bd_seq_no;
					} else {
					$sql = "UPDATE t_base_data SET $use_column=" . sql_number($eng->edit_use)
						. ", $use_flag_column=1"
						. ", bd_regist_date='now'"
						. " WHERE bd_seq_no=" . $eng->bd_seq_no;
					}
					db_exec($sql);

					// 修正ログ書き出し
					$sql = "INSERT INTO t_edit_data (ed_mb_seq_no,ed_month,ed_eng_cd,ed_use_sum,ed_old_volume,ed_new_volume,ed_reason,ed_old_sum,ed_new_sum)"
						 . " VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s);";
					$sql = sprintf($sql,
						sql_number($_SESSION['ss_seq_no']),
						sql_date($eng->bd_y . "-" . $eng->bd_m . "-01"),
						sql_char($this->en),
						0,
						sql_number($eng->$use),
						sql_number($eng->edit_use),
						sql_char('本人による一括変更'),
						sql_number($eng->$sum),
						sql_number($eng->edit_sum));
					db_exec($sql);
			}

		}
		// コミット
		db_commit_trans();
	}

	// データ保存(登録から２ヶ月以上経過の場合)
	function save_edit() {

		function save_data($eng, $old_use, $new_use, $old_sum, $new_sum, $bd_seq_no,$bd_month,$reason) {

			$old_use = $old_use != '' ? $old_use:0;
			$old_sum = $old_sum != '' ? $old_sum:0;

			// t_base_data書き換え
			if ($eng != 'gm') {
				$sql = "UPDATE t_base_data SET "
					. "bd_".$eng."_use = " . sql_number($new_use)
					. ", bd_".$eng."_use_flag=1"
					. ",bd_".$eng."_sum = " . sql_number($new_sum)
					. ",bd_".$eng."_sum_flag=1"
					. ", bd_regist_date='now'"
					. " WHERE bd_seq_no=" . sql_number($bd_seq_no);
			} else {
				$sql = "UPDATE t_base_data SET "
					. "bd_".$eng."_use = " . sql_number($new_use)
					. ", bd_".$eng."_use_flag=1"
					. ", bd_regist_date='now'"
					. " WHERE bd_seq_no=" . sql_number($bd_seq_no);
			}
			db_exec($sql);

			// t_edit_data書込み
			$sql = "INSERT INTO t_edit_data (ed_mb_seq_no,ed_month,ed_eng_cd,ed_use_sum,ed_old_volume,ed_new_volume,ed_reason,ed_old_sum,ed_new_sum)"
				 . " VALUES (%s,%s,%s,0,%s,%s,%s,%s,%s);";
			$sql = sprintf($sql,
				sql_number($_SESSION['ss_seq_no']),
				sql_date($bd_month),
				sql_char($eng),
				sql_number($old_use),
				sql_number($new_use),
				sql_char($reason),
				sql_number($old_sum),
				sql_number($new_sum));
			db_exec($sql);
		}

		$eng = &$this->eng[$this->edit_no];

		$eng->el_use = $eng->el_use != ''?$eng->el_use:0;
		$eng->el_sum = $eng->el_sum != ''?$eng->el_sum:0;
		$eng->gs_use = $eng->gs_use != ''?$eng->gs_use:0;
		$eng->gs_sum = $eng->gs_sum != ''?$eng->gs_sum:0;
		$eng->wt_use = $eng->wt_use != ''?$eng->wt_use:0;
		$eng->wt_sum = $eng->wt_sum != ''?$eng->wt_sum:0;
		$eng->gm_use = $eng->gm_use != ''?$eng->gm_use:0;
		$eng->ol_use = $eng->ol_use != ''?$eng->ol_use:0;
		$eng->ol_sum = $eng->ol_sum != ''?$eng->ol_sum:0;
		$eng->gl_use = $eng->gl_use != ''?$eng->gl_use:0;
		$eng->gl_sum = $eng->gl_sum != ''?$eng->gl_sum:0;

		$bd_month = $eng->bd_y . "-" . $eng->bd_m . "-01";
		$sql = "SELECT bd_seq_no FROM t_base_data WHERE bd_mb_seq_no=" . $_SESSION['ss_seq_no'] . " AND bd_month=" . sql_date($bd_month);
		$bd_seq_no = db_fetch1($sql);

		// トランザクション開始
		db_begin_trans();

		if ($eng->el_use != $this->edit_el_use || $eng->el_sum != $this->edit_el_sum)
			save_data('el', $eng->el_use, $this->edit_el_use, $eng->el_sum, $this->edit_el_sum, $bd_seq_no,$bd_month,$this->reason);
		if ($eng->gs_use != $this->edit_gs_use || $eng->gs_sum != $this->edit_gs_sum)
			save_data('gs', $eng->gs_use, $this->edit_gs_use, $eng->gs_sum, $this->edit_gs_sum, $bd_seq_no,$bd_month,$this->reason);
		if ($eng->wt_use != $this->edit_wt_use || $eng->wt_sum != $this->edit_wt_sum)
			save_data('wt', $eng->wt_use, $this->edit_wt_use, $eng->wt_sum, $this->edit_wt_sum, $bd_seq_no,$bd_month,$this->reason);
		if ($eng->gm_use != $this->edit_gm_use)
			save_data('gm', $eng->gm_use, $this->edit_gm_use, $eng->gm_sum, $this->edit_gm_sum, $bd_seq_no,$bd_month,$this->reason);
		if ($eng->ol_use != $this->edit_ol_use || $eng->ol_sum != $this->edit_ol_sum)
			save_data('ol', $eng->ol_use, $this->edit_ol_use, $eng->ol_sum, $this->edit_ol_sum, $bd_seq_no,$bd_month,$this->reason);
		if ($eng->gl_use != $this->edit_gl_use || $eng->gl_sum != $this->edit_gl_sum)
			save_data('gl', $eng->gl_use, $this->edit_gl_use, $eng->gl_sum, $this->edit_gl_sum, $bd_seq_no,$bd_month,$this->reason);

		// コミット
		db_commit_trans();
	}
}
?>