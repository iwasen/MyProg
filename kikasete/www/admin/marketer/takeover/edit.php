<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ޡ����������Ѥ�����
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

set_global('marketer', '�ޡ�����������', '�����ӥ��ΰ��Ѥ�', BACK_TOP);
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
	if (f.from_marketer_id.value == "") {
		alert("�����Ѥ����ޥ������������򤷤Ƥ���������");
		f.from_marketer_id.focus();
		return false;
	}
	if (f.to_marketer_id.value == "") {
		alert("�����Ѥ��ޥ������������򤷤Ƥ���������");
		f.to_marketer_id.focus();
		return false;
	}
	if (f.from_marketer_id.value == f.to_marketer_id.value) {
		alert("Ʊ��ޡ��������˰����Ѥ����ȤϤǤ��ޤ���");
		f.from_marketer_id.focus();
		return false;
	}
	return confirm("�����ӥ��ΰ��Ѥ���Ԥ��ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>�������ӥ��ΰ��Ѥ���Ԥ��ޡ�������������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�����Ѥ����ޥ�������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="from_marketer_id"><? select_marketer('- ���򤷤Ƥ������� -', '', 3) ?></select>
<?/*			<input type="checkbox" name="flag" value="1" checked>���Ѥ���⥵���ӥ��򸫤���褦�ˤ���*/?>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�����Ѥ��ޥ�������<?=MUST_ITEM?></td>
		<td class="n1"><select name="to_marketer_id"><? select_marketer('- ���򤷤Ƥ������� -', '', 3) ?></select>
	</tr>
</table>
<br>
<input type="submit" value="�����Ѥ���">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
