<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�ץ��������ѹ��Υ᡼��ƥ�ץ졼����Ͽ
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

//�ᥤ�����

set_global2('project', '�ץ������ȴ����åƥ�ץ졼���Խ�', '���Υ᡼��ƥ�ץ졼����Ͽ', BACK_TOP);
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
	if (f.pm_title.value == "") {
		alert("�ƥ�ץ졼��̾�����Ϥ��Ƥ���������");
		f.pm_title.focus();
		return false;
	}
	if (f.pm_body.value == "") {
		alert("��ʸ�����Ϥ��Ƥ���������");
		f.pm_body.focus();
		return false;
	}
	return confirm("���Υ᡼��ƥ�ץ졼�Ȥ����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="document.form1.pm_title.focus()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center"><?=$g_title?></td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=0 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">���ƥ�ץ졼��̾�����Ϥ��Ʋ�������</td>
	</tr>
	<tr>
		<td class="m1" width=140>�����ȥ�</td>
		<td class="n1"><input type="text" name="pm_title" size=90 maxlength=100>
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=0 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">���ƥ�ץ졼�Ȥ����Ƥ����Ϥ��Ʋ�������</td>
	</tr>
	<tr>
		<td class="m1" width=140>��ʸ</td>
		<td class="n1">
			<textarea name="pm_body" cols=72 rows=20></textarea>
		</td>
	</tr>
</table>
<br>
<table width=700>
	<div align="center">
	<input type="hidden" name="next_action" value='new'>
	<input type="submit" value="����Ͽ��">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value="����롡" onclick="location.href='list.php'">
	</div>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
