<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙモニター更新処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/my_monitor.php");
include("$inc/get_form.php");

// メイン処理
set_global('marketer', 'マーケター管理｜Ｍｙモニター管理', 'Ｍｙモニター更新', BACK_TOP);

switch ($next_action) {
case 'app':
	$sql = "UPDATE t_my_monitor SET mm_status=2 WHERE mm_room_id=$room_id";
	db_exec($sql);
	$msg = 'Ｍｙモニターを承認しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$mymoni = new my_monitor_class;
	$mymoni->read_db($room_id);
	$mymoni->room_name = trim($room_name);
	$mymoni->start_date_y = get_number($start_date_y);
	$mymoni->start_date_m = get_number($start_date_m);
	$mymoni->start_date_d = get_number($start_date_d);
	$mymoni->period = $period;
	$mymoni->condition = trim($condition);
	$mymoni->status = $status;
	$mymoni->start_mail = $start_mail;
	$mymoni->write_db();
	$msg = 'Ｍｙモニターを更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "UPDATE t_my_monitor SET mm_status=9 WHERE mm_room_id=$room_id";
	db_exec($sql);
	$msg = 'Ｍｙモニターを削除しました。';
	$back = "location.href='list.php'";
	break;
default:
	redirect('list.php');
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
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
