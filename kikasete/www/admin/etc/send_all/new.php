<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ۿ�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/select.php");

set_global('etc', '����¾����', '���ۿ�', BACK_TOP);

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
	f("target[]")[0].focus();
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		return input_check(f, "���ۿ��᡼�����Ͽ���ޤ���������Ǥ�����");
	case "test":
		return confirm("���ۿ��᡼���ƥ����������ޤ���������Ǥ�����");
	}
	return false;
}
function input_check(f, msg) {
	var ok;
	for (i = 0; i < f("target[]").length; i++) {
		if (f("target[]")[i].checked) {
			ok = true;
			break;
		}
	}
	if (!ok) {
		alert("����������򤷤Ƥ���������");
		f("target[]")[0].focus();
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
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>�����ۿ��᡼������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">������<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input type="checkbox" name="target[]" value="<?=PS_MONITOR?>">��˥���
			<input type="checkbox" name="target[]" value="<?=PS_MARKETING_PARTNER?>">�ͥѡ��ȥʡ�
			<br>
			<input type="checkbox" name="target[]" value="<?=PS_MARKETER_FREE?>">�ޡ���������̵����
			<input type="checkbox" name="target[]" value="<?=PS_MARKETER_PAY?>">�ޡ���������ͭ����
			<input type="checkbox" name="target[]" value="<?=PS_MARKETER_ASSISTANT?>">�ޡ��������ʥ���������ȡ�
			<br>
			<input type="checkbox" name="target[]" value="<?=PS_AGENT?>">�����������
			<input type="checkbox" name="target[]" value="<?=PS_AGENT_SUB?>">����������ȡʥ��֡�
		</td>
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
		<td class="m1">��������</td>
		<td class="n1"><input type="checkbox" name="send_date_check" value="1" onclick="show_hide('send_date',checked)">���ꤹ��
			<span class="note">�ʻ��ꤷ�ʤ�����¨����</span>
			<span id="send_date">&nbsp;
				<select name="send_date_y"><?select_year($today['year'], '', $today['year'])?></select>ǯ
				<select name="send_date_m"><?select_month('', $today['mon'])?></select>��
				<select name="send_date_d"><?select_day('', $today['mday'])?></select>��
				<select name="send_date_h"><?select_hour()?></select>��
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
