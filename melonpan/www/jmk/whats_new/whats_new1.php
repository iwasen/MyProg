<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:��What's New������
'******************************************************/

$title_text = $TT_whats_new;
$title_color = $TC_MASTER;

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
  document.form1.new_msg.focus();
}
function OnClick_info() {
  var win;
  win = window.open(document.form1.msg_url.value, "info_mag", "scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes,location=yes");
  win.focus();
}
function OnSubmit_form1() {
	with (document.form1) {
    if (new_msg.value == "") {
      alert("��å����������Ϥ��Ƥ���������");
      new_msg.focus();
      return false;
    }
		str=new_msg.value;
		if (str.length > 100) {
			alert("�����Ȥ�Ĺ�����ޤ���");
			new_msg.focus();
			return(false);
		}
	}
	return confirm("��Ͽ���ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="whats_new3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">����What's New����Ͽ</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="80%">
		<tr>
			<td class="m4"><nobr>��å�����</nobr></td>
			<td class="n6">
				<textarea name="new_msg" cols=80 rows=5></textarea><font class="note">��100ʸ�������</font>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>��󥯤���URL</nobr></td>
			<td class="n6">
				<input size=80 name=msg_url maxlength=200><font class="note">��Ⱦ�ѱѿ���</font>
				<input type="button" value="��ǧ" onclick="OnClick_info()">
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>�������å�</nobr></td>
			<td class="n6">
				<input type="radio" name="target" value="_self" checked>_self��
				<input type="radio" name="target" value="_top">_top��
				<input type="radio" name="target" value="_parent">_parent��
				<input type="radio" name="target" value="_blank">_blank��
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" ��Ͽ ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="location.href='index.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
