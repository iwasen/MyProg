<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:モニター個人検索
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

function change_monitor_count($monitor_id, $new_status) {
	$sql = "SELECT mn_status FROM t_monitor WHERE mn_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$old_status = $fetch[0];
	} else {
		$old_status = 9;
	}
}

// 生活情報設定
function set_lifeinfo($monitor_id, $table, $column, $id_arry) {
	$sql = "DELETE FROM $table WHERE $column=$monitor_id";
	db_exec($sql);

	if ($id_arry) {
		foreach ($id_arry as $id) {
			$sql = "INSERT INTO $table values ($monitor_id, $id)";
			db_exec($sql);
		}
	}
}

set_global('monitor', 'モニター管理', 'モニター個人検索', BACK_TOP);

db_begin_trans();

change_monitor_count($monitor_id, $status);

$sql = "UPDATE t_monitor SET" .
			" mn_status=" . sql_number($status) .
			",mn_update_date=current_timestamp" .
			",mn_name1=" . sql_char($name1) .
			",mn_name2=" . sql_char($name2) .
			",mn_name1_kana=" . sql_char($name1_kana) .
			",mn_name2_kana=" . sql_char($name2_kana) .
			",mn_birthday=" . sql_date("$birthday_y/$birthday_m/$birthday_d") .
			",mn_sex=" . sql_number($sex) .
			",mn_mikikon=" . sql_number($mikikon) .
			",mn_mail_addr=" . sql_char($mail_addr) .
			",mn_mail_addr_low=" . sql_char(strtolower($mail_addr)) .
			",mn_password=" . sql_char($password) .
			",mn_jitaku_zip=" . sql_char($jitaku_zip1 . $jitaku_zip2) .
			",mn_jitaku_area=" . sql_number($jitaku_area) .
			",mn_jitaku_addr2=" . sql_char($jitaku_addr2) .
			",mn_shokugyou_cd=" . sql_number($shokugyou_cd) .
			",mn_payment_type=" . sql_number($payment_type) . 
			",mn_account_num=" . sql_char($account_num) . 
			" WHERE mn_monitor_id=$monitor_id";
db_exec($sql);

set_lifeinfo($monitor_id, 't_chain', 'dc_monitor_id', $chain_cd);

if ($point_change) {
	$sql = sprintf("UPDATE t_monitor_point SET mp_curr_point=%s,mp_total_point=%s WHERE mp_monitor_id=$monitor_id",
			sql_number($curr_point),
			sql_number($total_point));
	db_exec($sql);
}

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg">モニター登録情報を更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
