<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�᡼�륢�ɥ쥹�����꡼�˥󥰡������ӥ�
'******************************************************/

$title_text = $TT_mail_cleaning;
$title_color = $TC_OTHER;

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
  document.form1.mag_id.focus();
}
function OnClick_mag() {
  var win;
	if (document.form1.mag_id.value == "") {
		alert("���ޥ�ID�����Ϥ��Ƥ���������");
		return;
	}
	mag_id=document.form1.mag_id.value;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=700");
  win.focus();
}
function OnSubmit_form1(frm) {
	if (frm.mag_id.value == "") {
		alert("���ޥ�ID�����Ϥ��Ƥ���������");
		frm.mag_id.focus();
		return false;
	}
	if (frm.importfile.value == "") {
		alert("����ݡ��ȥե���������Ϥ��Ƥ���������");
		frm.importfile.focus();
		return false;
	}
	return confirm("����ݡ��Ȥ��ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" ENCTYPE="multipart/form-data" action="import2.php" onSubmit="return OnSubmit_form1(this);">
<table border=0 cellspacing=0 cellpadding=0 width="700">
	<tr>
		<td>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td>�����ޥ������Ͽ</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=2 width="100%">
				<tr>
					<td class="h1" align="center" width="30%"><nobr>���ޥ�ID</nobr></td>
					<td>
						<input type="text" size="10" maxlength="6" name="mag_id">
						<font class="note">(��Ƭ��"0"�Ͼ�ά�Ǥ��ޤ���)</font>
						<input type="button" value="�Ҏَώ��޳�ǧ" OnClick='JavaScript:OnClick_mag()'>
					</td>
				</tr>
				<tr>
					<td class="h1" align="center" width="30%"><nobr>�ɲ���Ͽ</nobr></td>
					<td>
						<input type="checkbox" name="bakyun" value="1" checked>�С����塼��
						<input type="checkbox" name="letter" value="1" checked>����Ѥ�쥿��
					</td>
				</tr>
				<tr>
					<td class="h1" align="center" width="30%"><nobr>����ݡ��ȥե�����</nobr></td>
					<td><input type="file" name="importfile" size=60></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����ݡ���">
<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
