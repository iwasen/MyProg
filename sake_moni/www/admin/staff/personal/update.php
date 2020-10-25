<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:スタッフ個人検索
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('staff', 'スタッフ管理', 'スタッフ個人検索', BACK_TOP);

$sql = "UPDATE t_staff SET" .
			" st_status=" . sql_char($status) .
			",st_update_date=current_timestamp" .
			",st_name1=" . sql_char($name1) .
			",st_name2=" . sql_char($name2) .
			",st_name1_kana=" . sql_char($name1_kana) .
			",st_name2_kana=" . sql_char($name2_kana) .
			",st_birthday=" . sql_date("$birthday_y/$birthday_m/$birthday_d") .
			",st_sex=" . sql_number($sex) .
			",st_mail_addr=" . sql_char($mail_addr) .
			",st_password=" . sql_char($password) .
			",st_kinmu_zip=" . sql_char($kinmu_zip1 . $kinmu_zip2) .
			",st_kinmu_area=" . sql_number($kinmu_area) .
			",st_kinmu_addr1=" . sql_char($kinmu_addr1) .
			",st_kinmu_addr2=" . sql_char($kinmu_addr2) .
			",st_kinmu_addr3=" . sql_char($kinmu_addr3) .
			",st_kinmu_tel=" . sql_char($kinmu_tel) .
			",st_kinmu_fax=" . sql_char($kinmu_fax) .
			",st_kinmu_name=" . sql_char($kinmu_name) .
			",st_kinmu_unit=" . sql_char($kinmu_unit) .
			",st_kinmu_post=" . sql_char($kinmu_post) .
			" WHERE st_staff_id=$staff_id";
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
<p class="msg">スタッフ登録情報を更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
