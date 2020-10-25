<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニター個人検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/point.php");
include("$inc/resign.php");

// モニター数更新
function change_monitor_count($monitor_id, $old_status, $new_status) {
	if ($old_status == 0 && $new_status != 0) {
		$sql = "UPDATE t_sum_data SET su_sum_data = su_sum_data - 1 WHERE su_sum_id=1";
		db_exec($sql);
	} elseif ($old_status != 0 && $new_status == 0) {
		$sql = "UPDATE t_sum_data SET su_sum_data = su_sum_data + 1 WHERE su_sum_id=1";
		db_exec($sql);
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

$sql = "SELECT mn_status FROM t_monitor WHERE mn_monitor_id=$monitor_id";
$old_status = db_fetch1($sql);

change_monitor_count($monitor_id, $old_status, $status);

$sql = "UPDATE t_monitor SET" .
			" mn_status=" . sql_number($status) .
			",mn_update_date=current_timestamp" .
			",mn_type=" . sql_number($type) .
			",mn_name1=" . sql_char($name1) .
			",mn_name2=" . sql_char($name2) .
			",mn_name1_kana=" . sql_char($name1_kana) .
			",mn_name2_kana=" . sql_char($name2_kana) .
			",mn_birthday=" . sql_date2($birthday_y, $birthday_m, $birthday_d) .
			",mn_sex=" . sql_number($sex) .
			",mn_mikikon=" . sql_number($mikikon) .
			",mn_mail_addr=" . sql_char($mail_addr) .
			",mn_mail_addr_low=" . sql_char(strtolower($mail_addr)) .
			",mn_password=" . sql_char($password) .
			",mn_jitaku_zip=" . sql_char($jitaku_zip1 . $jitaku_zip2) .
			",mn_jitaku_area=" . sql_number($jitaku_area) .
			",mn_jitaku_addr1=" . sql_char($jitaku_addr1) .
			",mn_jitaku_addr2=" . sql_char($jitaku_addr2) .
			",mn_jitaku_addr3=" . sql_char($jitaku_addr3) .
			",mn_jitaku_tel=" . sql_char($jitaku_tel) .
			",mn_jitaku_fax=" . sql_char($jitaku_fax) .
			",mn_urgent_addr=" . sql_char($urgent_addr) .
			",mn_shokugyou_cd=" . sql_number($shokugyou_cd) .
			",mn_gyoushu_cd=" . sql_number($gyoushu_cd) .
			",mn_shokushu_cd=" . sql_number($shokushu_cd) .
			",mn_kinmu_zip=" . sql_char($kinmu_zip1 . $kinmu_zip2) .
			",mn_kinmu_area=" . sql_number($kinmu_area) .
/*
			",mn_kinmu_addr1=" . sql_char($kinmu_addr1) .
			",mn_kinmu_addr2=" . sql_char($kinmu_addr2) .
			",mn_kinmu_addr3=" . sql_char($kinmu_addr3) .
			",mn_kinmu_tel=" . sql_char($kinmu_tel) .
			",mn_kinmu_fax=" . sql_char($kinmu_fax) .
*/
			",mn_kinmu_name=" . sql_char($kinmu_name) .
			",mn_kinmu_unit=" . sql_char($kinmu_unit) .
			",mn_kinmu_post=" . sql_char($kinmu_post) .
//			",mn_self_introduction=" . sql_char($self_introduction) .
			",mn_blacklist_flg=" . sql_number($blacklist_flg) .
			",mn_have_child=" . sql_number($have_child) .
			",mn_housing_form=" . sql_number($housing_form) .
			",mn_have_car=" . sql_number($have_car) .
			",mn_html_mail=" . sql_bool($html_mail) .
			",mn_spouse_flg=" . sql_number($spouse_flg) .
			",mn_spouse_other=" . sql_char($spouse_flg == '3' ? $spouse_other : '') .
			",mn_spouse_birthday=" . sql_date2($spouse_flg == '1' ? $spouse_birthday_y : '', $spouse_birthday_m, $spouse_birthday_d) .
			",mn_spouse_shokugyo_cd=" . sql_number($spouse_flg == '1' ? $spouse_shokugyo_cd : '') .
			",mn_senior_flg=" . sql_number($senior_flg) .
			",mn_haimail_flag=" . sql_bool($haimail_flag) .
			" WHERE mn_monitor_id=$monitor_id";
db_exec($sql);

// 退会日時設定
if ($old_status != 9 && $status == 9) {
	// 退会処理
	resign_monitor($monitor_id);
} elseif ($old_status == 9 && $status != 9) {
	$sql = "UPDATE t_monitor SET mn_taikai_date = NULL WHERE mn_monitor_id=$monitor_id";
	db_exec($sql);
}

$sql = "DELETE FROM t_monitor_child WHERE mc_monitor_id=$monitor_id";
db_exec($sql);
if ($have_child == '1') {
	$child_no = 1;
	for ($i = 0; $i < $child_num; $i++) {
		$sql = sprintf("INSERT INTO t_monitor_child (mc_monitor_id,mc_child_no,mc_sex,mc_birthday) VALUES (%s,%s,%s,%s)",
				sql_number($monitor_id),
				sql_number($child_no++),
				sql_number($child_sex[$i]),
				sql_date2($child_birthday_y[$i], $child_birthday_m[$i], $child_birthday_d[$i]));
		db_exec($sql);
	}
}

set_lifeinfo($monitor_id, 't_conveni', 'dc_monitor_id', $conveni_cd);
set_lifeinfo($monitor_id, 't_super', 'ds_monitor_id', $super_cd);
set_lifeinfo($monitor_id, 't_genre', 'dk_monitor_id', $genre_cd);
set_lifeinfo($monitor_id, 't_monitor_profile', 'mnp_monitor_id', $profile_cd);

if ($point_change) {
	$sql = sprintf("UPDATE t_monitor_point SET mp_curr_point=%s,mp_total_point=%s WHERE mp_monitor_id=$monitor_id",
			sql_number($curr_point),
			sql_number($total_point));
	db_exec($sql);
}

$sql = sprintf("UPDATE t_monitor_point SET mp_stop_period_flag=%s WHERE mp_monitor_id=%s AND mp_stop_period_flag<>%s",
		sql_bool($stop_period_flag),
		sql_number($monitor_id),
		sql_bool($stop_period_flag));
db_exec($sql);

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
