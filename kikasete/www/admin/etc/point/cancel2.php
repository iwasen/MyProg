<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ポイント精算却下取消処理
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

// メイン処理
set_global('etc', 'その他管理', 'ポイント精算', BACK_TOP);

db_begin_trans();

$sql = "SELECT si_member_id,si_point,si_item FROM t_seisan WHERE si_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$sql = "UPDATE t_seisan SET si_status=0 WHERE si_seq_no=$seq_no";
	db_exec($sql);

	$point = $fetch->si_point + get_charge_point($fetch->si_item);
	monitor_point($fetch->si_member_id, PT_SEISAN, -$point);
}

db_commit_trans();

$msg = 'ポイント却下を取り消しました。';
$back = "location.href='list.php'";
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
