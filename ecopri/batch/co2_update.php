<?
/******************************************************
' System :Eco-footprint 共通
' Content:最新CO2排出量登録処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch_log.php");
include("$inc/rank.php");
include("$inc/cnv_unit.php");
include("$inc/cnv_eng_co2.php");

// 指定年月のCO2、使用量、金額取得
function get_month_data($seq_no, $date, &$co2_ary, &$use_ary, &$sum_ary) {
	// エネルギー消費データ取得
	$sql = "SELECT bd_el_use,bd_el_sum,bd_gs_use,bd_gs_sum,bd_ol_use,bd_ol_sum,bd_wt_use,bd_wt_sum,bd_gl_use,bd_gl_sum,bd_gm_use"
			. " FROM t_base_data"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month=" . sql_date($date);
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// CO2排出量計算
		$co2_ary['ele'] = cnv_ele_co2($fetch->bd_el_use);
		$co2_ary['gas'] = cnv_gas_co2($fetch->bd_gs_use);
		$co2_ary['wtr'] = cnv_wtr_co2($fetch->bd_wt_use);
		$co2_ary['oil'] = cnv_oil_co2($fetch->bd_ol_use);
		$co2_ary['gso'] = cnv_gso_co2($fetch->bd_gl_use);
		$co2_ary['dst'] = cnv_dst_co2($fetch->bd_gm_use);

		// 使用量
		$use_ary['ele'] = $fetch->bd_el_use == '' ? 0 : $fetch->bd_el_use;
		$use_ary['gas'] = $fetch->bd_gs_use == '' ? 0 : $fetch->bd_gs_use;
		$use_ary['wtr'] = $fetch->bd_wt_use == '' ? 0 : $fetch->bd_wt_use;
		$use_ary['oil'] = $fetch->bd_ol_use == '' ? 0 : $fetch->bd_ol_use;
		$use_ary['gso'] = $fetch->bd_gl_use == '' ? 0 : $fetch->bd_gl_use;
		$use_ary['dst'] = $fetch->bd_gm_use == '' ? 0 : $fetch->bd_gm_use;

		// 金額
		$sum_ary['ele'] = $fetch->bd_el_sum == '' ? 0 : $fetch->bd_el_sum;
		$sum_ary['gas'] = $fetch->bd_gs_sum == '' ? 0 : $fetch->bd_gs_sum;
		$sum_ary['wtr'] = $fetch->bd_wt_sum == '' ? 0 : $fetch->bd_wt_sum;
		$sum_ary['oil'] = $fetch->bd_ol_sum == '' ? 0 : $fetch->bd_ol_sum;
		$sum_ary['gso'] = $fetch->bd_gl_sum == '' ? 0 : $fetch->bd_gl_sum;
	}
}

// 合計値、合計削減量算出
function get_total_cut($kind, $data_ary, $data_ary_ly, &$eng_4total, &$eng_6total, &$eng_4cut, &$eng_6cut) {
	$eng_4total = $data_ary['ele'] + $data_ary['gas'] + $data_ary['wtr'] + $data_ary['oil'];
	$eng_4cut = ($data_ary['ele'] - $data_ary_ly['ele']) + ($data_ary['gas'] - $data_ary_ly['gas']) + ($data_ary['wtr'] - $data_ary_ly['wtr']) + ($data_ary['oil'] - $data_ary_ly['oil']);

	if ($kind == 'sum') {
		$eng_6total = $eng_4total + $data_ary['gso'];
		$eng_6cut = $eng_4cut + $data_ary['gso'] - $data_ary_ly['gso']; 
	} else {
		$eng_6total = $eng_4total + $data_ary['gso'] + $data_ary['dst'];
		$eng_6cut = $eng_4cut + ($data_ary['gso'] - $data_ary_ly['gso']) + ($data_ary['dst'] - $data_ary_ly['dst']); 
	}
}


define('FILE_NAME', __FILE__);

// 最大実行時間（３分）
set_time_limit(180);

// トランザクション開始
db_begin_trans();

// 前回処理日時取得
$sql = "SELECT MAX(lb_date) AS lb_date FROM l_batch WHERE lb_file_name='co2_update.php'";
$lt_date = db_fetch1($sql);

// 対象データ抽出
$sql = "SELECT lb_seq_no FROM l_batch WHERE lb_file_name='co2_update.php'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$sub = "SELECT MAX(lb_date) AS lb_date FROM l_batch WHERE lb_file_name='co2_update.php'";
	$sql = "SELECT bd_seq_no,bd_mb_seq_no,bd_month,bd_auto_commit FROM t_base_data"
		. " WHERE bd_regist_date > ($sub) ORDER BY bd_seq_no";
} else
	$sql = "SELECT bd_seq_no,bd_mb_seq_no,bd_month,bd_auto_commit FROM t_base_data ORDER BY bd_seq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
//print $nrow."<br>";

$count = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$bd_seq_no = $fetch->bd_seq_no;
	$seq_no = $fetch->bd_mb_seq_no;
	$date = $fetch->bd_month;
	$auto_commit = $fetch->bd_auto_commit;
	$_SESSION['ss_seq_no'] = $seq_no;

	if ($auto_commit == '') {
		// 翌年の情報取得
		$date_ny = add_date($date, 1, 0, 0);
		$sql = "SELECT bd_seq_no,bd_mb_seq_no,bd_month,bd_auto_commit FROM t_base_data"
			. " WHERE bd_auto_commit IS NOT NULL AND bd_mb_seq_no=$seq_no AND bd_month=" . sql_date($date_ny);
		$result2 = db_exec($sql);
		if (pg_numrows($result2)) {
		
			$fetch = pg_fetch_object($result2, 0);
			$bd_seq_no = $fetch->bd_seq_no;
			$date = $fetch->bd_month;
			$auto_commit = $fetch->bd_auto_commit;
		} else {
			continue;
		}
	}

	// 配列の初期化
	$co2_ary = array();
	$use_ary = array();
	$sum_ary = array();
	$co2_ary_ly = array();
	$use_ary_ly = array();
	$sum_ary_ly = array();
	$no_input = array();

	// 指定月のCO2排出量計算
	get_month_data($seq_no, $date, $co2_ary, $use_ary, $sum_ary);

	// 前年のCO2排出量計算
	$date_ly = add_date($date, -1, 0, 0);
	get_month_data($seq_no, $date_ly, $co2_ary_ly, $use_ary_ly, $sum_ary_ly);

	// 未入力項目は計算に入れないようにする
	$sql = "SELECT bd_gm_inp_cd,bd_gl_inp_cd,bd_ol_inp_cd"
			. " FROM t_base_data"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month=" . sql_date($date);
	$result2 = db_exec($sql);
	if (pg_numrows($result2)) {
		$fetch2 = pg_fetch_object($result2, 0);

		if ($co2_ary['ele'] == 0)
			$no_input['ele'] = true;

		if ($co2_ary['gas'] == 0)
			$no_input['gas'] = true;

		if ($co2_ary['wtr'] == 0)
			$no_input['wtr'] = true;

		if ($fetch2->bd_ol_inp_cd != 1 && $co2_ary['oil'] == 0)
			$no_input['oil'] = true;

		if ($fetch2->bd_gl_inp_cd != 1 && $co2_ary['gso'] == 0)
			$no_input['gso'] = true;

		if ($fetch2->bd_gm_inp_cd != 1 && $co2_ary['dst'] == 0)
			$no_input['dst'] = true;
	}

	// 無効データをゼロに
	foreach ($use_ary as $key => $co2) {
		if ($no_input[$key]) {
			$co2_ary[$key] = 0;
			$co2_ary_ly[$key] = 0;

			$use_ary[$key] = 0;
			$use_ary_ly[$key] = 0;

			$sum_ary[$key] = 0;
			$sum_ary_ly[$key] = 0;
		}
	}

	// 既にデータがあったら削除
	$sql = "SELECT bd_seq_no FROM t_base_data WHERE bd_seq_no=$bd_seq_no";
	$result3 = db_exec($sql);
	if (pg_numrows($result3)) {
		$sql = "DELETE FROM t_batch_data WHERE ba_bd_seq_no=$bd_seq_no";
		db_exec($sql);
	}

	// 合計値、合計削減量算出
	get_total_cut('co2', $co2_ary, $co2_ary_ly, $co2_4total, $co2_6total, $co2_4cut, $co2_6cut);
	get_total_cut('use', $use_ary, $use_ary_ly, $use_4total, $use_6total, $use_4cut, $use_6cut);
	get_total_cut('sum', $sum_ary, $sum_ary_ly, $sum_4total, $sum_6total, $sum_4cut, $sum_6cut);

	if ($auto_commit != '') {
		// データ書込み
		$sql = sprintf("INSERT INTO t_batch_data (ba_bd_seq_no,ba_mb_seq_no,ba_ym,
			ba_co2_ele,ba_co2_gas,ba_co2_oil,ba_co2_wtr,ba_co2_gso,ba_co2_dst,
			ba_co2_cut_ele,ba_co2_cut_gas,ba_co2_cut_oil,ba_co2_cut_wtr,ba_co2_cut_gso,ba_co2_cut_dst,
			ba_co2_4total,ba_co2_6total,ba_co2_4cut,ba_co2_6cut,
			ba_use_ele,ba_use_gas,ba_use_oil,ba_use_wtr,ba_use_gso,ba_use_dst,
			ba_use_cut_ele,ba_use_cut_gas,ba_use_cut_oil,ba_use_cut_wtr,ba_use_cut_gso,ba_use_cut_dst,
			ba_sum_ele,ba_sum_gas,ba_sum_oil,ba_sum_wtr,ba_sum_gso,
			ba_sum_cut_ele,ba_sum_cut_gas,ba_sum_cut_oil,ba_sum_cut_wtr,ba_sum_cut_gso,
			ba_sum_4total,ba_sum_6total,ba_sum_4cut,ba_sum_6cut,ba_regist_date) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,'now')",
		sql_number($bd_seq_no),
		sql_number($seq_no),
		sql_date($date),
		sql_number($co2_ary['ele']),
		sql_number($co2_ary['gas']),
		sql_number($co2_ary['oil']),
		sql_number($co2_ary['wtr']),
		sql_number($co2_ary['gso']),
		sql_number($co2_ary['dst']),
		sql_number($co2_ary['ele'] - $co2_ary_ly['ele']),
		sql_number($co2_ary['gas'] - $co2_ary_ly['gas']),
		sql_number($co2_ary['oil'] - $co2_ary_ly['oil']),
		sql_number($co2_ary['wtr'] - $co2_ary_ly['wtr']),
		sql_number($co2_ary['gso'] - $co2_ary_ly['gso']),
		sql_number($co2_ary['dst'] - $co2_ary_ly['dst']),
		sql_number($co2_4total),
		sql_number($co2_6total),
		sql_number($co2_4cut),
		sql_number($co2_6cut),
		sql_number($use_ary['ele']),
		sql_number($use_ary['gas']),
		sql_number($use_ary['oil']),
		sql_number($use_ary['wtr']),
		sql_number($use_ary['gso']),
		sql_number($use_ary['dst']),
		sql_number($use_ary['ele'] - $use_ary_ly['ele']),
		sql_number($use_ary['gas'] - $use_ary_ly['gas']),
		sql_number($use_ary['oil'] - $use_ary_ly['oil']),
		sql_number($use_ary['wtr'] - $use_ary_ly['wtr']),
		sql_number($use_ary['gso'] - $use_ary_ly['gso']),
		sql_number($use_ary['dst'] - $use_ary_ly['dst']),
		sql_number($sum_ary['ele']),
		sql_number($sum_ary['gas']),
		sql_number($sum_ary['oil']),
		sql_number($sum_ary['wtr']),
		sql_number($sum_ary['gso']),
		sql_number($sum_ary['ele'] - $sum_ary_ly['ele']),
		sql_number($sum_ary['gas'] - $sum_ary_ly['gas']),
		sql_number($sum_ary['oil'] - $sum_ary_ly['oil']),
		sql_number($sum_ary['wtr'] - $sum_ary_ly['wtr']),
		sql_number($sum_ary['gso'] - $sum_ary_ly['gso']),
		sql_number($sum_4total),
		sql_number($sum_6total),
		sql_number($sum_4cut),
		sql_number($sum_6cut));
		db_exec($sql);

		$count++;
	}
}

// エラーチェック
$err_msg = db_errormessage();
if ($err_msg) {
	db_rollback();

	// バッチログ書き込み（エラー）
	$desc = "CO2算出処理でエラーが発生しました。($err_msg)";
	batch_log(9, FILE_NAME, $desc);
	exit;
} else {
	// バッチログ書き込み（正常）
	if ($count > 0) {
		$desc = "CO2算出処理を{$count}件実行しました。";
		batch_log(0, FILE_NAME, $desc);
	}
}

db_commit_trans();

?>