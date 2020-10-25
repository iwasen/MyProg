<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:事務局ページ共通人数検索
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");

// 検索条件取得
$search_id = $_GET['search_id'];
$search = new search_class;
$search->get_form();
$search->make_sql($from, $where);
$condition = $search->get_condition();

// 全有効ユーザ数取得
$sql = "SELECT COUNT(*) FROM t_user_status WHERE ust_status=1 OR ust_status=2";
$result = db_exec($sql);
$all = pg_fetch_result($result, 0, 0);

// 検索人数取得
$sql = "SELECT COUNT(*) FROM $from WHERE $where";
$result = db_exec($sql);
$find = pg_fetch_result($result, 0, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>人数検索</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header('人数検索') ?>

<div align="center">
<table border=0 cellspacing=2 cellpadding=2 width="100%">
	<tr>
		<td class="lt" colspan=2 align="left">■検索結果</td>
	</tr>
	<tr>
		<td class="m1" width="25%">検索ユーザ数</td>
		<td class="n1"><?=number_format($find)?> / <?=number_format($all)?> (<?=number_format($find / $all * 100, 1)?>%)</td>
	</tr>
	<tr>
		<td class="m1">検索条件</td>
		<td class="n1"><font size="-1"><?=nl2br($condition)?></font></td>
	</tr>
</table><br>
<div align="center">
	<input type="submit" value="　閉じる　" onclick="window.close()">
</div>

<? center_footer() ?>

</body>
</html>
