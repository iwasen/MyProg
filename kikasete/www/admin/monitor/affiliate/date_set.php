<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ե��ꥨ���ȿ��������������
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

// �ᥤ�����
set_global('monitor', '��˥�������', '���ե��ꥨ���ȴ���', BACK_TOP);

$sql = "SELECT afd_start_date,afd_end_date FROM t_affiliate_date";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

$start_y = get_datepart('Y', $fetch->afd_start_date);
$start_m = get_datepart('M', $fetch->afd_start_date);
$start_d = get_datepart('D', $fetch->afd_start_date);

$end_y = get_datepart('Y', $fetch->afd_end_date);
$end_m = get_datepart('M', $fetch->afd_end_date);
$end_d = get_datepart('D', $fetch->afd_end_date);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (!check_date(f.start_y.value, f.start_m.value, f.start_d.value)) {
		alert("�������դ�����������ޤ���");
		f.start_y.focus();
		return false;
	}
	if (!check_date(f.end_y.value, f.end_m.value, f.end_d.value)) {
		alert("��λ���դ�����������ޤ���");
		f.end_y.focus();
		return false;
	}
	var start_date = new Date(f.start_y.value, f.start_m.value - 1, f.start_d.value);
	var end_date = new Date(f.end_y.value, f.end_m.value - 1, f.end_d.value);
	if (start_date > end_date) {
		alert("��λ���դ��������դ����ˤʤäƤ��ޤ���");
		f.end_y.focus();
		return false;
	}
	return confirm('���ե��ꥨ���ȿ������մ��֤����ꤷ�ޤ���������Ǥ�����');
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
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="date_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>�����ե��ꥨ���ȿ������մ��֤����ꤷ�Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="22%">����</td>
		<td class="n1">
			<input class="number" type="text" name="start_y" size=4 maxlength=4 <?=value($start_y)?>>ǯ
			<select name="start_m"><? select_month('', $start_m) ?></select>��
			<select name="start_d"><? select_day('', $start_d) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">��λ</td>
		<td class="n1">
			<input class="number" type="text" name="end_y" size=4 maxlength=4 <?=value($end_y)?>>ǯ
			<select name="end_m"><? select_month('', $end_m) ?></select>��
			<select name="end_d"><? select_day('', $end_d) ?></select>��
		</td>
	</tr>
</table>
<br>
<input type="submit" value="�����ꡡ">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
