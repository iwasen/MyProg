<?
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:ȯ�ԼԾ���ɽ������
'******************************************************/

$title_text = "ȯ�ԼԾ���ɽ��";
$title_color = $TC_MASTER;

$sql ="SELECT PUBLISHER_ID, MAIL_ADD_MAIN, OWNER_NM_KANJ, OWNER_NM_KANA, OWNER_ZIP1, OWNER_ZIP2"
	. ", OWNER_PREFEC, OWNER_ADD, OWNER_TEL, OWNER_PSWD, pt_melonpan"
	. " FROM M_PUBLISHER"
	. " WHERE PUBLISHER_ID = '$pub_id'";
$result = db_exec($sql);
$nrow = pg_numrows($result);

if ($nrow > 0) {
	$fetch = pg_fetch_object($result, 0);
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
<title>ȯ�ԼԾ���</title>
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
		<table width='550'>
			<tr>
				<td align=right valign=bottom norap><small><font color=blue>��</font><A href='JavaScript:window.close()'>�Ĥ���</A></small></td>
				</td>
			</tr>
			<tr>
				<td>�����ޥ�ȯ�ԼԾ���</td>
			</tr>
		</table>
		<table width='550' border="1" CELLSPACING="0" CELLPADDING="3">
			<tr>
				<td class="m6s" colspan="2">ȯ�ԼԴ��ܾ���</td>
			</tr>
			<tr>
				<td width="35%" class="n6">ȯ�Լ�ID</td>
				<td><?= $publisher_id ?></td>
			</tr>
			<tr>
				<td width="35%" class="n6">���ܥ᡼�륢�ɥ쥹</td>
				<td><?= $mail_add_main ?></td>
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
				<td><?= $owner_pswd ?></td>
			</tr>
			<tr>
				<td class="n6">����Ѥ�ݥ����</td>
				<td><?= number_format($pt_melonpan) ?> �ݥ����</td>
			</tr>
		</table><br>
		<table width='550' border="1" CELLSPACING="0" CELLPADDING="3">
			<tr>
				<td class="m6s" colspan="3">ȯ�ԥ��ޥ�����</td>
			</tr>
			<tr>
				<td class="n6" align="center">status</td>
				<td class="n6" align="center">���ޥ�ID</td>
				<td class="n6" align="center">���ޥ�̾</td>
			</tr>
<?php
$sql = "SELECT MAG_ID, MAG_NM, MAG_PUB_STATUS_FLG, MAG_STATUS_FLG, MAG_PUB_STOP_FLG FROM M_MAGAZINE WHERE PUBLISHER_ID='$publisher_id' ORDER BY MAG_ID";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_pub_status = $mag_pub_stop = $mag_status = "";	//�ѿ������(2001.10.09)
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$mag_pub_status_flg = $fetch->mag_pub_status_flg;
	$mag_status_flg = $fetch->mag_status_flg;
	$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	if ($mag_pub_status_flg == '00') { $mag_pub_status = "<font color=\"orange\">��</font>";	}
	if ($mag_pub_status_flg == '01') { $mag_pub_status = "<font color=\"green\">��</font>";	}
	if ($mag_pub_status_flg == '02') { $mag_pub_status = "<font color=\"red\">��</font>";	}
	if ($mag_pub_status_flg == '03') { $mag_pub_status = "<font color=\"blue\">��</font>";	}
	if ($mag_pub_status_flg == '99') { $mag_pub_status = "<font color=\"red\">��</font>";	}
	if ($mag_status_flg == '01') { $mag_status = "/<font color=\"red\">��</font>";	}
	if ($mag_pub_stop_flg == '01') { $mag_pub_stop = "/<font color=\"orange\">Y</font>";	}
	if ($mag_pub_stop_flg == '02') { $mag_pub_stop = "/<font color=\"red\">R</font>";	}

?>
			<tr>
				<td align="center"><?= $mag_pub_status ?><?= $mag_status ?></td>
				<td align="center"><?= $mag_id ?></td>
				<td align="left"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
			</tr>
<?php
	}
}
?>
		</table>
	<input type="button" value="���Ĥ��롡" onclick="window.close()"></form>
	</center>
</form>
</body>
</html>
