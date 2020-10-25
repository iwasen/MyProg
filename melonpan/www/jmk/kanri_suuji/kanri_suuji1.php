<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:管理数字表示画面
'******************************************************/

$title_text = $TT_kanri_suuji;
$title_color = $TC_MASTER;

//-前日の日付を取得-
$yesterday = mktime (0,0,0,date(m),date(d)-1,date(Y));
$y = strftime ("%Y", $yesterday);
$m = strftime ("%m", $yesterday);
$d = strftime ("%d", $yesterday);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" action="kanri_suuji2.php">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■管理数字表示<font size="2">　（前日までの管理数字を表示します。）</font></td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m4" width="28%">出力指定日</td>
						<td class="n4" width="60%">
							<select name="year"><? select_year(2000, '', $y) ?></select>年
							<select name="month"><? select_month('', $m); ?></select>月
							<select name="day"><? select_day('', $d); ?></select>日
					</tr>
				</table>
			</td>
		</tr>
	</table> <br>
<input type="submit" value=" 出力 ">
<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
