<?php
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:My����Ѥ����ɽ��
'******************************************************/
$mail_addr = trim ($mail_addr);

$sql = "SELECT M.MAIL_ADDR,"
  . "to_char(M.MODIFY_DT, 'YYYY') as MF_YEAR,"
  . "to_char(M.MODIFY_DT, 'MM') as MF_MONTH,"
  . "to_char(M.MODIFY_DT, 'DD') as MF_DAY,"
	. "M.READER_PSWD,"
  . "to_char(READER_BIRTH, 'YYYY') as WK_YEAR,"
  . "to_char(READER_BIRTH, 'MM') as WK_MONTH,"
	. "to_char(READER_BIRTH, 'DD') as WK_DAY,"
	. "M.MAIL_ADDR,"
	. "M.READER_SEX,"
	. "M.READER_MRG,"
	. "M.READER_ZIP1,"
	. "M.READER_ZIP2,"
	. "MZ.KEN,"
	. "MO.OCCUP,"
	. "M.PT_MELONPAN"
	. " FROM M_MELONPA M, M_MAG_OCCUP MO, M_ZIPCODE MZ"
	. " WHERE M.reader_id = $reader_id AND M.READER_OCCUP = MO.OCCUP_ID AND M.READER_ZIP1 = MZ.ZIPCODE";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_addr = $fetch->mail_addr;
	$mf_year = $fetch->mf_year;
	$mf_month = $fetch->mf_month;
	$mf_day = $fetch->mf_day;
	$reader_pswd = $fetch->reader_pswd;
	$wk_year = $fetch->wk_year;
	$wk_month = $fetch->wk_month;
	$wk_day = $fetch->wk_day;
	$reader_sex = $fetch->reader_sex;
	$reader_mrg = $fetch->reader_mrg;
	$reader_zip1 = $fetch->reader_zip1;
	$reader_zip2 = $fetch->reader_zip2;
	$ken = $fetch->ken;
	$occup = $fetch->occup;
	$pt_melonpan = $fetch->pt_melonpan;

	if ($reader_sex == 1) {
		$seibetsu = "����";
	} elseif ($reader_sex == 2){
		$seibetsu = "����";
	} else {
		$seibetsu = " - ";
	}
	if ($reader_mrg == 1) {
		$mikikon = "�ȿ�";
	} elseif ($reader_mrg == 2)  {
		$mikikon = "����";
	} else {
		$mikikon = " - ";
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>My����Ѥ����</title>
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
<form method="post" name="form1" method="post" style="margin:0">
<center>
<table width="400">
	<tr>
		<td align=right valign=bottom norap><small><font color=blue>��</font><A href='JavaScript:window.close()'>�Ĥ���</A></small>
		</td>
	</tr>
	<tr>
		<td>��My����Ѥ���Ͽ����
			<table width="400" border="1" cellpadding="3" cellspacing="0">
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">My����Ѥ���Ͽ��</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?=  $mf_year . "ǯ" . sprintf("%2d", $mf_month) . "��" . sprintf("%2d", $mf_day) . "��" ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">�᡼�륢�ɥ쥹</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= htmlspecialchars($mail_addr) ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">�ѥ����</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= htmlspecialchars($reader_pswd) ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">��ǯ����</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?=  $wk_year . "ǯ" . sprintf("%2d", $wk_month) . "��" . sprintf("%2d", $wk_day) . "��" ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">������</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $seibetsu ?> </td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">̤����</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $mikikon ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">�ｻ��</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $reader_zip1 . "-" . $reader_zip2 . " " . $ken ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">������</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $occup ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">�ݥ����</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $pt_melonpan ?>���ݥ����</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="400"><br>
	<tr>
		<td��colspan=2>�����ɥ��ޥ�</td>
	</tr>
	<tr>
		<td bgcolor="#FFCC33" align="center"><font size=-1><nobr>�Ҏَώ���ID</nobr></font></td>
		<td bgcolor="#FFCC33" align="center"><font size=-1><nobr>���ޥ�̾</nobr></font></td>
	</tr>
<?
	$sql = "SELECT TM.mag_id, MM.mag_nm FROM T_MAILADDR TM,M_MAGAZINE MM WHERE TM.mail_addr='$mail_addr' AND TM.mag_id=MM.mag_id ORDER BY mag_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
?>
	<tr>
		<td bgcolor="#FFFFCC" align="center"><font size=-1><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= $mag_id ?></a></font></td>
		<td bgcolor="#FFFFCC" align="left"><font size=-1><?= htmlspecialchars($mag_nm) ?></font></td>
	</tr>
<?
	}
} else {
	print "<center><br>�������ǡ��������Ĥ���ޤ���<br>";
}
?>

</table>
	<input type="button" value="�Ĥ���" onclick="window.close()">
</center>
</form>
</body>
</html>

