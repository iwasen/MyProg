#!/usr/local/bin/php4
<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");
include("$inc/select.php");

// ��n��˾����
function select_choice($chice_num, $selected) {
	for ($i = 0; $i < $chice_num; $i++)
		option_tag($i, $selected, mb_convert_kana($i + 1, 'N'));
}

// �����󥻥顼����������
function select_sex($selected) {
	option_tag('3', $selected, '�ä˴�˾���ʤ�');
	option_tag('1', $selected, '����');
	option_tag('2', $selected, '����');
}

// ���å���󤫤�ͽ��������
session_start();
if ($reserve == null)
	redirect('/index.html');

for ($chice_num = 0; $chice_num < MAX_CHOICE; $chice_num++) {
	if ($reserve->choice[$chice_num]->room_id == 0)
		break;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function appl() {
	var f = document.form1;
	if (f["choice[]"].length) {
		for (i = 0; i < f["choice[]"].length; i++) {
			for (j = i + 1; j < f["choice[]"].length; j++) {
				if (f["choice[]"][i].selectedIndex == f["choice[]"][j].selectedIndex) {
					alert("����˾��̤����������򤵤�Ƥ��ʤ��褦�Ǥ���");
					f["choice[]"][j].focus();
					return;
				}
			}
		}
	}
	if (f.name1.value == "") {
		alert("��̾�������ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.name1.focus();
		return;
	}
	if (f.name2.value == "") {
		alert("��̾����̾�ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.name2.focus();
		return;
	}
	if (f.kana1.value == "") {
		alert("��̾���ʥ����ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.kana1.focus();
		return;
	}
	if (f.kana2.value == "") {
		alert("��̾���ʥᥤ�ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.kana2.focus();
		return;
	}
	if (f.mail_addr.value == "") {
		alert("�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.mail_addr.focus();
		return;
	}
	if (f.mail_addr2.value == "") {
		alert("�᡼�륢�ɥ쥹�ʳ�ǧ�ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.mail_addr2.focus();
		return;
	}
	if (f.mail_addr.value != f.mail_addr2.value) {
		alert("���Ϥ��줿�᡼�륢�ɥ쥹�����פ��Ƥ��ʤ��褦�Ǥ���");
		f.mail_addr.focus();
		return;
	}
	if (!f.mail_addr.value.match(/^[a-zA-Z0-9._\/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$/)) {
		alert("�᡼�륢�ɥ쥹���������ʤ��褦�Ǥ���");
		f.mail_addr.focus();
		return;
	}
	if (!f.contact[0].checked && !f.contact[1].checked && !f.contact[2].checked) {
		alert("��˾Ϣ���褬���򤵤�Ƥ��ʤ��褦�Ǥ���");
		f.contact[0].focus();
		return;
	}
	if (f.contact[1].checked && f.tel_fax.value == "") {
		alert("TEL�ֹ椬���Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.tel_fax.focus();
		return;
	}
	if (f.contact[2].checked && f.tel_fax.value == "") {
		alert("FAX�ֹ椬���Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.tel_fax.focus();
		return;
	}
	if (f["counseling_id[]"]) {
		sel = false;
		if (f["counseling_id[]"].length) {
			for (i = 0; i < f["counseling_id[]"].length; i++) {
				if (f["counseling_id[]"][i].checked) {
					sel = true;
					break;
				}
			}
		} else {
			if (f["counseling_id[]"].checked)
				sel = true;
		}
		if (!sel) {
			alert("�������Ƥ򣱤İʾ����򤷤Ƥ���������");
			return;
		}
	}
	f.next.value = "confirm.php";
	f.submit();
}
//-->
</script>
</head>
<body>
ͽ��ɬ�׹�������
<br>
<form method="post" name="form1" action="reserve2.php">
<input type="hidden" name="next">
<table border=1 cellspacing=1>
	<tr>
		<td>ͽ�󥳡���</td>
		<td colspan=3>��<?=$reserve->course?> <?=$reserve->shop?></td>
	</tr>
<?
for ($i = 0; $i < $chice_num; $i++) {
	$choice = &$reserve->choice[$i];
	$week = date('w', strtotime($choice->date));

	$sql = sprintf("SELECT sum(wk_sex_cd) as sex_cd"
			. " FROM t_waku"
			. " WHERE wk_room_id=%s AND wk_course_id=%s AND wk_date=%s AND wk_start_time=%s AND wk_end_time=%s",
			sql_number($choice->room_id),
			sql_number($reserve->course_id),
			sql_date($choice->date),
			sql_date($choice->start_time),
			sql_date($choice->end_time));
	$sex_cd = db_fetch1($sql);

?>
	<tr>
		<td>��<select name="choice[]"><? select_choice($chice_num, $i) ?></select>��˾</td>
		<td><?=$choice->date?>��<?=decode_week($week)?>�� <?=$choice->start_time?>��<?=$choice->end_time?></td>
		<td><?=$choice->room?></td>
		<td>�����󥻥顼�����̡�
<?
	if ($sex_cd == 1) {
?>
			����<input type="hidden" name="sex[]" value="1">
<?
	} elseif ($sex_cd == 2) {
?>
			����<input type="hidden" name="sex[]" value="2">
<?
	} else {
?>
			<select name="sex[]"><? select_sex($choice->sex) ?></select>
<?
	}
?>
		</td>
	</tr>
<?
}
?>
</table>
<hr>
<table border=1 cellspacing=1>
	<tr>
		<td>��̾��</td>
		<td>
			��<input type="text" name="name1" <?=value($reserve->name_1)?>>
			̾<input type="text" name="name2" <?=value($reserve->name_2)?>>
			<br>
			����<input type="text" name="kana1" <?=value($reserve->kana_1)?>>
			�ᥤ<input type="text" name="kana2" <?=value($reserve->kana_2)?>>
		</td>
	</tr>
	<tr>
		<td>�᡼�륢�ɥ쥹</td>
		<td>
			<input type="text" name="mail_addr" <?=value($reserve->mail_addr)?>>
			<br>
			<input type="text" name="mail_addr2" <?=value($reserve->mail_addr)?>>
		</td>
	</tr>
	<tr>
		<td>��˾Ϣ����</td>
		<td>
			<input type="radio" name="contact" <?=value_checked(1, $reserve->contact)?>>�᡼��
			<input type="radio" name="contact" <?=value_checked(2, $reserve->contact)?>>TEL
			<input type="radio" name="contact" <?=value_checked(3, $reserve->contact)?>>FAX
			<br>
			TEL�ޤ���FAX�򤴻���ξ���TEL/FAX�ֹ�����Ϥ��Ƥ���������
			<input type="text" name="tel_fax" <?=value($reserve->tel . $reserve->fax)?>>
		</td>
	</tr>
	<tr>
		<td>��������</td>
		<td>
			<table border=0 cellspacing=1>
<?
$sql = "SELECT cs_counseling_id,cs_content"
		. " FROM m_counseling"
		. " WHERE cs_course_id=" . sql_number($reserve->course_id);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	if ($i % 2 == 0) {
?>
				<tr>
<?
	}
?>
					<td><input type="checkbox" name="counseling_id[]" <?=value($fetch->cs_counseling_id)?> <?=checked(in_array($fetch->cs_counseling_id, $reserve->counseling_id))?>><?=htmlspecialchars($fetch->cs_content)?></td>
<?
	if ($i % 2 == 1) {
?>
				</tr>
<?
	}
}
if ($i % 2 == 1) {
?>
					<td></td>
				</tr>
<?
}
?>
			</table>
			<hr>
			<input type="radio" name="change" <?=value_checked(1, $reserve->change)?>>�����Ʊ�������󥻥顼���˾����
			<input type="radio" name="change" <?=value_checked(2, $reserve->change)?>>�̤Υ����󥻥顼���˾����
		</td>
	</tr>
	<tr>
		<td>����¾������˾�ʤ�</td>
		<td><textarea name="comment" cols=60 rows=5><?=htmlspecialchars($reserve->comment)?></textarea></td>
</table>
<br>
<input type="button" value="ͽ�󿽹���" onclick="appl()">
<input type="button" value="����롡" onclick="location.href='choice.php'">
</form>
</body>
</html>
