<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:����ƥ��ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/select.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<title>�ܺٸ�����ʬ</title>
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
function onsubmit_search(f) {
	if (f.search_from_y.value != "") {
		if (f.search_from_m.value == "") {
			alert("�������֤η�����Ϥ��Ƥ���������");
			f.search_from_m.focus();
			return false;
		}
		if (f.search_from_d.value == "") {
			alert("�������֤��������Ϥ��Ƥ���������");
			f.search_from_d.focus();
			return false;
		}
		if (!check_date(f.search_from_y.value, f.search_from_m.value, f.search_from_d.value)) {
			alert("�������֤����դλ��꤬����������ޤ���");
			f.search_from_d.focus();
			return false;
		}
	}
	if (f.search_to_y.value != "") {
		if (f.search_to_m.value == "") {
			alert("�������֤η�����Ϥ��Ƥ���������");
			f.search_to_m.focus();
			return false;
		}
		if (f.search_to_d.value == "") {
			alert("�������֤��������Ϥ��Ƥ���������");
			f.search_to_d.focus();
			return false;
		}
		if (!check_date(f.search_to_y.value, f.search_to_m.value, f.search_to_d.value)) {
			alert("�������֤����դλ��꤬����������ޤ���");
			f.search_to_d.focus();
			return false;
		}
	}
	if (!f.search_subject.checked && !f.search_content.checked && !f.search_member.checked) {
		alert("���֥������ȡ���ʸ����ƼԤ��飱�İʾ����򤷤Ƥ���������");
		f.search_subject.focus();
		return false;
	}
	return true;
}
function onchange_from_y(y) {
	var f = document.form1;
	if (f.search_from_y.selectedIndex == 0) {
		f.search_from_m.selectedIndex = 0;
		f.search_from_d.selectedIndex = 0;
	}
}
function onchange_to_y(y) {
	var f = document.form1;
	if (f.search_to_y.selectedIndex == 0) {
		f.search_to_m.selectedIndex = 0;
		f.search_to_d.selectedIndex = 0;
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
			<td class="text12b">�ܺٸ���</td>
		</tr>
		</table></td>
</tr>
</table>

<form method="get" name="form1" action="subject_search.php" target="subject" onsubmit="return onsubmit_search(this)">
<table border="0" cellspacing="1" cellpadding="0" bgcolor="#49c4c7" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="2" bgcolor="#ffffff" align="center" width="100%" class="text12">
		<tr>
			<td valign="middle">

			<table border="0" cellspacing="0" cellpadding="0" width="400" class="text12">
			<tr>
				<td>

				<table border="0" cellspacing="0" cellpadding="2" align="center" width="100%" class="text12">
				<tr>
					<td colspan="2" class="text14b">�ܺ٤����ꤷ�Ƹ����Ǥ��ޤ���</td>
				</tr>
				<tr>
					<td colspan="2"><hr size="1" width="100%" align="center" color="#c0c0c0" noshade></td>
				</tr>
				<tr>
					<td colspan="2">�������֤���ꤷ�Ƥ������������ꤷ�ʤ��Ƥ�빽�Ǥ���</td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td><select name="search_from_y" class="textbox10" onchange="onchange_from_y()"><? select_year(2004, '-', '') ?></select>ǯ
						<select name="search_from_m" class="textbox10"><? select_month('-', '') ?></select>��
						<select name="search_from_d" class="textbox10"><? select_day('-', '') ?></select>���ʹ�
					</td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td><select name="search_to_y" class="textbox10" onchange="onchange_to_y()"><? select_year(2004, '-', '') ?></select>ǯ
						<select name="search_to_m" class="textbox10"><? select_month('-', '') ?></select>��
						<select name="search_to_d" class="textbox10"><? select_day('-', '') ?></select>������
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr size="1" width="100%" align="center" color="#c0c0c0" noshade></td>
				</tr>
				<tr>
					<td colspan="2">�����ϰϤ���ꤷ�Ƥ���������</td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td><input type="checkbox" name="search_subject" value="1" id="sub"><label for="sub">���֥�������</label></td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td><input checked type="checkbox" name="search_content" value="2" id="honbun"><label for="honbun">��ʸ</label></td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td><input type="checkbox" name="search_member" value="3" id="member"><label for="member">��Ƽ�</label></td>
				</tr>
				<tr>
					<td colspan="2"><hr size="1" width="100%" align="center" color="#c0c0c0" noshade></td>
				</tr>
				<tr>
					<td colspan="2">����������ɤ����Ϥ��Ƥ���������</td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td><input type="text" name="search_keyword" value="" size="50" class="textbox"></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" value="����"></td>
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
</form>

</body>

</html>
