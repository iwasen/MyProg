<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報クラス
'******************************************************/

class eng {
	var $bd_y;
	var $bd_m;

	var $wt_use;
	var $wt_use_flag;
	var $wt_sum;
	var $wt_sum_flag;
	var $gl_use;
	var $gl_use_flag;
	var $gl_sum;
	var $gl_sum_flag;
	var $gm_use;
	var $gm_use_flag;
}

class fiducial2 {
	var $active;
	var $sys_ym;
	var $start_y;
	var $start_m;

	var $wt_input_use;
	var $wt_input_sum;
	var $gl_input_use;
	var $gl_input_sum;
	var $gm_input_use;

	var $edit_ym;
	var $edit_eng_cd;
	var $edit_old_volume;
	var $edit_new_volume;
	var $edit_old_sum;
	var $edit_new_sum;
	var $edit_reason;

	// コンストラクタ
	function fiducial() {
		$this->active = true;
	}

	// 開始年月取得
	function set_start_ym2() {
		$fetch = get_system_info('sy_batch_month');
		$sys_y = get_datepart('Y', $fetch->sy_batch_month);
		$sys_m = get_datepart('M', $fetch->sy_batch_month);
		$this->start_y = date("Y", mktime(0,0,0,$sys_m, 1, $sys_y - 1));
		$this->start_m = date("m", mktime(0,0,0,$sys_m, 1, $sys_y - 1));
	}

	// 過去データの読み込み
	function read_db2() {

		$year = $this->start_y;
		$month = $this->start_m;

		function get_m1($i, $year, $month) {
			return (int)(date("m", mktime (0,0,0,$month + $i,1,$year)));
		}
		function get_y1($i, $year, $month) {
			return (int)(date("Y", mktime (0,0,0,$month + $i,1,$year)));
		}

		$sql = "select * from t_base_data where bd_mb_seq_no=" . sql_number($_SESSION['ss_seq_no']) ." and bd_month >= " . sql_date("$this->start_y/$this->start_m/01") . " order by bd_month limit 12";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$eng = &$this->eng[get_m1($i,$year,$month)];
			$eng->bd_y = get_datepart('Y', $fetch->bd_month);
			$eng->bd_m = get_datepart('M', $fetch->bd_month);
			$eng->wt_use = $fetch->bd_wt_use;
			$eng->wt_use_flag = $fetch->bd_wt_use_flag;
			$eng->wt_sum = $fetch->bd_wt_sum;
			$eng->wt_sum_flag = $fetch->bd_wt_sum_flag;
			$eng->gl_use = $fetch->bd_gl_use;
			$eng->gl_use_flag = $fetch->bd_gl_use_flag;
			$eng->gl_sum = $fetch->bd_gl_sum;
			$eng->gl_sum_flag = $fetch->bd_gl_sum_flag;
			$eng->gm_use = $fetch->bd_gm_use;
			$eng->gm_use_flag = $fetch->bd_gm_use_flag;
		}
	}

	// データ保存
	function save_edit() {
		function null2zero($num) {
			if (!$num)
				return 0;
			else
				return $num;
		}

		db_begin_trans();

		$sql = "select bd_seq_no from t_base_data where bd_mb_seq_no={$_SESSION['ss_seq_no']} and bd_month=" . sql_date($this->edit_ym."/01");
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, $i);
			$use = "bd_{$this->edit_eng_cd}_use";
			$use_flag = "bd_{$this->edit_eng_cd}_use_flag";
			$sum = "bd_{$this->edit_eng_cd}_sum";
			$sum_flag = "bd_{$this->edit_eng_cd}_sum_flag";

			if ($this->edit_eng_cd != 'gm')
				$set = ", {$sum} = {$this->edit_new_sum}, {$sum_flag} = 1";

			$sql = "update t_base_data set {$use} = {$this->edit_new_volume}, {$use_flag} = 1 $set"
					. " where bd_mb_seq_no={$_SESSION['ss_seq_no']} and bd_month=" . sql_date($this->edit_ym."/01");
			db_exec($sql);

			$sql = sprintf("insert into t_edit_data (ed_mb_seq_no,ed_month,ed_eng_cd,ed_use_sum,ed_old_volume,ed_new_volume,ed_reason,ed_old_sum,ed_new_sum) values"
					. " (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
					sql_number($_SESSION['ss_seq_no']),
					sql_date($this->edit_ym."/01"),
					sql_char($this->edit_eng_cd),
					0,
					sql_number(null2zero($this->edit_old_volume)),
					sql_number($this->edit_new_volume),
					sql_char($this->edit_reason),
					sql_number(null2zero($this->edit_old_sum)),
					sql_number(null2zero($this->edit_new_sum)));
			db_exec($sql);
		}
		db_commit_trans();
	}
}

?>