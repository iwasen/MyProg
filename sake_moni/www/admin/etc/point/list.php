<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�ݥ���Ⱦ������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global('etc', '����¾����', '�ݥ���Ⱦ������', BACK_TOP);
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
	if (f.from_month.value == "") {
		alert("�ݥ���Ƚ��ϴ��֤����Ϥ��Ƥ���������");
		f.from_month.focus();
		return false;
	}
	if (f.from_day.value == "") {
		alert("�ݥ���Ƚ��ϴ��֤����Ϥ��Ƥ���������");
		f.from_day.focus();
		return false;
	}
	if (f.to_month.value == "") {
		alert("�ݥ���Ƚ��ϴ��֤����Ϥ��Ƥ���������");
		f.to_month.focus();
		return false;
	}
	if (f.to_day.value == "") {
		alert("�ݥ���Ƚ��ϴ��֤����Ϥ��Ƥ���������");
		f.to_day.focus();
		return false;
	}
	return confirm("�ݥ���Ⱦ�����������ɤ��ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="csv.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���ݥ���Ƚ��Ͼ������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�ݥ���Ƚ��ϴ���<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<select name="from_year"><? select_year(2004, '', date('Y')) ?></select>ǯ
			<select name="from_month"><? select_month('��') ?></select>��
			<select name="from_day"><? select_day('��') ?></select>��
			��
			<select name="to_year"><? select_year(date('Y')) ?></select>ǯ
			<select name="to_month"><? select_month('��') ?></select>��
			<select name="to_day"><? select_day('��') ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���Ƚ�������</td>
		<td class="n1">
			<select name="net_id">
				<option value="1">�������ơ�net</option>
				<option value="2">iMi�ͥå�</option>
			</select>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="���������">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
