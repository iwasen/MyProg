<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ブラックリスト管理
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

set_global('monitor', 'モニター管理', 'ブラックリスト管理', BACK_TOP);

switch ($next_action) {
case 'update':
	$sql = "UPDATE t_monitor SET" .
				" mn_update_date=current_timestamp" .
				",mn_blacklist_flg=" . sql_number($blacklist_flg) .
				" WHERE mn_monitor_id=$monitor_id";
	db_exec($sql);
	$msg = "ブラックリストフラグを変更しました。";
	break;
case 'delete':
	resign_monitor($monitor_id);
	$msg = "モニターを退会にしました。";
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
