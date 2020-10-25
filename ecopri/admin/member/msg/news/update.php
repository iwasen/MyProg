<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:News!!更新
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('member', '会員トップページ設定', 'News!!', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "INSERT INTO t_news (ns_title,ns_text,ns_start_date,ns_end_date) VALUES (" .
					sql_char($title) . "," .
					sql_char($msg) . "," .
					sql_date("$start_y/$start_m/$start_d") . "," .
					sql_date("$end_y/$end_m/$end_d") . ")";
	db_exec($sql);
	$msg = 'News!!を登録しました。';
	$ret = 'location.href=\'list.php\'';
	break;
case 'update':
	$sql = "UPDATE t_news SET" .
				" ns_title=" . sql_char($title) .
				",ns_text=" . sql_char($msg) .
				",ns_start_date=" . sql_date("$start_y/$start_m/$start_d") .
				",ns_end_date=" . sql_date("$end_y/$end_m/$end_d") .
				" WHERE ns_seq_no=$ns_no";
	db_exec($sql);
	$msg = 'News!!情報を更新しました。';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	$sql = "DELETE FROM t_news WHERE ns_seq_no=$ns_no";
	db_exec($sql);
	$msg = 'News!!を削除しました。';
	$ret = 'location.href=\'list.php\'';
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
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
