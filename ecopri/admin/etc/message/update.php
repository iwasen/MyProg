<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:Ecoゾウさんからのお知らせ情報更新処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', 'その他管理', 'Ecoゾウさんからのお知らせ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "INSERT INTO t_top_msg (tm_status,tm_date,tm_title,tm_message,tm_start_date,tm_end_date) VALUES (" .
					"0," .
					"'now'" . "," .
					sql_char($tm_title) . "," .
					sql_char($tm_message) . "," .
					sql_date("$start_year/$start_month/$start_day") . "," .
					sql_date("$end_year/$end_month/$end_day") . ")";
	db_exec($sql);
	$msg = 'Ecoゾウさんからのお知らせを登録しました。';
	$ret = 'location.href=\'list.php\'';
	break;
case 'update':
	$sql = "UPDATE t_top_msg SET" .
				" tm_status=" . sql_char($tm_status) .
				",tm_date='now'" .
				",tm_message=" . sql_char($tm_message) .
				",tm_title=" . sql_char($tm_title) .
				",tm_start_date=" . sql_date("$start_year/$start_month/$start_day") .
				",tm_end_date=" . sql_date("$end_year/$end_month/$end_day") .
				" WHERE tm_seq_no=$tm_no";
	db_exec($sql);
	$msg = 'Ecoゾウさんからのお知らせ情報を更新しました。';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	$sql = "DELETE FROM t_top_msg WHERE tm_seq_no=$tm_no";
	db_exec($sql);
	$msg = 'Ecoゾウさんからのお知らせを削除しました。';
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
