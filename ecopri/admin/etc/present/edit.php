<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:�ץ쥼��ȴ�����������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// �ץ쥼��ȼ»ܾ��������
function select_present_status($sel) {
	echo "<option ", value_selected('0', $sel), ">������</option>";
	echo "<option ", value_selected('1', $sel), ">�»���</option>";
	echo "<option ", value_selected('2', $sel), ">��λ</option>";
	echo "<option ", value_selected('3', $sel), ">���</option>";
	echo "<option ", value_selected('9', $sel), ">̵��</option>";
}


//�ᥤ�����
set_global('etc', '����¾����', '�ץ쥼��ȴ���', BACK_TOP);

$sql = "select * from t_present where ps_present_no=$ps_no";
$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
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

function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		f.action = "update.php";
		f.target = "_self";
		return input_check(f, "�ץ쥼��Ⱦ���򹹿����ޤ�����������Ǥ�����");
	case "delete":
		f.action = "update.php";
		f.target = "_self";
		return confirm("�ץ쥼��Ⱦ���������ޤ�����������Ǥ�����");
	case "preview":
		f.action = "preview.php";
		f.target = "_blank";
		return true;
	}
	return false;
}
function input_check(f, msg) {
	if (f.name.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.name.focus();
		return false;
	}
	if (f.prize.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.prize.focus();
		return false;
	}
	if (f.num.value == "") {
		alert("�����Ϳ������Ϥ��Ƥ���������");
		f.num.focus();
		return false;
	}
	if (!check_date(f.start_date_y.value, f.start_date_m.value, f.start_date_d.value)) {
		alert("�������λ��꤬����������ޤ���");
		f.start_date_d.focus();
		return(false);
	}
	if (!check_date(f.end_date_y.value, f.end_date_m.value, f.end_date_d.value)) {
		alert("��λ���λ��꤬����������ޤ���");
		f.end_date_d.focus();
		return(false);
	}
	var start_date = new Date(f.start_date_y.value, f.start_date_m.value - 1, f.start_date_d.value, f.start_date_h.value);
	var end_date = new Date(f.end_date_y.value, f.end_date_m.value - 1, f.end_date_d.value, f.end_date_h.value);
	if (start_date > end_date) {
		alert("��λ�����ϳ���������������դ����Ϥ��Ƥ���������");
		f.end_date_y.focus();
		return false;
	}
	if (!check_date(f.lottery_date_y.value, f.lottery_date_m.value, f.lottery_date_d.value)) {
		alert("�������λ��꤬����������ޤ���");
		f.lottery_date_d.focus();
		return(false);
	}
	if (f.contact_addr1.value == "") {
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.contact_addr1.focus();
		return false;
	}
	return confirm("�ץ쥼��Ⱦ���򹹿����ޤ�����������Ǥ�����");
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
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%" id="tbl">
	<tr>
		<td class="m0" colspan=2>���ץ쥼��Ⱦ�������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�����ȥ�<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="kanji" type="text" name="name" size=80 maxlength=100 <?=value($fetch->ps_name)?>>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�ץ쥼��Ⱦ���<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="kanji" type="text" name="prize" size=80 maxlength=100 <?=value($fetch->ps_prize)?>>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<textarea name="explain" cols=78 rows=5><?=htmlspecialchars($fetch->ps_explain)?></textarea><br>
			<span class="note">��ʸ�������¤Ϥ���ޤ����</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input type="radio" name="type" disabled>����
			<input type="radio" name="type" value=0 checked>�ʤ�
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">������<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="number" type="text" name="num" size=6 maxlength=6 <?=value($fetch->ps_num)?>>̾��
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="start_date_y"><? select_year(2002, '', get_datepart('Y',$fetch->ps_start_date)) ?></select>ǯ
			<select name="start_date_m"><? select_month('', get_datepart('M',$fetch->ps_start_date)) ?></select>��
			<select name="start_date_d"><? select_day('', get_datepart('D',$fetch->ps_start_date)) ?></select>��
			<select name="start_date_h"><? select_hour('', get_datepart('h',$fetch->ps_start_date)) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">��λ����<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="end_date_y"><? select_year(2002, '', get_datepart('Y',$fetch->ps_end_date)) ?></select>ǯ
			<select name="end_date_m"><? select_month('', get_datepart('M',$fetch->ps_end_date)) ?></select>��
			<select name="end_date_d"><? select_day('', get_datepart('D',$fetch->ps_end_date)) ?></select>��
			<select name="end_date_h"><? select_hour('', get_datepart('h',$fetch->ps_end_date)) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="lottery_date_y"><? select_year(2002, '', get_datepart('Y',$fetch->ps_lottery_date)) ?></select>ǯ
			<select name="lottery_date_m"><? select_month('', get_datepart('M',$fetch->ps_lottery_date)) ?></select>��
			<select name="lottery_date_d"><? select_day('', get_datepart('D',$fetch->ps_lottery_date)) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">�»ܾ���<?=MUST_ITEM?></td>
		<td class="n1"><select name="status"><? select_present_status($fetch->ps_status) ?></select></td>
	</tr>
	<tr>
		<td class="m1">Ϣ����᡼�륢�ɥ쥹��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="contact_addr1" size=50 maxlength=100 <?=value($fetch->ps_contact_addr1)?>>
			<span class="note">��Ⱦ��100ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Ϣ����᡼�륢�ɥ쥹��</td>
		<td class="n1">
			<input class="alpha" type="text" name="contact_addr2" size=50 maxlength=100 <?=value($fetch->ps_contact_addr2)?>>
			<span class="note">��Ⱦ��100ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Ϣ����᡼�륢�ɥ쥹��</td>
		<td class="n1">
			<input class="alpha" type="text" name="contact_addr3" size=50 maxlength=100 <?=value($fetch->ps_contact_addr3)?>>
			<span class="note">��Ⱦ��100ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="ps_no" <?=value($ps_no)?>>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="seq_no" value="<?=$seq_no?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>