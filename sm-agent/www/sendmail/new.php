<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:�ۿ��᡼�뿷������
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/header.php");
include("$inc/select.php");

set_global('�ۿ��᡼�뿷����Ͽ', BACK_TOP);

$today = getdate(time() + (60 * 60 * 24));
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
	show_hide('send_date', f.send_date_check.checked);
	show_hide('limit_date', f.limit_date_check.checked);
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		if (f.list_file.value == "") {
			alert("������ꥹ�ȥե���������Ϥ��Ƥ���������");
			f.list_file.focus();
			return false;
		}
		if (f.send_date_check.checked) {
			d = new Date(f.send_date_y.value, f.send_date_m.value-1, f.send_date_d.value, f.send_date_h.value, f.send_date_i.value);
			if (d < new Date()) {
				alert("����������̤��λ�������ꤷ�Ƥ���������");
				f.send_date_y.focus();
				return false;
			}
		}
		if (f.limit_date_check.checked) {
			d = new Date(f.limit_date_y.value, f.limit_date_m.value-1, (f.limit_date_d.value-0)+1);
			if (d < new Date()) {
				alert("������ڤ�������̤��λ�������ꤷ�Ƥ���������");
				f.limit_date_y.focus();
				return false;
			}
		}
		return input_check(f, "�ۿ��᡼�����Ͽ���ޤ���������Ǥ�����");
	case "test":
		return input_check(f, "�ۿ��᡼���ƥ����������ޤ���������Ǥ�����");
	}
	return false;
}
function input_check(f, msg) {
	if (f.from_addr.value == "") {
		alert("���������ɥ쥹�����Ϥ��Ƥ���������");
		f.from_addr.focus();
		return false;
	}
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
	return confirm(msg);
}
function show_hide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
//-->
</script>
</head>
<body onload="onLoad_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" ENCtype="multipart/form-data" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���ۿ��᡼������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">������ꥹ�ȥե�����<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input type="file" name="list_file" size=80><br>
			<span class="note">�ʣ����ܤ��᡼�륢�ɥ쥹�������ܰʹߤ������ǡ�����CSV�����ե������</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�����ǡ���</td>
		<td class="n1">
			<input class="alpha"  type="text" name="plug_vars" size="80"><br>
			<span class="note">���ѿ�̾�򥫥�޶��ڤ�����Ϥ��Ƥ���������</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���������ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="from_addr" size=80 maxlength=100>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">������̾��</td>
		<td class="n1">
			<input class="kanji" type="text" name="from_name" size=80 maxlength=100>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ֿ��襢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="reply_to" size=80 maxlength=100>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̾<?=MUST_ITEM?></td>
		<td class="n1">
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
		<td class="m1">��������</td>
		<td class="n1"><input type="checkbox" name="send_date_check" value="1" onclick="show_hide('send_date',checked)">���ꤹ��
			<span class="note">�ʻ��ꤷ�ʤ�����¨����</span>
			<span id="send_date">&nbsp;
				<select name="send_date_y"><? select_year($today['year'], '', $today['year'])?></select>ǯ
				<select name="send_date_m"><? select_month('', $today['mon'])?></select>��
				<select name="send_date_d"><? select_day('', $today['mday'])?></select>��
				<select name="send_date_h"><? select_hour()?></select>��
				<select name="send_date_i"><? select_minute()?></select>ʬ
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">������ڤ�����</td>
		<td class="n1"><input type="checkbox" name="limit_date_check" value="1" onclick="show_hide('limit_date',checked)">���ꤹ��
			<span class="note">�ʻ��ꤷ�ʤ�����̵���¡�</span>
			<span id="limit_date">&nbsp;
				<select name="limit_date_y"><? select_year($today['year'], '', $today['year'])?></select>ǯ
				<select name="limit_date_m"><? select_month('', $today['mon'])?></select>��
				<select name="limit_date_d"><? select_day('', $today['mday'])?></select>��
			</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="�ƥ�������" onclick="document.form1.next_action.value='test'">
<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<? page_footer() ?>
</body>
</html>
