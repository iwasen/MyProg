<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:エージェント個人検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('agent', 'エージェント管理', 'エージェント個人検索', BACK_TOP);

$sql = "UPDATE t_agent SET" .
			" ag_status=" . sql_number($status) .
			",ag_update_date=current_timestamp" .
			",ag_type=" . sql_number($type) .
			",ag_name1=" . sql_char($name1) .
			",ag_name2=" . sql_char($name2) .
			",ag_name1_kana=" . sql_char($name1_kana) .
			",ag_name2_kana=" . sql_char($name2_kana) .
			",ag_birthday=" . sql_date("$birthday_y/$birthday_m/$birthday_d") .
			",ag_sex=" . sql_number($sex) .
			",ag_mail_addr=" . sql_char($mail_addr) .
			",ag_mail_addr_low=" . sql_char(strtolower($mail_addr)) .
			",ag_password=" . sql_char($password) .
			",ag_kinmu_name=" . sql_char($kinmu_name) .
			",ag_kinmu_unit=" . sql_char($kinmu_unit) .
			",ag_kinmu_post=" . sql_char($kinmu_post) .
			",ag_agent_unit=" . sql_char($agent_unit) .
			",ag_self_introduction=" . sql_char($self_introduction) .
			",ag_disp_flag=" . sql_bool($disp_flag, "'f'") .
			",ag_bcc_addr=" . sql_char($bcc_addr) .
			",ag_signature=" . sql_char($signature) .
			",ag_agent_disc=" . sql_char($agent_disc) .
			" WHERE ag_agent_id=$agent_id";
if (db_exec($sql)) {
	$msg = "エージェント登録情報を更新しました。";
} else {
	$msg = "エージェント登録情報を更新に失敗しました。";
}
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
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
