<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:ȯ�����������������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/auth.php");
include("$inc/select.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// ���¥����å�
if (!check_staff_auth('ȯ�����������ݡ���'))
	redirect("$top/room/top.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>������</title>
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
function is_number(value) {
	return (value.match(/[0-9]+/g) == value);
}
function onsubmit_form1(f) {
	switch (f.download.value) {
	case "1":
		return check1(f);
	case "2":
		return check2(f);
	case "3":
		return check3(f);
	}
	return false;
}
function check1(f) {
	if (f.from_y.value != "") {
		if (f.from_m.value == "") {
			alert("������֤η�����Ϥ��Ƥ���������");
			f.from_m.focus();
			return false;
		}
		if (f.from_d.value == "") {
			alert("������֤��������Ϥ��Ƥ���������");
			f.from_d.focus();
			return false;
		}
		if (!check_date(f.from_y.value, f.from_m.value, f.from_d.value)) {
			alert("������֤����դλ��꤬����������ޤ���");
			f.from_d.focus();
			return false;
		}
	}
	if (f.to_y.value != "") {
		if (f.to_m.value == "") {
			alert("������֤η�����Ϥ��Ƥ���������");
			f.to_m.focus();
			return false;
		}
		if (f.to_d.value == "") {
			alert("������֤��������Ϥ��Ƥ���������");
			f.to_d.focus();
			return false;
		}
		if (!check_date(f.to_y.value, f.to_m.value, f.to_d.value)) {
			alert("������֤����դλ��꤬����������ޤ���");
			f.to_d.focus();
			return false;
		}
	}
	if (f.from_y.value != "" && f.to_y.value != "") {
		var from_date = new Date(f.from_y.value, f.from_m.value - 1, f.from_d.value);
		var to_date = new Date(f.to_y.value, f.to_m.value - 1, f.to_d.value);
		if (from_date > to_date) {
			alert("������֤ν�λ���դϳ������դ�������դ����Ϥ��Ƥ���������");
			f.to_d.focus();
			return false;
		}
	}
	return true;
}
function check2(f) {
	if (f.from_no.value != "") {
		if (!is_number(f.from_no.value)) {
			alert("ȯ���ֹ�Ͽ��������Ϥ��Ƥ���������");
			f.from_no.focus();
			return false;
		}
	}
	if (f.to_no.value != "") {
		if (!is_number(f.to_no.value)) {
			alert("ȯ���ֹ�Ͽ��������Ϥ��Ƥ���������");
			f.to_no.focus();
			return false;
		}
	}
	if (f.from_no.value != "" && f.to_no.value != "") {
		if (Number(f.from_no.value) > Number(f.to_no.value)) {
			alert("��λȯ���ֹ�ϳ���ȯ���ֹ����礭���ֹ�����Ϥ��Ƥ���������");
			f.to_no.focus();
			return false;
		}
	}
	return true;
}
function check3(f) {
	if (f.no.value.match(/[0-9\-,]+/g) != f.no.value) {
		alert("ȯ���ֹ�λ���˿������ϥ��ե󡢥���ްʳ���ʸ�������ϤǤ��ޤ���");
		f.no.focus();
		return false;
	}
	return true;
}
function onchange_from_y(y) {
	var f = document.form1;
	if (f.from_y.selectedIndex == 0) {
		f.from_m.selectedIndex = 0;
		f.from_d.selectedIndex = 0;
	}
}
function onchange_to_y(y) {
	var f = document.form1;
	if (f.to_y.selectedIndex == 0) {
		f.to_m.selectedIndex = 0;
		f.to_d.selectedIndex = 0;
	}
}
//-->
</script>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#49c4c7" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="2" bgcolor="#b8e8e9" align="center" width="100%">
		<tr>
			<td class="text12b">���ޤǤ�ȯ���Υ�����������</td>
		</tr>
		</table></td>
</tr>
</table>

<form method="post" name="form1" action="download.php" onsubmit="return onsubmit_form1(this)">
<table border="0" cellspacing="1" cellpadding="0" bgcolor="#49c4c7" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="2" bgcolor="#ffffff" align="center" width="100%" class="text12">
		<tr>
			<td valign="middle">

			<table border="0" cellspacing="0" cellpadding="0" width="550" class="text12">
			<tr>
				<td>

				<table border="0" cellspacing="0" cellpadding="2" align="center" width="100%" class="text12">
				<tr>
					<td colspan="3" class="text14b">�ܺ٤���ꤷ��ȯ�����������ɤǤ��ޤ���</td>
				</tr>
				<tr>
					<td colspan="3"><hr size="1" width="100%" align="center" color="#c0c0c0" noshade></td>
				</tr>
				<tr>
					<td colspan="3" class="text14">���֤���ꤷ��ȯ�����������ɤ��롣</td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td><select name="from_y" class="textbox10" onchange="onchange_from_y()"><? select_year(2004, '-', '') ?></select>ǯ
						<select name="from_m" class="textbox10"><? select_month('-', '') ?></select>��
						<select name="from_d" class="textbox10"><? select_day('-', '') ?></select>������
						<select name="to_y" class="textbox10" onchange="onchange_to_y()"><? select_year(2004, '-', '') ?></select>ǯ
						<select name="to_m" class="textbox10"><? select_month('-', '') ?></select>��
						<select name="to_d" class="textbox10"><? select_day('-', '') ?></select>���ޤ�
					</td>
					<td width="50">
						<input type="submit" value="���������" class="button" onclick="document.form1.download.value='1'">
					</td>
				</tr>
				<tr>
					<td colspan="3"><hr size="1" width="100%" align="center" color="#c0c0c0" noshade></td>
				</tr>
				<tr>
					<td colspan="3" class="text14">ȯ���ֹ��<strong>Ϣ�֤�</strong>���ꤷ��ȯ�����������ɤ��롣<br>
													��ȯ���ֹ�Ȥ�ȯ���Υ��֥������Ȥκǽ�ˤĤ��Ƥ���[0000]�Τ褦���ֹ�Τ��ȤǤ���</td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td colspan="2">1��5�����Ϥ�����硢1��2��3��4��5��ȯ�����Ƥ���������ɤ���ޤ���<br>
									1��3��5�Τߤ��������ɤ�����ϡ�
									���ֲ��Ρ�ȯ���ֹ���̡��˻��ꤷ��ȯ�����������ɤ���פ�����ꤷ�Ƥ���������</td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td><input type="text" name="from_no" value="" size="10" class="button">
						��
						<input type="text" name="to_no" value="" size="10" class="button">
						<span class="red">Ⱦ�ѿ�����</span>���Ϥ��Ƥ���������</td>
					<td width="50">
						<input type="submit" value="���������" class="button" onclick="document.form1.download.value='2'">
					</td>

				</tr>
				<tr>
					<td colspan="3"><hr size="1" width="100%" align="center" color="#c0c0c0" noshade></td>
				</tr>
				<tr>
					<td colspan="3" class="text14">ȯ���ֹ��<strong>�̡���</strong>���ꤷ��ȯ�����������ɤ��롣<br>
													��ȯ���ֹ�Ȥ�ȯ���Υ��֥������Ȥκǽ�ˤĤ��Ƥ���[0000]�Τ褦���ֹ�Τ��ȤǤ���</td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td colspan="2">��1,3,5�ۤΤ褦�ˡ�Ⱦ�ѥ���ޡפǶ��ڤä����Ϥ��Ƥ���������<br>
									��1,3,5-10�ۤȥϥ��ե�ǤĤʤ��뤳�Ȥˤ�ꡢ1��3��5��6��7��8��9��10��ȯ�����������ɤǤ��ޤ���</td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td><textarea name="no" cols="70" rows="3" class="textbox"></textarea></td>
					<td width="50">
						<input type="submit" value="���������" class="button" onclick="document.form1.download.value='3'">
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>

			</td>
		</tr>
		</table></td>
</tr>
</table>
<input type="hidden" name="download">
<input type="hidden" name="disp_type" value="1">
<input type="hidden" name="file_name" value="log">
</form>

</body>

</html>
