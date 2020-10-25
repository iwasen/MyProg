<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:マーケター個人検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mr_permission.php");

set_global('marketer', 'マーケター管理', 'マーケター個人検索', BACK_TOP);

$sql = "UPDATE t_marketer SET" .
			" mr_status=" . sql_char($status) .
			",mr_update_date=current_timestamp" .
			",mr_type=" . sql_number($type) .
			",mr_name1=" . sql_char($name1) .
			",mr_name2=" . sql_char($name2) .
			",mr_name1_kana=" . sql_char($name1_kana) .
			",mr_name2_kana=" . sql_char($name2_kana) .
			",mr_birthday=" . sql_date("$birthday_y/$birthday_m/$birthday_d") .
			",mr_sex=" . sql_number($sex) .
			",mr_mail_addr=" . sql_char($mail_addr) .
			",mr_mail_addr_low=" . sql_char(strtolower($mail_addr)) .
			",mr_password=" . sql_char($password) .
			",mr_shokugyou_cd=" . sql_number($shokugyou_cd) .
			",mr_gyoushu_cd=" . sql_number($gyoushu_cd) .
			",mr_shokushu_cd=" . sql_number($shokushu_cd) .
			",mr_kinmu_zip=" . sql_char($kinmu_zip1 . $kinmu_zip2) .
			",mr_kinmu_area=" . sql_number($kinmu_area) .
			",mr_kinmu_addr1=" . sql_char($kinmu_addr1) .
			",mr_kinmu_addr2=" . sql_char($kinmu_addr2) .
			",mr_kinmu_addr3=" . sql_char($kinmu_addr3) .
			",mr_kinmu_tel=" . sql_char($kinmu_tel) .
			",mr_kinmu_fax=" . sql_char($kinmu_fax) .
			",mr_kinmu_name=" . sql_char($kinmu_name) .
			",mr_kinmu_unit=" . sql_char($kinmu_unit) .
			",mr_kinmu_post=" . sql_char($kinmu_post) .
			",mr_agent_id=" . sql_number($agent_id) .
			",mr_permission=" . sql_char($permission != '' ? $permission : get_multi_flag($pmr, PMR_LEN)) .
			",mr_regist_type=" . sql_number($regist_type) .
			" WHERE mr_marketer_id=$marketer_id";
db_exec($sql);
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
<p class="msg">マーケター登録情報を更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
