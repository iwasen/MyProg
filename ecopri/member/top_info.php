<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:トップページ（ログイン）
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");

$sql = "select tm_message from t_top_msg where to_char(now(), 'YYYYMMDD') between to_char(tm_start_date, 'YYYYMMDD') and to_char(tm_end_date, 'YYYYMMDD')"
		. " and tm_status=0 order by tm_seq_no desc";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$tm_message = $fetch->tm_message;
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>
<body topmargin=0 leftmargin=0 rightmargin=0 bottommargin=0>
<table cellspacing=0 cellpadding=1>
<tr>
<td>
<span style="font-size:9pt;"><?=str_replace("\n", "<br>", $tm_message)?></span>
</td>
</tr>
</table>
</body>
</html>