<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���𥯥�å�����������
'******************************************************/

$title_text = $TT_click;
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
function onLoad_body() {
	document.form1.cc_click_name.focus();
}
function OnClick_info() {
  var win;
  win = window.open(document.form1.cc_jump_url.value, "jump_url", "scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes,location=yes");
  win.focus();
}

function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@:";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}

function OnSubmit_form1() {
  with (document.form1) {
    if (cc_click_name.value == "") {
      alert("����̾�����Ϥ��Ƥ���������");
      cc_click_name.focus();
      return false;
    }
    if (cc_client_name.value == "") {
      alert("���饤�����̾�����Ϥ��Ƥ���������");
      cc_client_name.focus();
      return false;
    }
    if (cc_start_date.value.length != 8) {
      alert("���դ��������ʤ��褦�Ǥ���");
      cc_start_date.focus();
      return false;
    }
		if (!num_chk(cc_start_date.value)) {
			alert("Ⱦ�ѿ��������Ϥ��Ƥ���������");
			cc_start_date.focus();
			return false;
		}
    if (cc_all_num.value <= 0) {
      alert("���ο������Ϥ��Ƥ���������");
      cc_all_num.focus();
      return false;
    }
		if (!num_chk(cc_all_num.value)) {
			alert("Ⱦ�ѿ��������Ϥ��Ƥ���������");
			cc_all_num.focus();
			return false;
		}
    if (cc_jump_url.value == "") {
      alert("URL�����Ϥ��Ƥ���������");
      cc_jump_url.focus();
      return false;
    }
    if (cc_jump_url.value.length <= 7) {
      alert("URL���������ʤ��褦�Ǥ���");
      cc_jump_url.focus();
      return false;
    }
		if (!isA(cc_jump_url.value)) {
			alert("URL��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			cc_jump_url.focus();
			return false;
		}
	}
	return confirm("��Ͽ���ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body onload="onLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="click3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">�����𥯥�å������󥿾ܺ�����</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="800">
		<tr>
			<td class="m4"><nobr>����̾</nobr></td>
			<td class="n6"><input size=80 name="cc_click_name" maxlength=100></td>
		</tr>
		<tr>
			<td class="m4"><nobr>���饤�����̾</nobr></td>
			<td class="n6"><input size=80 name="cc_client_name" maxlength=100></td>
		</tr>
		<tr>
			<td class="m4"><nobr>������</nobr></td>
			<td class="n6"><input size=20 name="cc_start_date" maxlength=8><font class="note">��Ⱦ�ѿ����Τߡ��㡧20020101��</font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>���ο�</nobr></td>
			<td class="n6"><input size=10 name="cc_all_num" maxlength=10></td>
		</tr>
		<tr>
			<td class="m4"><nobr>������URL</nobr></td>
			<td class="n6" colspan="2">
				<nobr>
				<input size=80 name="cc_jump_url" maxlength=100 value="http://">
				<input type="button" value="��ǧ" onclick="OnClick_info()">
				<font class="note">��Ⱦ�ѱѿ���</font></td>
				</nobr>
		</tr>
<!--
		<tr>
			<td class="m4"><nobr>����å������������URL</nobr></td>
			<td class="n6" colspan="2"><BR></td>
		</tr>
-->
	</table><br>
	<input type="hidden" name="next_action" value="new">
	<input type="submit" value=" ��Ͽ " onclick="document.form1.next_action.value='new'">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
