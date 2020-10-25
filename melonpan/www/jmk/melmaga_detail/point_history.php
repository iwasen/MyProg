<?
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:ポイント履歴表示
'******************************************************/

$title_text = "発行者情報表示";
$title_color = $TC_MASTER;

if ($kind == "2") {
	$sql = "SELECT owner_nm_kanj FROM M_PUBLISHER WHERE publisher_id=$id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$name = $fetch->owner_nm_kanj;
	}
} elseif ($kind == "1") {
	$sql = "SELECT mail_addr FROM M_MELONPA WHERE reader_id=$id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$name = $fetch->mail_addr;
	}
} elseif ($kind == "3") {
	$sql = "SELECT melonpai_name FROM M_MELONPAI WHERE melonpai_id=$id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$name = $fetch->melonpai_name;
	}
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title>ポイント履歴表示</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=650,height=700");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1" action="kanri_suuji2.php">
	<center>
		<table width='650'>
			<tr>
				<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
				</td>
			</tr>
			<tr>
				<td>■<?= $name ?>さんのポイント履歴</td>
			</tr>
		</table>
		<table width='650' border="1" CELLSPACING="0" CELLPADDING="0">
			<tr>
				<td class="n6" align="center">取得日</td>
				<td class="n6" align="center">ポイント内容</td>
				<td class="n6" align="center">メルマガID</td>
				<td class="n6" align="center">取得ﾎﾟｲﾝﾄ</td>
				<td class="n6" align="center">　残高　</td>
			</tr>
<?php
$balance = 0;
$sql = "SELECT to_char(TP.trans_date, 'YYYY/MM/DD') as trans_date, MP.attribution, TP.mag_id, TP.trans_point"
	. " FROM T_POINT_DETAIL TP, M_POINT_ATTRIBUTION MP"
	. " WHERE TP.owner_kind='$kind' AND owner_id=$id AND TP.attribution_cd=MP.attribution_cd"
	. " ORDER BY TP.trans_date";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$trans_date = $fetch->trans_date;
	$attribution = $fetch->attribution;
	$mag_id = $fetch->mag_id;
	$trans_point = $fetch->trans_point;
	$balance = $balance + $trans_point;
?>
			<tr>
				<td align="center"><?= $trans_date ?></td>
				<td align="left"><?= $attribution ?></td>
				<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= replace_br(trim($mag_id)) ?></a></td>
				<td align="right"><?= number_format($trans_point) ?></td>
				<td align="right"><?= number_format($balance) ?></td>
			</tr>
<?php
}
?>
		</table>
	<input type="button" value="　閉じる　" onclick="window.close()"></form>
	</center>
</form>
</body>
</html>
