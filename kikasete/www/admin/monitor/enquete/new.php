<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�����ͥ��󥱡��ȴ���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/select.php");

// ���󥱡��ȼ»ܾ��������
function select_enq_status($sel) {
	echo "<option ", value_selected('4', $sel), ">������</option>";
	echo "<option ", value_selected('5', $sel), ">�»���</option>";
	echo "<option ", value_selected('6', $sel), ">������</option>";
	echo "<option ", value_selected('7', $sel), ">��λ</option>";
}

set_global('monitor', '��˥�������', '�����ͥ��󥱡��ȴ���', BACK_TOP);

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
function onload_body() {
	add_question();
	document.form1.title.focus();
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		f.action = "update.php";
		f.target = "_self";
		return input_check(f, "�����ͥ��󥱡��Ȥ���Ͽ���ޤ���������Ǥ�����");
	case "preview":
		f.action = "preview.php";
		f.target = "_blank";
		return true;
	}
	return false;
}
function input_check(f, msg) {
	var now = new Date();
	if (f.title.value == "") {
		alert("��������Ϥ��Ƥ���������");
		f.title.focus();
		return false;
	}
	if (f.description.value == "") {
		alert("����ʸ�����Ϥ��Ƥ���������");
		f.description.focus();
		return false;
	}
	var start_date = new Date(f.start_date_y.value, f.start_date_m.value - 1, f.start_date_d.value, f.start_date_h.value);
	if (start_date < now) {
		alert("���������˲������դ����ϤǤ��ޤ���");
		f.start_date_y.focus();
		return false;
	}
	var end_date = new Date(f.end_date_y.value, f.end_date_m.value - 1, f.end_date_d.value, f.end_date_h.value);
	if (start_date > end_date) {
		alert("��λ�����ϳ���������������դ����Ϥ��Ƥ���������");
		f.end_date_y.focus();
		return false;
	}
	if (f.point.value == "") {
		alert("��Ϳ�ݥ���Ȥ����Ϥ��Ƥ���������");
		f.point.focus();
		return false;
	}
	for (var i = 1; i <= f.question_cnt.value; i++) {
		var cnt = f["sel_cnt" + i].value;
		for (var j = 0; j < cnt; j++) {
			if (f["sel_text" + i + "[]"][j].value != '') {
				if (f["sel_text_s" + i + "[]"][j].value == '') {
					alert("û�̷������������Ϥ��Ƥ���������");
					f["sel_text_s" + i + "[]"][j].focus();
					return false;
				}
			}
		}
	}
	return confirm(msg);
}
function show_hide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
function add_question() {
	var html = '\
<table border=0 cellspacing=2 cellpadding=3 width="80%">\
	<tr>\
		<td width="100%" colspan=2 style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">��%Q%</td>\
	</tr>\
	<tr>\
		<td class="m1" width="20%">�������</td>\
		<td class="n1" width="80%">\
			<select name="question_type%Q%">\
				<option value="1">ñ�������(SA)</option>\
				<option value="2">ʣ�������(MA)</option>\
			</select>\
			��<input type="radio" name="top_page" value="%Q%"' + (document.form1.question_cnt.value == 0 ? ' checked' : '') + '>�ȥåץڡ�����ɽ��\
		</td>\
	</tr>\
	<tr>\
		<td class="m1">����ʸ</td>\
		<td class="n1"><textarea name="question_text%Q%" cols=65 rows=5></textarea></td>\
	</tr>\
	<tr>\
		<td class="m1">�����</td>\
		<td class="n1">\
			<span id="sel%Q%"></span>\
			<input type="button" value="������ɲ�" onclick="add_select(%Q%)">\
			<input type="hidden" name="sel_cnt%Q%" value="0">\
		</td>\
	</tr>\
</table>';

document.all.question.innerHTML += html.replace(/%Q%/g, ++document.form1.question_cnt.value);
add_select(document.form1.question_cnt.value);
add_select(document.form1.question_cnt.value);
add_select(document.form1.question_cnt.value);
}
function add_select(question_no) {
	var sel = document.all["sel" + question_no];
	var cnt = document.form1["sel_cnt" + question_no];
	cnt.value++;
	sel.innerHTML += cnt.value + ' <input type="text" name="sel_text' + question_no + '[]" size=60> <input type="text" name="sel_text_s' + question_no + '[]" size=20><br>';
}
//-->
</script>
</head>
<body onload="onload_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%" id="tbl">
	<tr>
		<td class="m0" colspan=2>�������ͥ��󥱡��Ȥ����Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">����<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="kanji" type="text" name="title" size=80 maxlength=100>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����ʸ<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea name="description" cols=78 rows=10></textarea><br>
			<span class="note">��ʸ�������¤Ϥ���ޤ����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="start_date_y"><?select_year(date('Y'))?></select>ǯ
			<select name="start_date_m"><?select_month()?></select>��
			<select name="start_date_d"><?select_day()?></select>��
			<select name="start_date_h"><?select_hour()?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">��λ����<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="end_date_y"><?select_year(date('Y'))?></select>ǯ
			<select name="end_date_m"><?select_month()?></select>��
			<select name="end_date_d"><?select_day()?></select>��
			<select name="end_date_h"><?select_hour()?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">��Ϳ�ݥ����</td>
		<td class="n1"><input class="number" type="text" name="point" size=4 maxlength=4></td>
	</tr>
	<tr>
		<td class="m1">��������襢�ɥ쥹</td>
		<td class="n1">
			<textarea class="alpha" name="report_addr" cols=50 rows=4></textarea><br>
			<span class="note">�ʣ��Ԥˣ����ɥ쥹���ĵ�����</span>
		</td>
	</tr>
</table>
<input type="hidden" name="question_cnt" value="0">
<span id="question"></span>
<br>
<input type="button" value="�����ɲ�" onclick="add_question()">
<input type="submit" value="�ץ�ӥ塼" onclick="document.form1.next_action.value='preview'">
<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="status" value="4">
</form>
</div>

<? page_footer() ?>
</body>
</html>
