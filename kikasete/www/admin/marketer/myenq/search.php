<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート人数検索処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

$search = new search_class;
$search->read_db($search_id);

$sql = 'SELECT COUNT(*) FROM t_monitor WHERE ' . $search->make_sql();
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$find = $fetch[0];
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>検索結果</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<div align="center">
<table width=200 border=1>
	<tr>
		<td class="m2" align="center">対象人数検索結果</td>
	</tr>
	<tr>
		<td class="n1" align="center"><?=number_format($find)?> 人</td>
	</tr>
</table>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</div>

</body>
</html>
