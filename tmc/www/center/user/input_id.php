<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�桼������ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");

// �ѥ�᡼���ǥ桼��ID���Ϥ��줿������쥯�Ȥ�ɽ��
if ($_GET['user_id'] <> '')
	redirect("info.php?user_id={$_GET['user_id']}");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.user_id.value == "") {
		alert("�桼���ɣĤޤ��ϥ᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.user_id.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body onload="document.form1.user_id.focus()">

<? center_header('�桼������ɽ��') ?>

<div align="center">
<form method="get" name="form1" action="info.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���桼���ɣĤޤ��ϥ᡼�륢�ɥ쥹�����Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="30%">�桼���ɣĤޤ��ϥ᡼�륢�ɥ쥹</td>
		<td class="n1"><input class="alpha" type="text" name="user_id" size=40></td>
	</tr>
</table>
<br>
<input type="submit" value="���ϣˡ�">
<input type="button" value="�Ĥ���" onclick="window.close()">
</form>
</div>

<? center_footer() ?>

</body>
</html>
