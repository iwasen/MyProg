<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ץ�ե����뿷���ɲ�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('monitor', '��˥�������', '�ץ�ե������ư�ɲ�', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function add_sel() {
	var tb = document.getElementById("sel").getElementsByTagName("TBODY")[0];
	var tr = tb.laxxxhild;
	var new_row = tr.cloneNode(true);
	var input = new_row.getElementsByTagName("INPUT");
	input[0].value = parseInt(input[0].value) + 10;
	input[1].value = "";
	input[2].value = "";
	tb.insertBefore(new_row, null);
}
function onsubmit_form1(f) {
	var sel_flag = false;
	if (f.order.value == "") {
		alert("ɽ��������Ϥ��Ƥ���������");
		f.order.focus();
		return false;
	}
	if (!check_num(f.order.value)) {
		alert("ɽ�����Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.order.focus();
		return false;
	}
	if (f.profile_name.value == "") {
		alert("����̾���Ϥ��Ƥ���������");
		f.profile_name.focus();
		return false;
	}
	if (f["sel_order[]"].length) {
		for (var i = 0; i < f["sel_order[]"].length; i++) {
			if (!check_sel(f["sel_order[]"][i], f["sel_text[]"][i]))
				return false;
			if (f["sel_text[]"][i].value != "")
				sel_flag = true;
		}
	} else {
		if (!check_sel(f["sel_order[]"], f["sel_text[]"]))
			return false;
		if (f["sel_text[]"].value != "")
			sel_flag = true;
	}
	if (!sel_flag) {
		alert("����褬�ҤȤĤ����Ϥ���Ƥ��ޤ���");
		return false;
	}
	return confirm("�ɲåץ�ե��������Ͽ���ޤ���������Ǥ�����");
}
function check_sel(sel_order, sel_text) {
	if (sel_order.value == "" && sel_text.value != "") {
		alert("�����ɽ��������Ϥ��Ƥ���������");
		sel_order.focus();
		return false;
	}
	if (!check_num(sel_order.value)) {
		alert("�����ɽ�����Ⱦ�ѿ��������Ϥ��Ƥ���������");
		sel_order.focus();
		return false;
	}
	if (sel_order.value != "" && sel_text.value == "") {
		alert("���������Ϥ��Ƥ���������");
		sel_text.focus();
		return false;
	}
	return true;
}
function check_num(s) {
	for (var i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9")
			return false;
	}
	return true;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���ɲåץ�ե���������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ɽ����<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="number" type="text" name="order" size=10 maxlength=10>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="profile_name" size=80>
			<span class="note">��ʸ�������¤Ϥ���ޤ����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ��<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="hidden_flag" value="f" checked>ɽ��
			<input type="radio" name="hidden_flag" value="t">��ɽ��
		</td>
	</tr>
	<tr>
		<td class="m1">�����<?=MUST_ITEM?></td>
		<td class="n1">
			<table class="small" id="sel">
				<tr>
					<td>ɽ����</td>
					<td>�����</td>
				</tr>
				<tr>
					<td><input class="number" type="text" name="sel_order[]" size=5 maxlength=5 value="10"></td>
					<td><input class="kanji" type="text" name="sel_text[]" size=60><input type="hidden" name="profile_cd[]" value=""></td>
				</tr>
			</table>
			<input type="button" value="������ɲ�" onclick="add_sel()">
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>
</body>
</html>
