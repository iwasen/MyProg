<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�᡼��˥塼���ۿ�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/select.php");

set_global('monitor', '��˥�������', '�᡼��˥塼���ۿ�', BACK_TOP);

$max_send = 5;
$today = getdate(time());
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	var f = document.form1;
	set_show_hide();
	f.subject.focus();
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		return input_check(f, "�᡼��˥塼������Ͽ���ޤ���������Ǥ�����");
	case "test":
		return input_check(f, "�᡼��˥塼����ƥ����������ޤ���������Ǥ�����");
	}
	return false;
}
function input_check(f, msg) {
	if (f.subject.value == "") {
		alert("��̾�����Ϥ��Ƥ���������");
		f.subject.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("��ʸ�����Ϥ��Ƥ���������");
		f.body.focus();
		return false;
	}
	if (!f.send_assign_flag[0].checked && !f.send_assign_flag[1].checked) {
		alert("��������������򤷤Ƥ���������");
		f.send_assign_flag[0].focus();
		return false;
	}
	if (f.send_assign_flag[1].checked) {
		if (f.send_assign.value == "") {
			alert("�����������Ϥ��Ƥ���������");
			f.send_assign.focus();
			return false;
		}
		if (!check_num(f.send_assign.value)) {
			alert("��������Ⱦ�ѿ��������Ϥ��Ƥ���������");
			f.send_assign.focus();
			return false;
		}
		if (f.send_assign.value <= 0) {
			alert("��������1�ʾ�ο��������Ϥ��Ƥ���������");
			f.send_assign.focus();
			return false;
		}
	}
	if (!f.send_type[0].checked && !f.send_type[1].checked && !f.send_type[2].checked) {
		alert("���������פ����򤷤Ƥ���������");
		f.send_type[0].focus();
		return false;
	}
	var now = new Date();
	if (f.send_type[1].checked) {
		if (!check_date(f.send_date_y.value, f.send_date_m.value, f.send_date_d.value)) {
			alert("���������λ��꤬����������ޤ���");
			f.send_date_d.focus();
			return false;
		}
		if (new Date(f.send_date_y.value, f.send_date_m.value - 1, f.send_date_d.value, f.send_date_h.value) < now) {
			if (!confirm("���������˲������դ����Ϥ���Ƥ��ޤ�����������Ǥ�����")) {
				f.send_date_y.focus();
				return false;
			}
		}
	}
	if (f.send_type[2].checked) {
		for (var i = 1; i <= f.send_count.value; i++) {
			if (!check_date(f["send_date_y_" + i].value, f["send_date_m_" + i].value, f["send_date_d_" + i].value)) {
				alert("���������λ��꤬����������ޤ���");
				f["send_date_d_" + i].focus();
				return false;
			}
		}
		if (new Date(f.send_date_y_1.value, f.send_date_m_1.value - 1, f.send_date_d_1.value, f.send_date_h_1.value) < now) {
			if (!confirm("���������˲������դ����Ϥ���Ƥ��ޤ�����������Ǥ�����")) {
				f.send_date_y_1.focus();
				return false;
			}
		}
		for (var i = 1; i < f.send_count.value; i++) {
			var j = i + 1;
			if (new Date(f["send_date_y_" + i].value, f["send_date_m_" + i].value - 1, f["send_date_d_" + i].value, f["send_date_h_" + i].value) > new Date(f["send_date_y_" + j].value, f["send_date_m_" + j].value - 1, f["send_date_d_" + j].value, f["send_date_h_" + j].value)) {
				alert("ʬ�����������ν��������������ޤ���");
				f["send_date_y_" + j].focus();
				return false;
			}
		}
	}
	return confirm(msg);
}
function set_show_hide() {
	var f = document.form1;
	document.getElementById("id_send_assign").style.display = f.send_assign_flag[1].checked ? "" : "none";
	document.getElementById("id_send_date").style.display = f.send_type[1].checked ? "" : "none";
	document.getElementById("id_add_count").style.display = (f.send_type[2].checked && f.send_count.value < <?=$max_send?>) ? "" : "none";
	var sc = f.send_type[2].checked ? f.send_count.value : 0;
	for (var i = 1; i <= <?=$max_send?>; i++)
		document.getElementById("id_send_count_" + i).style.display = i <= sc ? "" : "none";
}
function add_send_count() {
	var f = document.form1;
	f.send_count.value++;
	set_show_hide();
}
function check_num(s) {
	for (var i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9")
			return false;
	}
	return true;
}
function check_date(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (isNaN(dt))
		return false;
	else {
		if (dt.getYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
		return true;
	else
		return false;
	}
}
//-->
</script>
</head>
<body onload="onLoad_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���᡼��˥塼�������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��̾<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="kanji" type="text" name="subject" size=80 maxlength=100>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ʸ<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea name="body" cols=78 rows=20></textarea><br>
			<span class="note">��ʸ�������¤Ϥ���ޤ����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����������<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="send_assign_flag" value="0" onclick="set_show_hide()">���ʤ�&nbsp;
			<input type="radio" name="send_assign_flag" value="1" onclick="set_show_hide()">����
			<span id="id_send_assign">
				<input class="number" type="text" name="send_assign" size="8">��
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����������<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="send_type" value="1" onclick="set_show_hide()">����������¨����<br>
			<input type="radio" name="send_type" value="2" onclick="set_show_hide()">���������ʻ��ֻ����
			<span id="id_send_date">
				<select name="send_date_y"><?select_year($today['year'], '', $today['year'])?></select>ǯ
				<select name="send_date_m"><?select_month('', $today['mon'])?></select>��
				<select name="send_date_d"><?select_day('', $today['mday'])?></select>��
				<select name="send_date_h"><?select_hour('', $today['hours'])?></select>��
			</span><br>
			<input type="radio" name="send_type" value="3" onclick="set_show_hide()">ʬ������
			<span id="id_add_count">
				<input type="button" value="����������ɲä���" onclick="add_send_count()">
			</span>
		</td>
	</tr>
<?
for ($i = 0; $i < $max_send; $i++) {
	$n = $i + 1;
?>
	<tr id="id_send_count_<?=$n?>">
		<td class="m1"><?=$n?>����</td>
		<td class="n1">
			<select name="send_date_y_<?=$n?>"><?select_year($today['year'], '', $today['year'])?></select>ǯ
			<select name="send_date_m_<?=$n?>"><?select_month('', $today['mon'])?></select>��
			<select name="send_date_d_<?=$n?>"><?select_day('', $today['mday'])?></select>��
			<select name="send_date_h_<?=$n?>"><?select_hour('', $today['hours'])?></select>��<br>
		</td>
	</tr>
<?
}
?>
</table>
<br>
<input type="submit" value="�ƥ�������" onclick="document.form1.next_action.value='test'">
<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="send_count" value="2">
</form>
</div>

<? page_footer() ?>
</body>
</html>
