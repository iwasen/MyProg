<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�ޡ����󥰥ޥ���������Ͽ����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>icp.net ���ڥ졼�����ڡ���</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.marking_id.value == "") {
		alert("ID�����Ϥ��Ƥ���������");
		f.marking_id.focus();
		return false;
	}
	if (f.name.value == "") {
		alert("�ե饰̾�Τ����Ϥ��Ƥ���������");
		f.name.focus();
		return false;
	}
	if (f.color.value == "") {
		alert("ɽ���������Ϥ��Ƥ���������");
		f.color.focus();
		return false;
	}

	return confirm("�ޡ����󥰥ե饰�򿷵���Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- ������ƥ�� -->
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td align="center">

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
	<tr>
		<td colspan=2>���ޡ����󥰥ե饰��������Ϥ��Ƥ���������</td>
	</tr>
</table>
<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
	<tr>
		<th>ID</th>
		<td><input type="text" name="marking_id" size=10></td>
	</tr>
	<tr>
		<th>�ե饰̾��</th>
		<td><input type="text" name="name" size=50></td>
	</tr>
	<tr>
		<th>ɽ����</th>
		<td><input type="text" name="color" size=20></td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="proc" value="regist">
</form>

			</td>
		</tr>
		</table>

	</td>
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>
</html>
