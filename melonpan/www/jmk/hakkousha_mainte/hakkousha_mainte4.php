<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:ȯ�ԼԾ�����ƥʥ�
'******************************************************/

$title_text = $TT_hakkousha_mainte;
$title_color = $TC_HAKKOUSHA;

$sql ="SELECT PUBLISHER_ID, MAIL_ADD_MAIN, OWNER_NM_KANJ, OWNER_NM_KANA, OWNER_ZIP1, OWNER_ZIP2, OWNER_PREFEC, OWNER_ADD, OWNER_TEL, OWNER_PSWD, pt_melonpan";
$sql = $sql . " FROM M_PUBLISHER";
$sql = $sql . " WHERE PUBLISHER_ID = '$pub_id'";
$result = db_exec($sql);

if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, $i);
	$publisher_id = $fetch->publisher_id;
	$mail_add_main = $fetch->mail_add_main;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$owner_nm_kana = $fetch->owner_nm_kana;
	$owner_zip1 = $fetch->owner_zip1;
	$owner_zip2 = $fetch->owner_zip2;
	$owner_prefec = $fetch->owner_prefec;
	$owner_add = $fetch->owner_add;
	$owner_tel = $fetch->owner_tel;
	$owner_pswd = $fetch->owner_pswd;
	$pt_melonpan = $fetch->pt_melonpan;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=650,height=700");
  win.focus();
}
function OnClick_sakujo(publisher_id) {
	return confirm("������ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1" action="hakkousha_mainte5.php">
	<center>
		<table width="800">
			<tr>
				<td>�����ޥ�ȯ�ԼԾ���</td>
			</tr>
		</table>
		<table width='800' border="1" CELLSPACING="0" CELLPADDING="3">
			<tr>
				<td class="m6s" colspan="2">ȯ�ԼԴ��ܾ���</td>
			</tr>
			<tr>
				<td width="35%" class="n6">���ܥ᡼�륢�ɥ쥹</td>
				<td><?= htmlspecialchars($mail_add_main) ?></td>
			</tr>
			<tr>
				<td class="n6"><nobr>ȯ�ԼԻ�̾[����]</nobr></td>
				<td><?= htmlspecialchars($owner_nm_kanj) ?></td>
			</tr>
			<tr>
				<td class="n6"><nobr>ȯ�ԼԻ�̾[����]</nobr></td>
				<td><?= htmlspecialchars($owner_nm_kana) ?></td>
			</tr>
			<tr>
				<td class="n6"><nobr>Ϣ����͹���ֹ�</nobr></td>
				<td><?= $owner_zip1 ?>-<?= $owner_zip2 ?></td>
			</tr>
			<tr>
				<td class="n6">Ϣ���轻��</td>
				<td><?= $owner_prefec ?> <?= htmlspecialchars($owner_add) ?></td>
			</tr>
			<tr>
				<td class="n6">Ϣ���������ֹ�</td>
				<td><?= $owner_tel ?></td>
			</tr>
			<tr>
				<td class="n6">�ѥ����</td>
				<td><?= htmlspecialchars($owner_pswd) ?></td>
			</tr>
		</table><br>
		<table width="800">
			<tr>
				<td>��ȯ����Υ��ޥ���������ϡ�ȯ�ԼԤκ���ϤǤ��ޤ���</td>
			</tr>
		</table>
		<table width='800' border="1" CELLSPACING="0" CELLPADDING="3">
			<tr>
				<td class="m6s" colspan="5">ȯ�ԥ��ޥ�����</td>
			</tr>
			<tr>
				<td class="n6" align="center" width="10%">��ǧ</td>
				<td class="n6" align="center" width="10%">ȯ��</td>
				<td class="n6" align="center" width="10%">���ơ�����</td>
				<td class="n6" align="center" width="10%">���ޥ�ID</td>
				<td class="n6" align="center" width="60%">���ޥ�̾</td>
			</tr>
<?php
	$sql = "SELECT MAG_ID, MAG_NM, MAG_STATUS_FLG, MAG_PUB_STOP_FLG, MAG_PUB_STATUS_FLG FROM M_MAGAZINE WHERE PUBLISHER_ID='$publisher_id' ORDER BY MAG_ID";
$result = db_exec($sql);
$nrow = pg_numrows($result);

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_pub_status_flg = $fetch->mag_pub_status_flg;
	$mag_status_flg = $fetch->mag_status_flg;
	$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;

	switch ($mag_pub_status_flg) {
		case "00";
			$mag_pub_status = "������";
			break;
		case "01";
			$mag_pub_status = "����Ͽ";
			break;
		case "02";
			$mag_pub_status = "��ǧ����";
			break;
		case "03";
			$mag_pub_status = "����Ͽ";
			break;
		case "99";
			$mag_pub_status = "�Ѵ�";
			break;
	}
	switch ($mag_status_flg) {
		case "00";
			$mag_status = "ȯ����";
			$hakkou_flg = 1;
			break;
		case "01";
			$mag_status = "�ٴ���";
			break;
		case "99";
			$mag_status = "�Ѵ�";
			break;
	}
	switch ($mag_pub_stop_flg) {
		case "00";
			$mag_pub_stop = "�ϣ�";
			break;
		case "01";
			$mag_pub_stop = "<font color=\"orange\">��</font>";
			break;
		case "02";
			$mag_pub_stop = "<font color=\"red\">��</font>";
			break;
	}
?>
			<tr>
				<input type="hidden" name="mag_id[]" value="<?= $mag_id ?>">
				<td align="center"><?= $mag_pub_status ?></td>
				<td align="center"><?= $mag_status ?></td>
				<td align="center"><?= $mag_pub_stop ?></td>
				<td align="center"><?= $mag_id ?></td>
				<td align="left"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
			</tr>
<?php
	}
}
?>
		</table><br>
	<input type="hidden" name="publisher_id" value=<?= $publisher_id ?>>
<? if ($hakkou_flg <> 1) { ?>
	<input type="hidden" name="pt_melonpan" value=<?= $pt_melonpan ?>>
	<input type="submit" value=" ��� " onclick="OnClick_sakujo('<?= $publisher_id ?>')">
<? } ?>
	<input type="button" value=" ��� " onclick="history.back()">
	</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
