<?
/******************************************************
' System :Eco-footprint SoL通信
' Content:コマンド受信処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/cnv_unit.php");
include("$inc/cnv_eng_mny.php");

// コマンドコード
define('COM_KENSHIN', 1);
define('COM_PASSCHECK', 2);

// 結果コード
define('RES_NORMAL', 0);
define('RES_PASSCHANGE', 1);
define('RES_ERR_ID', 2);
define('RES_ERR_PASSWORD', 3);
define('RES_ERR_COMMAND', 4);
define('RES_ERR_VERSION', 5);
define('RES_ERR_LASTDATE', 6);
define('RES_ERR_THISDATE', 7);
define('RES_ERR_USEVAL', 8);
define('RES_ERR_OTHER', 99);

// トランザクション開始
db_begin_trans();

// Solコマンド処理
sol_command();

// トランザクションコミット
db_commit_trans();
exit;

// Solコマンド処理
function sol_command() {
	global $version, $id, $password, $command;

	// 結果コード初期化
	$res = RES_NORMAL;

	// バージョンチェック
	if ($version != 1)
		$res = RES_ERR_VERSION;
	else {
		// ID,Passwordチェック
		$mb_seq_no = login_check($id, $password, $res);
		if ($res == RES_NORMAL || $res == RES_PASSCHANGE) {
			switch ($command) {
			case COM_KENSHIN:
				// 検針データコマンド
				kenshin_command($mb_seq_no, $res);
				break;
			case COM_PASSCHECK:
				// パスワードチェックコマンド（無処理）
				break;
			default:
				$res = RES_ERR_COMMAND;
				break;
			}
		}
	}

	if ($res == RES_NORMAL || $res == RES_PASSCHANGE) {
		// Sol設置フラグセット
		set_sol_flag($mb_seq_no);

		// 最終ログインＩＤパスワード更新
		update_last_login($mb_seq_no);
	}

	// 結果返信
	send_result($res);

	// 受信ログ書き込み
	write_log($mb_seq_no, $res);
}

// 検針コマンド処理
function kenshin_command($mb_seq_no, &$res) {
	global $version, $last_date, $this_date, $ele, $sun, $gas, $wtr;

	// 年が9999の場合は何もしない
	if (substr($last_date, 0, 4) == '9999')
		return;

	// 前回検針日時チェック
	if (!check_date($last_date)) {
		$res = RES_ERR_LASTDATE;
		return;
	}

	// 今回検針日時チェック
	if (!check_date($this_date)) {
		$res = RES_ERR_THISDATE;
		return;
	}

	// 電気使用量チェック
	if (!check_use($ele)) {
		$res = RES_ERR_USEVAL;
		return;
	}

	// 太陽発電量チェック
	if (!check_use($sun)) {
		$res = RES_ERR_USEVAL;
		return;
	}

	// ガス使用量チェック
	if (!check_use($gas)) {
		$res = RES_ERR_USEVAL;
		return;
	}

	// 水道使用量チェック
	if (!check_use($wtr)) {
		$res = RES_ERR_USEVAL;
		return;
	}

	// テーブルに保存
	write_use_data($mb_seq_no, $this_date, $ele, $sun, $gas, $wtr);
}

// 検針日時チェック
function check_date(&$date) {
	sscanf($date, '%d-%d-%d_%d-%d', $year, $month, $day, $hour, $minute);

	if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day) || !is_numeric($hour) || !is_numeric($minute))
		return false;

	if (!checkdate($month, $day, $year))
		return false;

	if ($hour < 0 || $hour > 23)
		return false;

	if ($minute < 0 || $minute > 59)
		return false;

	$date = sprintf('%04d-%02d-01', $year, $month);

	return true;
}

// 使用量チェック
function check_use($val) {
	return is_numeric($val);
}

// データを保存
function write_use_data($mb_seq_no, $date, $ele, $sun, $gas, $wtr) {
	$sql = "SELECT bd_seq_no,bd_el_use,bd_gs_use,bd_wt_use,bd_sn_use,bd_commit_flag FROM t_base_data WHERE bd_mb_seq_no=$mb_seq_no AND bd_month='$date'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// 確定済みなら何もしない
		if ($fetch->bd_commit_flag == 1)
			return;

		// 電気使用量
		if (!$fetch->bd_el_use) {
			$set[] = "bd_el_use=$ele";
			$set[] = "bd_el_use_flag=5";
			$set[] = "bd_el_sum=" . cnv_ele_mny($ele, -1, $mb_seq_no);
			$set[] = "bd_el_sum_flag=2";
		}

		// ガス使用量
		if (!$fetch->bd_gs_use) {
			$set[] = "bd_gs_use=$gas";
			$set[] = "bd_gs_use_flag=5";
			$set[] = "bd_gs_sum=" . cnv_gas_mny($gas, $mb_seq_no);
			$set[] = "bd_gs_sum_flag=2";
		}

		// 水道使用量
		if (!$fetch->bd_wt_use) {
			$set[] = "bd_wt_use=$wtr";
			$set[] = "bd_wt_use_flag=5";
			$set[] = "bd_wt_sum=" . cnv_wtr_mny($wtr, $mb_seq_no);
			$set[] = "bd_wt_sum_flag=2";
		}

		// 太陽光発電量
		if (!$fetch->bd_sn_use) {
			$set[] = "bd_sn_use=$sun";
			$set[] = "bd_sn_use_flag=5";
		}

		if (!$set)
			return;

		$sql = "UPDATE t_base_data SET " . join(',', $set) . " WHERE bd_seq_no=$fetch->bd_seq_no";
	} else {
		// レコード追加
		$sql = sprintf("INSERT INTO t_base_data (bd_mb_seq_no,bd_month,bd_el_use,bd_el_use_flag,bd_el_sum,bd_el_sum_flag,bd_gs_use,bd_gs_use_flag,bd_gs_sum,bd_gs_sum_flag,bd_wt_use,bd_wt_use_flag,bd_wt_sum,bd_wt_sum_flag,bd_sn_use,bd_sn_use_flag) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			sql_number($mb_seq_no),
			sql_date($date),
			sql_number($ele),
			sql_number(5),
			sql_number(cnv_ele_mny($ele, -1, $mb_seq_no)),
			sql_number(2),
			sql_number($gas),
			sql_number(5),
			sql_number(cnv_gas_mny($gas, $mb_seq_no)),
			sql_number(2),
			sql_number($wtr),
			sql_number(5),
			sql_number(cnv_wtr_mny($wtr, $mb_seq_no)),
			sql_number(2),
			sql_number($sun),
			sql_number(5));
	}

	db_exec($sql);
}

// ログインチェック
function login_check($id, $password, &$res) {
	// IDチェック
	$sql = "SELECT mb_seq_no,mb_password FROM t_member WHERE mb_id=" . sql_char($id);
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mb_seq_no = $fetch->mb_seq_no;

		// パスワードチェック
		if ($password != $fetch->mb_password)
			$res = RES_ERR_PASSWORD;
	} else
		$res = RES_ERR_ID;

	// 前回ログインIDチェック
	if ($res != RES_NORMAL) {
		$sql = "SELECT ls_mb_seq_no FROM t_last_sol WHERE ls_id=" . sql_char($id) . " AND ls_password=" . sql_char($password);
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mb_seq_no = $fetch->ls_mb_seq_no;

			// 変更後のID,PasswordをSolに返す
			$sql = "SELECT mb_id,mb_password FROM t_member WHERE mb_seq_no=$mb_seq_no";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				add_result_data("id=$fetch->mb_id");
				add_result_data("password=$fetch->mb_password");
			}

			$res = RES_PASSCHANGE;
		}
	}

	return $mb_seq_no;
}

// Sol設置フラグセット
function set_sol_flag($mb_seq_no) {
	$sql = "UPDATE t_member SET mb_sol_flag=1 WHERE mb_seq_no=$mb_seq_no AND mb_sol_flag IS NULL";
	db_exec($sql);
}

// 最終ログインＩＤパスワード更新
function update_last_login($mb_seq_no) {
	$sql = "SELECT ls_mb_seq_no FROM t_last_sol WHERE ls_mb_seq_no=$mb_seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		// 更新
		$sql = "UPDATE t_last_sol SET ls_id=mb_id,ls_password=mb_password,ls_date=current_timestamp FROM t_member WHERE ls_mb_seq_no=$mb_seq_no AND ls_mb_seq_no=mb_seq_no AND (ls_id<>mb_id OR ls_password<>mb_password)";
	} else {
		// 追加
		$sql = "INSERT INTO t_last_sol (ls_mb_seq_no,ls_id,ls_password) SELECT mb_seq_no,mb_id,mb_password FROM t_member WHERE mb_seq_no=$mb_seq_no";
	}

	db_exec($sql);
}

// 追加返信データセット
function add_result_data($data) {
	global $g_add_result;

	$g_add_result .= "&$data";
}

// 結果返信
function send_result($res) {
	global $g_add_result;

	echo "result=$res";

	if ($g_add_result)
		echo $g_add_result;
}

// 受信ログ書き込み
function write_log($mb_seq_no, $res) {
	global $HTTP_RAW_POST_DATA, $REMOTE_ADDR;

	$sql = sprintf("INSERT INTO l_sol_receive (sr_receive_data,sr_mb_seq_no,sr_ip_address,sr_result) VALUES (%s,%s,%s,%s)",
			sql_char($HTTP_RAW_POST_DATA),
			sql_number($mb_seq_no),
			sql_char($REMOTE_ADDR),
			sql_number($res));
	db_exec($sql);
}
?>