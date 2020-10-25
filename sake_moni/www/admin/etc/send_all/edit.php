<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:���ۿ�
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', '����¾����', '���ۿ�', BACK_TOP);

$sql = "SELECT sa_send_id,sa_subject,sa_body,sa_send_date,sa_status,sa_net_kind" .
			" FROM t_send_all WHERE sa_send_id=$send_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	redirect('list.php');
$fetch = pg_fetch_object($result, 0);

$target = $fetch->sa_target;
if ($fetch->sa_send_date) {
	$send_date_check = '1';
	$send_date = sql_time($fetch->sa_send_date);
} else
	$send_date = time() + (60 * 60 * 24);
$send_date_y = date(Y, $send_date);
$send_date_m = date(m, $send_date);
$send_date_d = date(d, $send_date);
$send_date_h = date(H, $send_date);
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
	show_hide("send_date", f.send_date_check.checked);
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return input_check(f, "���ۿ��᡼��򹹿����ޤ���������Ǥ�����");
	case "delete":
		return confirm("���ۿ��᡼��������ޤ���������Ǥ�����");
	case "test":
		return confirm("���ۿ��᡼���ƥ����������ޤ���������Ǥ�����");
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
		<td class="m1" width="20%">�������ơ�net��iMi�ͥå�<?=MUST_ITEM?></td>
		<td class="n1" width="80%"><select name="net_kind"><? select_net_kind('����', $fetch->sa_net_kind) ?></select></td>
	</tr>
	<tr>
		<td class="m1" width="20%">��̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=80 maxlength=100 <?=value($fetch->sa_subject)?>>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ʸ<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->sa_body)?></textarea><br>
			<span class="note">��ʸ�������¤Ϥ���ޤ����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1"><input type="checkbox" name="send_date_check" <?=value_checked('1', $send_date_check)?> onclick="show_hide('send_date',checked)">���ꤹ��
			<span class="note">�ʻ��ꤷ�ʤ�����¨����</span>
			<span id="send_date">&nbsp;
				<select name="send_date_y"><?select_year(2001, '', $send_date_y)?></select>ǯ
				<select name="send_date_m"><?select_month('', $send_date_m)?></select>��
				<select name="send_date_d"><?select_day('', $send_date_d)?></select>��
				<select name="send_date_h"><?select_hour('', $send_date_h)?></select>��
			</span>
		</td>
	</tr>
</table>
<br>
<?
if ($fetch->sa_status == 0) {
?>
<input type="submit" value="�ƥ�������" onclick="document.form1.next_action.value='test'">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<?
}
?>
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="send_id" value="<?=$send_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
