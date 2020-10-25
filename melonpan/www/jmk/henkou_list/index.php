<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:登録情報変更リスト
'******************************************************/

$title_text = $TT_henkou_list;
$title_color = $TC_MASTER;

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

<center><br>
	<table border=0 cellspacing=0 cellpadding=0 width='40%'>
		<tr>
			<td>■登録情報変更リスト</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=5 width='40%'>
	<form method="post" name="form1">
		<tr>
			<td align="center" class="m6s">１</td>
			<td class="m6s"><nobr>発行者個人情報</nobr></td>
			<td align="center" class="h2"><input type="button" value=" 出力 "onclick="location.href='henkou_list1.php'"></td>
		</tr>
		<tr>
			<td align="center" class="m6s">２</td>
			<td class="m6s"><nobr>メルマガ情報</nobr></td>
			<td align="center" class="h2"><input type="button" value=" 出力 " onclick="location.href='henkou_list2.php'"></td>
		</tr>
		<tr>
			<td align="center" class="m6s">3
			</td>
			<td class="m6s"><nobr>Myめろんぱん個人情報</nobr></td>
			<td align="center" class="h2"><input type="button" value=" 出力 " onclick="location.href='henkou_list3.php'"></td>
		</tr>
	</form>
	</table><br>
	<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
</center>

<? footer_jmk(0); ?>

</body>
</html>
