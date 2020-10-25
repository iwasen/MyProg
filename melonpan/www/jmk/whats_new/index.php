<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:「What's New」入力
'******************************************************/

$title_text = $TT_whats_new;
$title_color = $TC_MASTER;


//'=== Main ===
$sql = "SELECT msg_id, new_msg, target, to_char(modify_dt, 'YYYY/MM/DD hh24:mi:ss') as modify_time, to_char(modify_dt, 'YYYY/MM/DD') as modify_dt FROM T_WHATS_NEW"
		. " ORDER BY modify_time DESC";
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
			<td>■「What's New」表示中メッセージ一覧</td>
			<td align="right">
				<input type="button" value="新規登録" onclick="location.href='whats_new1.php'">
				<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='90%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>ID</nobr></th>
			<th align="center"><nobr>メッセージ</nobr></th>
			<th align="center"><nobr>ターゲット</nobr></th>
			<th align="center"><nobr>登録日</nobr></th>
		</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$msg_id = $fetch->msg_id;
	$new_msg = $fetch->new_msg;
	$modify_dt = $fetch->modify_dt;
	$target = $fetch->target;

?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><a href='whats_new2.php?msg_id=<?= $msg_id ?>'><?= $msg_id ?></a></td>
			<td><?= htmlspecialchars($new_msg) ?></td>
			<td><nobr><?= htmlspecialchars($target) ?></nobr></td>
			<td align="center"><nobr><?= $modify_dt ?></nobr></td>
		</tr>
<?php
$line++;
}
?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
