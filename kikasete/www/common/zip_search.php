<?
/******************************************************
' System :�������ơ�net���̽���
' Content:͹���ֹ渡��
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

//===  <option>�������� ===
function option($val, $sel) {
	if ($val == $sel)
		$selected = ' selected';

	return "<option$selected>$val</option>\n";
}

//=== �Զ�Į¼�������� ===
function write_shikuchouson($todoufuken, $shikuchouson) {
	echo '<option ', value_selected('', $shikuchouson), ">���������Ӥ�����������</option>\n";
	if ($todoufuken != '') {
		$sql = "SELECT zc_shikuchouson,MIN(zc_seq_no) AS seq_no FROM m_zip WHERE zc_todoufuken='$todoufuken' GROUP BY zc_shikuchouson ORDER BY seq_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			echo option($fetch->zc_shikuchouson, $shikuchouson);
		}
	}
}

//=== Į̾ɽ�� ===
function write_choumei($todoufuken, $shikuchouson) {
	$sql = "SELECT zc_zip_cd,zc_chouiki FROM m_zip WHERE zc_todoufuken='$todoufuken' AND zc_shikuchouson='$shikuchouson' ORDER BY zc_seq_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
	  $zip_cd = $fetch->zc_zip_cd;
	  echo "<option value='$zip_cd'>",substr($zip_cd, 0, 3), '-', substr($zip_cd, 3, 4), "&nbsp;$fetch->zc_chouiki</option>\n";
	}
}
?>
<html lang="ja">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<meta http-equiv="Pragma" content="no-cache">
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
	if (opener && !opener.closed) {
		index = document.form1.choumei.selectedIndex;
		if (index >= 0) {
			zip = document.form1.choumei.options[index].value;
			if (document.form1.pfc.value == "1") {
				opener.document.form1.jitaku_zip1.value = zip.substr(0, 3);
				opener.document.form1.jitaku_zip2.value = zip.substr(3, 4);
				window.close();
			} else if (document.form1.pfc.value == "2") {
				opener.document.form1.kinmu_zip1.value = zip.substr(0, 3);
				opener.document.form1.kinmu_zip2.value = zip.substr(3, 4);
				window.close();
			}
		}
	}
}
//-->
</SCRIPT>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table width="701" border="0" cellspacing="0" cellpadding="0">
			<tr>
      <td width="234"><a href="index.php"><img src="images/kikasete.gif" width="234" height="73" alt="��������net" border="0"></a></td>
				<td width="185"><img src="images/kekasete2.gif" width="185" height="73" alt="���ʤ�������ʹ�����Ƥ���������10���ͤΥޡ����ƥ��󥰥�˥����ͥåȥ��"></td>
				<td width="282"><img src="images/kekasete3.gif" width="282" height="73" alt="���ʤ�������ʹ�����Ƥ���������10���ͤΥޡ����ƥ��󥰥�˥����ͥåȥ��"></td>
			</tr>
		</table>
		<form method="post" name="form1">
		<input type="hidden" name="pfc" value="<?=$pfc?>">
		<br>
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36" align="right" valign="top"></td>
				<td width="550" height="36" valign="top">
					<div align="left">
						<font color="#2f2180"><b><img height="26" width="435" src="images/zip_search.gif" alt="͹���ֹ椫�󤿤󥵡���"></b></font></div>
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
							<td width="40"></td>
							<td><font size="2" color="#b2cce4">��</font><font size="2">��ƻ�ܸ�</font></td>
							<td><select name="todoufuken" size="1" onChange="OnChange_todoufuken()">
									<option <?=value_selected('', $todoufuken)?>>���������Ӥ�����������</option>
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
<?write_shikuchouson($todoufuken, $shikuchouson)?>
								</select></td>
						</tr>
					</table><br>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36">�� </td>
				<td width="550" height="36">
					<hr>
<?if ($sel_mode == '2') {?>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td align="left" valign="top" width="40"></td>
							<td width="441" align="left" valign="top"><b>�����Ǥ��ޤ�����<br>
								</b><br>
								<select name="choumei" size=8 onChange="OnChange_choumei()">
<?write_choumei($todoufuken, $shikuchouson)?>
								</select>
<?if ($pfc != '') {?>
								<br><br>
								</a><font size="2">͹���ֹ�򥯥�å����Ƥ���������<br>
								��͹���ֹ�򥯥�å�����ȡ���Ͽ���̤˼�ưŪ��ɽ������ޤ�����</font>
<?}?>
							</td>
						</tr>
					</table>
<?}?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="sel_mode">
		</form>
	</body>

</html>
