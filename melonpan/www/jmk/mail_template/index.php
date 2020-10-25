<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:送信メールテンプレート
'******************************************************/

$title_text = $TT_mail_template;
$title_color = $TC_MASTER_MAINTE;

$sql = "SELECT * FROM M_MAIL_TEMPLATE order by mt_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
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
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='90%'>
		<tr>
			<td>■送信メールテンプレート一覧</td>
			<td align="right"><input type="button" value=" 戻る " onclick="location.href='../menu.php'"></td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='90%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>種類</nobr></th>
			<th align="center"><nobr>件名</nobr></th>
			<th align="center"><nobr>From</nobr></th>
		</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mt_id = $fetch->mt_id;
	$guide = $fetch->mt_guide;
	$subject = $fetch->mt_subject;
	$from = $fetch->mt_from;
	$cc = $fetch->mt_cc;
	$bcc = $fetch->mt_bcc;
	if ($subject == "") { $subject = "<br>"; }
	if ($from == "") { $from = "<br>"; }
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="left"><a href='mail_template1.php?mt_id=<?= $mt_id ?>' title="送信メールテンプレートを修正します"><?= htmlspecialchars($guide) ?></a></td>
			<td align="left"><?= htmlspecialchars($subject) ?></td>
			<td align="left"><?= $from ?></td>
		</tr>
<?
$line++;
}
?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
