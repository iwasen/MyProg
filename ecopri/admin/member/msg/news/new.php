<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:News!!��Ͽ
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

set_global('member', '����ȥåץڡ�������', 'News!!', BACK_TOP);

if (!$next_action) {
	$sql = "SELECT MAX(ns_end_date) AS max_date from t_news";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0)
		system_error('��å������ɣĤ�����', __FILE__);
	$fetch = pg_fetch_object($result, 0);
	$max_date = $fetch->max_date;
	if ($max_date) {
		$max_date = mktime (0,0,0,get_datepart('M',$max_date),(get_datepart('D',$max_date) + 1),get_datepart('Y',$max_date));
		$start_date = strftime ("%Y-%m-%d", $max_date);
		$end_date = $start_date;
	} else
		$start_date = date("Y-m-d");
		$end_date = $start_date;
} else {
	$start_date = "$start_y-$start_m-$start_d";
	$end_date = "$end_y-$end_m-$end_d";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function disp_msg() {
	var f = document.form1;
	f.action = "new.php";
	f.submit();
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

function onSubmit_form1() {
	var f = document.form1;
	if (!check_date(f.start_y.value, f.start_m.value, f.start_d.value)) {
		alert("�������λ��꤬����������ޤ���");
		f.start_d.focus();
		return(false);
	}
	if (!check_date(f.end_y.value, f.end_m.value, f.end_d.value)) {
		alert("��λ���λ��꤬����������ޤ���");
		f.end_d.focus();
		return(false);
	}
	if (f.title.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.title.focus();
		return false;
	}
	if (f.msg.value == "") {
		alert("��å����������Ϥ��Ƥ���������");
		f.msg.focus();
		return false;
	}
	if (f.msg.value.indexOf('"') != -1) {
		alert("���֥륯�����ơ������ϻȤ��ޤ���");
		f.msg.focus();
		return false;
	}
	if (confirm("News!!����Ͽ���ޤ���������Ǥ�����")) {
		f.action = "update.php";
		f.submit();
	}
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>��News!!��������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">ɽ������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="start_y"><? select_year(2002, '', get_datepart('Y', $start_date)) ?></select>��
			<select name="start_m"><? select_month('', get_datepart('M', $start_date)) ?></select>��
			<select name="start_d"><? select_day('', get_datepart('D', $start_date)) ?></select>����
			<select name="end_y"><? select_year(2002, '', get_datepart('Y', $end_date)) ?></select>��
			<select name="end_m"><? select_month('', get_datepart('M', $end_date)) ?></select>��
			<select name="end_d"><? select_day(' ', $end_d) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">�����ȥ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="text" class="kanji" name="title" size=60 maxlength=50 <?=value($title)?>>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=2>��å�����<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="msg" rows=9 cols=60><?=$msg?></textarea>
			<input type="button" onclick="disp_msg()" value="���Ҏ�����ɽ��">
		</td>
	</tr>
	<tr>
		<td class="n1">
			<table border=1 cellspacing=1 cellpadding=1 width=200 height=105>
				<tr>
					<span class="note">��ɽ�����᡼����</span>
					<td class="n3" valign="top" bgcolor="lightyellow"><?=$msg?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="button" value="����Ͽ��" onclick="onSubmit_form1()">
<!--
<input type="reset" value="����󥻥�">
-->
<input type="button" value=" ��� " onclick="location.href='list.php'">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>
</body>
</html>
