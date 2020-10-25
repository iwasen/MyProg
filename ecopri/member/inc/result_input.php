<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:月極実績データ
'******************************************************/

class result_input {
	var $active;
	var $bd_seq_no;
	var $bd_y;
	var $bd_m;
	var $sys_ym;
	var $last_day;
	var $next_month;
	var $before_month;
	var $commit_flag;
	var $el_use;
	var $el_sum;
	var $gs_use;
	var $gs_sum;
	var $wt_use;
	var $wt_sum;
	var $wt_use_input;
	var $wt_sum_input;
	var $gm_use;
	var $gm_flag;
	var $gl_use;
	var $gl_sum;
	var $gl_flag;
	var $ol_use;
	var $ol_sum;
	var $gl_flag;

	var $gm_use_d;
	var $gl_use_d;
	var $gl_sum_d;
	var $ol_use_d;
	var $ol_sum_d;

	var $mb_regist_month;
	var $mb_wt_month;
	var $mb_gas_kind_cd;

	var $mb_ol_flag;
	var $mb_gm_flag;
	var $mb_gl_flag;
	var $gm_inp_cd;
	var $gl_inp_cd;
	var $ol_inp_cd;

	// コンストラクタ
	function result_input() {
		$this->active = true;
	}

	// データ読み込み
	function read_db() {
		$sql = "select * from t_base_data left join t_daily_data on bd_seq_no=dd_bd_seq_no where bd_mb_seq_no=" . $_SESSION['ss_seq_no'] . " and bd_month=" . sql_date("$this->bd_y/$this->bd_m/01");
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->bd_seq_no = $fetch->bd_seq_no;
			$this->el_use = $fetch->bd_el_use;
			$this->el_sum = $fetch->bd_el_sum;
			$this->gs_use = $fetch->bd_gs_use;
			$this->gs_sum = $fetch->bd_gs_sum;
			$this->gm_use = $fetch->dd_gm_use;
			$this->gm_flag = $fetch->dd_gm_flag;
			$this->gl_use = $fetch->dd_gl_use;
			$this->gl_sum = $fetch->dd_gl_sum;
			$this->gl_flag = $fetch->dd_gl_flag;
			$this->ol_use = $fetch->dd_ol_use;
			$this->ol_sum = $fetch->dd_ol_sum;
			$this->ol_flag = $fetch->dd_ol_flag;
			$this->wt_use = $fetch->bd_wt_use;
			$this->wt_sum = $fetch->bd_wt_sum;
			$this->commit_flag = $fetch->bd_commit_flag;
			$this->wt_use_input = $fetch->bd_wt_use_input;
			$this->wt_sum_input = $fetch->bd_wt_sum_input;
			$this->gm_inp_cd = $fetch->bd_gm_inp_cd;
			$this->gl_inp_cd = $fetch->bd_gl_inp_cd;
			$this->ol_inp_cd = $fetch->bd_ol_inp_cd;
		} else {
			$this->bd_seq_no = 0;
			$this->el_use = '';
			$this->el_sum = '';
			$this->gs_use = '';
			$this->gs_sum = '';
			$this->wt_use = '';
			$this->wt_sum = '';
			$this->gm_use = '';
			$this->gm_flag = '';
			$this->gl_use = '';
			$this->gl_sum = '';
			$this->gl_flag = '';
			$this->ol_use = '';
			$this->ol_sum = '';
			$this->ol_flag = '';
			$this->wt_use = '';
			$this->wt_sum = '';
			$this->commit_flag = 0;
			$this->wt_sum_input = '';
			$this->wt_sum_input = '';
			$this->gm_inp_cd = '';
			$this->gl_inp_cd = '';
			$this->ol_inp_cd = '';
		}
	}

	// 会員登録情報取得
	function get_member_info() {
		$sql = "select mb_gas_kind_cd,mb_water_month,mb_regist_month,mb_ol_flag,mb_gm_flag,mb_gl_flag from t_member where mb_seq_no={$_SESSION['ss_seq_no']}";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->mb_gas_kind_cd = $fetch->mb_gas_kind_cd;
			$this->mb_regist_month = $fetch->mb_regist_month;
			$this->mb_wt_month = $fetch->mb_water_month;
			$this->mb_ol_flag = $fetch->mb_ol_flag;
			$this->mb_gm_flag = $fetch->mb_gm_flag;
			$this->mb_gl_flag = $fetch->mb_gl_flag;
		}
	}

	// 処理月取得
	function get_ym() {
		$fetch = get_system_info('sy_batch_month');
		$this->sys_ym = $fetch->sy_batch_month;
		if ($_SESSION['ss_cr_ym'] == $this->sys_ym) {
			$this->bd_y = date(Y);
			$this->bd_m = date(m);
		} else {
			$this->bd_y = get_datepart('Y', $this->sys_ym);
			$this->bd_m = get_datepart('M', $this->sys_ym);
		}
	}

	// 末日取得
	function get_last_day($year,$month) {
		for ($i = 31; $i > 27; $i--) {
			$date = mktime (0,0,0,$month, $i, $year);
			if (date("m", $date) == $this->bd_m) {
				$this->last_day = date("d", $date);
				return date("d", $date);
				break;
			}
		}
	}

	// デーリー入力表示ページチェック
	function disp_page($eng) {

		$sql = "SELECT mb_ol_flag,mb_gm_flag,mb_gl_flag,bd_gm_inp_cd,bd_gl_inp_cd,bd_ol_inp_cd"
			. " FROM t_member LEFT JOIN t_base_data ON mb_seq_no=bd_mb_seq_no AND bd_month=" . sql_date($this->bd_y . "-" . $this->bd_m . "-01")
			. " WHERE mb_seq_no=" . $_SESSION['ss_seq_no'];
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			switch($eng) {
				case 'gm':
					$mb_flag = $fetch->mb_gm_flag;
					$bd_cd = $fetch->bd_gm_inp_cd;
					break;
				case 'ol':
					$mb_flag = $fetch->mb_ol_flag;
					$bd_cd = $fetch->bd_ol_inp_cd;
					break;
				case 'gl':
					$mb_flag = $fetch->mb_gl_flag;
					$bd_cd = $fetch->bd_gl_inp_cd;
					break;
			}
		}
		if ($mb_flag == '')
			redirect('result_input_set.php');
		elseif ($mb_flag == 0)
			redirect('main.php');
		elseif ($bd_cd != '')
			redirect('result_input.php');
	}

	// デイリーデータ保存
	function save_daily_data($seq_no, $kind) {

		db_begin_trans();

		$sql = "select bd_seq_no,dd_bd_seq_no from t_base_data left join t_daily_data on bd_seq_no=dd_bd_seq_no where bd_mb_seq_no=" . sql_number($seq_no) ." and bd_month=" . sql_date("$this->bd_y/$this->bd_m/01");
		$result = db_exec($sql);
		if (!pg_numrows($result)) {
			$sql = "insert into t_base_data (bd_mb_seq_no,bd_month) values (" . sql_number($seq_no) . ","	. sql_date("$this->bd_y/$this->bd_m/01") . ")";
			db_exec($sql);
			$bd_seq_no = get_currval('t_base_data_bd_seq_no_seq');
		} else {
			$fetch = pg_fetch_object($result, 0);
			$bd_seq_no = $fetch->bd_seq_no;
			$dd_bd_seq_no = $fetch->dd_bd_seq_no;
		}
		if ($dd_bd_seq_no) {
			if ($kind == 'gm')
				$sql = "update t_daily_data set dd_gm_use =" . sql_char($this->gm_use) . " where dd_bd_seq_no = $fetch->bd_seq_no";
			elseif ($kind == 'gl')
				$sql = "update t_daily_data set dd_gl_use =" . sql_char($this->gl_use) . ", dd_gl_sum=" . sql_char($this->gl_sum)
					 . " where dd_bd_seq_no = $fetch->bd_seq_no";
			elseif ($kind == 'ol')
				$sql = "update t_daily_data set dd_ol_use =" . sql_char($this->ol_use) . ", dd_ol_sum=" . sql_char($this->ol_sum)
					 . " where dd_bd_seq_no = $fetch->bd_seq_no";
		} else {
			if ($kind == 'gm')
				$sql = "insert into t_daily_data (dd_bd_seq_no, dd_gm_use) values (" . sql_number($bd_seq_no) . "," . sql_char($this->gm_use) . ")";
			elseif ($kind == 'gl')
				$sql = "insert into t_daily_data (dd_bd_seq_no, dd_gl_use, dd_gl_sum) values"
						. " (" . sql_number($bd_seq_no) . "," . sql_char($this->gl_use) . "," . sql_char($this->gl_sum) . ")";
			elseif ($kind == 'ol')
				$sql = "insert into t_daily_data (dd_bd_seq_no, dd_ol_use, dd_ol_sum) values"
						. " (" . sql_number($bd_seq_no) . "," . sql_char($this->ol_use) . "," . sql_char($this->ol_sum) . ")";
		}
		db_exec($sql);

		db_commit_trans();
	}

	// デイリー入力データ集計
	function daily_count($seq_no) {

		// カンマ区切りデータの集計
		function add_data($data) {
			if ($data) {
				return array_sum(explode(",",$data));
			} else
				return 0;
		}

		$sql = "select dd_gm_use,dd_gm_flag,dd_gl_use,dd_gl_sum,dd_gl_flag,dd_ol_use,dd_ol_sum,dd_ol_flag from t_base_data left join t_daily_data on bd_seq_no=dd_bd_seq_no where bd_mb_seq_no=" . sql_number($seq_no) . " and bd_month=" . sql_date("$this->bd_y/$this->bd_m/01");
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->gm_use = add_data($fetch->dd_gm_use);
			$this->gm_flag = $fetch->dd_gm_flag;
			$this->gl_use = add_data($fetch->dd_gl_use);
			$this->gl_sum = add_data($fetch->dd_gl_sum);
			$this->gl_flag = $fetch->dd_gl_flag;
			$this->ol_use = add_data($fetch->dd_ol_use);
			$this->ol_sum = add_data($fetch->dd_ol_sum);
			$this->ol_flag = $fetch->dd_ol_flag;

		}
	}
	// 月次データ保存
	function save_db($seq_no, $fix) {

		// commit_flagは/inc/inp_close.phpで立てる
		$fix = 0;
		db_begin_trans();

		$this->gm_inp_cd = $this->mb_gm_flag == 0 ? 3 : $this->gm_inp_cd;
		$this->gl_inp_cd = $this->mb_gl_flag == 0 ? 3 : $this->gl_inp_cd;
		$this->ol_inp_cd = $this->mb_ol_flag == 0 ? 3 : $this->ol_inp_cd;

		$this->wt_use = $this->wt_use_input;
		$this->wt_sum = $this->wt_sum_input;

		$sql = "select bd_seq_no from t_base_data where bd_mb_seq_no=" . sql_number($seq_no) . " and bd_month=" . sql_date("$this->bd_y/$this->bd_m/01");
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$bd_seq_no = $fetch->bd_seq_no;
			$sql = "update t_base_data set bd_mb_seq_no=%s,bd_month=%s,bd_el_use=%s,bd_el_sum=%s,bd_gs_use=%s,bd_gs_sum=%s,bd_ol_use=%s,bd_ol_sum=%s"
				. ",bd_wt_use=%s,bd_wt_sum=%s,bd_gl_use=%s,bd_gl_sum=%s,bd_gm_use=%s,bd_wt_use_input=%s,bd_wt_sum_input=%s,bd_commit_flag=%s,"
				. "bd_regist_date='now',bd_gm_inp_cd=%s,bd_gl_inp_cd=%s,bd_ol_inp_cd=%s"
						. " where bd_seq_no=" . sql_number($bd_seq_no);
		} else {
			$sql = "INSERT INTO t_base_data (bd_mb_seq_no,bd_month,bd_el_use,bd_el_sum,bd_gs_use,bd_gs_sum,bd_ol_use,bd_ol_sum,bd_wt_use,bd_wt_sum"
				. ",bd_gl_use,bd_gl_sum,bd_gm_use,bd_wt_use_input,bd_wt_sum_input,bd_commit_flag,bd_gm_inp_cd,bd_gl_inp_cd,bd_ol_inp_cd)"
				. " values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		}
		$sql = sprintf($sql,
				sql_number($seq_no),
				sql_date("$this->bd_y/$this->bd_m/01"),
				sql_number($this->el_use == '' ? 0 : $this->el_use),
				sql_number($this->el_sum == '' ? 0 : $this->el_sum),
				sql_number($this->gs_use == '' ? 0 : $this->gs_use),
				sql_number($this->gs_sum == '' ? 0 : $this->gs_sum),
				sql_number($this->ol_use == '' ? 0 : $this->ol_use),
				sql_number($this->ol_sum == '' ? 0 : $this->ol_sum),
				sql_number($this->wt_use == '' ? 0 : $this->wt_use),
				sql_number($this->wt_sum == '' ? 0 : $this->wt_sum),
				sql_number($this->gl_use == '' ? 0 : $this->gl_use),
				sql_number($this->gl_sum == '' ? 0 : $this->gl_sum),
				sql_number($this->gm_use == '' ? 0 : $this->gm_use),
				sql_number($this->wt_use_input),
				sql_number($this->wt_sum_input),
				sql_number($fix),
				sql_number($this->gm_inp_cd),
				sql_number($this->gl_inp_cd),
				sql_number($this->ol_inp_cd));
		db_exec($sql);

		if (!$bd_seq_no)
			$bd_seq_no = get_currval('t_base_data_bd_seq_no_seq');
		db_commit_trans();

		return $bd_seq_no;
	}

	// 入力しない理由保存
	function no_inp_reason($eng, $cd) {
		$sql = "SELECT bd_seq_no FROM t_base_data WHERE bd_mb_seq_no=" . $_SESSION['ss_seq_no'] . " AND bd_month=" . sql_date("$this->bd_y/$this->bd_m/01");
		$bd_seq_no = db_fetch1($sql);

		switch($eng) {
		case 'gm':
			$set = "bd_gm_inp_cd=$cd";
			$column = "bd_gm_inp_cd";
			break;
		case 'ol':
			$set = "bd_ol_inp_cd=$cd";
			$column = "bd_ol_inp_cd";
			break;
		case 'gl';
			$set = "bd_gl_inp_cd=$cd";
			$column = "bd_gl_inp_cd";
			break;
		}

		if ($bd_seq_no) {
			$sql = "UPDATE t_base_data SET $set WHERE bd_seq_no=$bd_seq_no";
			db_exec($sql);
		} else {
			$sql = "INSERT INTO t_base_data (bd_mb_seq_no,bd_month,$column) VALUES (%s,%s,%s)";
			$sql = sprintf($sql,
				sql_number($_SESSION['ss_seq_no']),
				sql_date($this->bd_y . "-" . $this->bd_m . "-01"),
				sql_number($cd));
			db_exec($sql);
		}
	}
}

?>