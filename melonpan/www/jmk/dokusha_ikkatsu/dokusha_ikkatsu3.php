<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ɼ԰����Ͽ/���
'******************************************************/

$title_text = $TT_dokusha_ikkatsu;
$title_color = $TC_DOKUSHA;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.add_adr.focus();
}
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=700");
  win.focus();
}
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
function OnClick_add(mag_id) {
	location.href = "dokusha_ikkatsu3.php?mag_id=" + mag_id;
}
function OnSubmit_form1() {
 with (document.form1) {
    if (add_adr.value == "") {
      alert("�ɲä����ɼԤΥ᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
      add_adr.focus();
      return false;
		}
  }
	return confirm("��Ͽ���ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1" action="dokusha_ikkatsu4.php" onSubmit="return OnSubmit_form1();">
<center>
<table border=0 cellspacing=0 cellpadding=0 width='80%'>
	<tr>
		<td>���ɼ԰����Ͽ</td>
			<table border=1 cellspacing=0 cellpadding=3 width='80%'>
				<tr bgcolor="#ffcc99">
					<th align="center"><nobr>���ޥ�ID</nobr></th>
					<th align="center"><nobr>���ޥ�̾</nobr></th>
					<th align="center"><nobr>ȯ�Լ�</nobr></th>
					<th align="center"><nobr>�᡼�륢�ɥ쥹</nobr></th>
				</tr>
<?php
if ($mag_id <> "") {
	$mag_id = sprintf("%06d", $mag_id);

	$sql = "SELECT MAG.MAG_ID,MAG.MAG_PUB_STOP_FLG,MAG.MAG_NM,PUB.PUBLISHER_ID,PUB.OWNER_NM_KANJ,PUB.MAIL_ADD_MAIN"
		. " FROM M_MAGAZINE MAG,M_PUBLISHER PUB "
		. " WHERE MAG.MAG_ID = '$mag_id' AND MAG.PUBLISHER_ID = PUB.PUBLISHER_ID "
		. " ORDER BY MAG.MAG_ID";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$publisher_id = $fetch->publisher_id;
		$mag_nm = $fetch->mag_nm;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$mail_add_main = $fetch->mail_add_main;
?>
				<tr>
					<td align="center"><?= $mag_id ?></td>
					<td><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
					<td align="left"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= htmlspecialchars($owner_nm_kanj) ?></a></td>
					<td align="left"><a href="mailto:<?= $mail_add_main ?>"><?= $mail_add_main ?></a></td>
				</tr>
<?php
	}
}
?>
			</table><br>
		</td>
	</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0 width="60%">
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=0>
				<tr bgcolor="#ccffcc">
					<td>��¸�ɼԥ᡼�륢�ɥ쥹</td>
					<td><textarea cols="70" rows="8">
<?
$sql = "SELECT * FROM T_MAILADDR WHERE mag_id='$mag_id' ORDER BY mail_addr";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mail_addr = $fetch->mail_addr;
	print $mail_addr . "\n";
}
?>
						</textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table><br>
<table border=0 cellspacing=0 cellpadding=0 width="60%">
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=0>
				<tr bgcolor="#ccffcc">
					<td>�ɲ��ɼԥ᡼�륢�ɥ쥹</td>
					<td><textarea name=add_adr cols="70" rows="8"></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table><br>
	<input type="hidden" name="mag_id" value="<?= $mag_id ?>">
	<input type="submit" value=" ��Ͽ ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="history.back()">
</center>
</form>

</SCRIPT>

<? footer_jmk(0); ?>

</body>
</html>
