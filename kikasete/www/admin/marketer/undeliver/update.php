<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:不達メール管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('marketer', 'マーケター管理', '不達メール管理', BACK_TOP);

switch ($next_action) {
case 'update':
	db_begin_trans();

	$sql = "UPDATE t_marketer SET" .
				" mr_status=0" .
				",mr_mail_addr=" . sql_char($mail_addr) .
				",mr_mail_addr_low=" . sql_char(strtolower($mail_addr)) .
				",mr_update_date=current_timestamp" .
				" WHERE mr_marketer_id=$marketer_id";
	db_exec($sql);

	$sql = "DELETE FROM t_err_mail WHERE er_member_kind=2 AND er_member_id=$marketer_id";
	db_exec($sql);

	db_commit_trans();

	$msg = "不達メールアドレスを有効にしました。";
	break;
case 'delete':
	db_begin_trans();

	$sql = "UPDATE t_marketer SET" .
				" mr_status=9" .
				",mr_taikai_date=current_timestamp" .
				" WHERE mr_marketer_id=$marketer_id";
	db_exec($sql);

	$sql = "DELETE FROM t_err_mail WHERE er_member_kind=2 AND er_member_id=$marketer_id";
	db_exec($sql);

	db_commit_trans();

	$msg = "不達メールアドレスを退会にしました。";
	break;
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
