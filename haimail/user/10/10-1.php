<?
include('../inc/common.php');
include('../inc/database.php');

//===  <option>�������� ===
function option($val, $sel) {
	if ($val == $sel)
		$selected = ' selected';

	return "<option$selected>$val</option>\n";
}

//=== �Զ�Į¼�������� ===
function WriteShikuchouson() {
	global $todoufuken, $shikuchouson;

	Writeln("<option value=''" . Selected(shikuchouson, '') . ">���������Ӥ�����������</option>");
	if ($todoufuken != '') {
		$sql = "SELECT ZC_shikuchouson,MIN(ZC_seq_no) AS seq_no FROM T_ZIPCODE WHERE ZC_todoufuken='$todoufuken' GROUP BY ZC_shikuchouson ORDER BY seq_no";
		$ds = CreateDynaset($sql, ORADYN_READONLY);
		while (!$ds->EOF()) {
			Writeln("<option" . Selected($shikuchouson, $ds->GetValue('ZC_shikuchouson')) . '>' . $ds->GetValue('ZC_shikuchouson') . "</option>");
			$ds->MoveNext();
		}
	}
}

//=== Į̾ɽ�� ===
function WriteChoumei() {
	global $todoufuken, $shikuchouson;

	$sql = "SELECT ZC_zip_code,ZC_chouiki FROM T_ZIPCODE WHERE ZC_todoufuken='$todoufuken' AND ZC_shikuchouson='$shikuchouson' ORDER BY ZC_seq_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	while (!$ds->EOF()) {
		$zip_code = $ds->GetValue('ZC_zip_code');
		Writeln("<option value='$zip_code'>" .substr($zip_code, 0, 3) . '-' . substr($zip_code, 3, 4) . '&nbsp;' . $ds->GetValue('ZC_chouiki') . "</option>");
		$ds->Movenext();
	}
}
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>͹���ֹ椫�󤿤󥵡���</title>
<SCRIPT LANGUAGE=javascript>
<!--
function OnChange_todoufuken() {
	document.form1.sel_mode.value = "1";
	document.form1.submit();
}
function OnChange_shikuchouson() {
	document.form1.sel_mode.value = "2";
	document.form1.submit();
}
function OnChange_choumei() {
	var index, zip;
	index = document.form1.choumei.selectedIndex;
	if (index >= 0) {
		zip = document.form1.choumei.options[index].value;
		if (document.form1.pfc.value == "1") {
			opener.document.form1.zip_kyojuu1.value = zip.substr(0, 3);
			opener.document.form1.zip_kyojuu2.value = zip.substr(3, 4);
			window.close();
		} else if (document.form1.pfc.value == "2") {
			opener.document.form1.zip_kinmu1.value = zip.substr(0, 3);
			opener.document.form1.zip_kinmu2.value = zip.substr(3, 4);
			window.close();
		}
	}
}
//-->
</SCRIPT>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="58">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="�Ϥ��᡼�롦net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form method="post" name="form1">
		<input type="hidden" name="pfc" value="<?=$pfc?>">
		<br>
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="550" height="36" valign="top">
					<div align="left">
						<font color="#2f2180"><b><img height="26" width="435" src="img/10-title.gif" alt="͹���ֹ椫�󤿤󥵡���"></b></font></div>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36" align="right" valign="top">��</td>
				<td width="550" height="36"><font size="2">���ʤ���Ĵ�٤����ϰ�����Ϥ���ȡ���ñ��͹���ֹ椬�����Ǥ��ޤ���<br>
					��ƻ�ܸ����������ˡ��Զ�Į¼������ǲ�������������̤�Ĵ�٤�͹���ֹ椬ɽ������ޤ���</font></td>
			</tr>
			<tr>
				<td width="50" align="right" valign="top">��</td>
				<td width="550" align="left" valign="top"><br>
					<table border="0" cellpadding="0" cellspacing="2" width="376">
						<tr>
							<td width="40"><img height="16" width="40" src="../img/space.gif"></td>
							<td><font size="2" color="#b2cce4">��</font><font size="2">��ƻ�ܸ�</font></td>
							<td><select name="todoufuken" size="1" onChange="OnChange_todoufuken()">
									<option value=""<?=Selected($todoufuken, '')?>>���������Ӥ�����������</option>
									<?=option("�̳�ƻ", $todoufuken)?>
									<?=option("�Ŀ���", $todoufuken)?>
									<?=option("��긩", $todoufuken)?>
									<?=option("�ܾ븩", $todoufuken)?>
									<?=option("���ĸ�", $todoufuken)?>
									<?=option("������", $todoufuken)?>
									<?=option("ʡ�縩", $todoufuken)?>
									<?=option("��븩", $todoufuken)?>
									<?=option("���ڸ�", $todoufuken)?>
									<?=option("���ϸ�", $todoufuken)?>
									<?=option("��̸�", $todoufuken)?>
									<?=option("���ո�", $todoufuken)?>
									<?=option("�����", $todoufuken)?>
									<?=option("�����", $todoufuken)?>
									<?=option("���㸩", $todoufuken)?>
									<?=option("�ٻ���", $todoufuken)?>
									<?=option("���", $todoufuken)?>
									<?=option("ʡ�温", $todoufuken)?>
									<?=option("������", $todoufuken)?>
									<?=option("Ĺ�", $todoufuken)?>
									<?=option("���츩", $todoufuken)?>
									<?=option("�Ų���", $todoufuken)?>
									<?=option("���θ�", $todoufuken)?>
									<?=option("���Ÿ�", $todoufuken)?>
									<?=option("���츩", $todoufuken)?>
									<?=option("������", $todoufuken)?>
									<?=option("�����", $todoufuken)?>
									<?=option("ʼ�˸�", $todoufuken)?>
									<?=option("���ɸ�", $todoufuken)?>
									<?=option("�²λ���", $todoufuken)?>
									<?=option("Ļ�踩", $todoufuken)?>
									<?=option("�纬��", $todoufuken)?>
									<?=option("������", $todoufuken)?>
									<?=option("���縩", $todoufuken)?>
									<?=option("������", $todoufuken)?>
									<?=option("���縩", $todoufuken)?>
									<?=option("���", $todoufuken)?>
									<?=option("��ɲ��", $todoufuken)?>
									<?=option("���θ�", $todoufuken)?>
									<?=option("ʡ����", $todoufuken)?>
									<?=option("���츩", $todoufuken)?>
									<?=option("Ĺ�긩", $todoufuken)?>
									<?=option("���ܸ�", $todoufuken)?>
									<?=option("��ʬ��", $todoufuken)?>
									<?=option("�ܺ긩", $todoufuken)?>
									<?=option("�����縩", $todoufuken)?>
									<?=option("���츩", $todoufuken)?>
								</select></td>
						</tr>
						<tr>
							<td width="40"></td>
							<td><font size="2" color="#b2cce4">��</font><font size="2">�Զ�Į¼</font></td>
							<td><select name="shikuchouson" size="1" onChange="OnChange_shikuchouson()">
<?
WriteShikuchouson();
?>
								</select></td>
						</tr>
					</table><br>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36">�� </td>
				<td width="550" height="36">
					<hr>
<?
if ($sel_mode == '2') {
?>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td align="left" valign="top"><img height="16" width="40" src="../img/space.gif"></td>
							<td width="441" align="left" valign="top"><b>�����Ǥ��ޤ�����<br>
								</b><br>
								<select name="choumei" size=8 onClick="OnChange_choumei()">
<?
	WriteChoumei();
?>
								</select>
<?
	if ($pfc != '') {
?>
								<br><br>
								</a><font size="2">͹���ֹ�򥯥�å����Ƥ���������<br>
								��͹���ֹ�򥯥�å�����ȡ���Ͽ���̤˼�ưŪ��ɽ������ޤ�����</font>
<?
	}
?>
							</td>
						</tr>
					</table>
<?
}
?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="sel_mode">
		</form>
	</body>

</html>
