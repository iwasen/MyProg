<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�оݾ��ޥ�����Ͽ��Ͽ
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/search.php");
include("$inc/condition.php");
include("$inc/select.php");

//�ᥤ�����
set_global2('project', '�ץ������ȴ����åƥ�ץ졼���Խ�', '�оݾ��ƥ�ץ졼����Ͽ', BACK_TOP);

$search = new search_class;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function OnClick_search() {
	var f = document.form1;
	f.target = "_blank";
	f.action = "search.php";
	f.submit();
}

function OnClick_regist() {
	var f = document.form1;
	if (f.ps_title.value <= 0) {
		alert("�оݾ��̾�����Ϥ��Ƥ���������");
		f.ps_title.focus();
		return(false);
	}
	if (confirm("�оݾ�����Ͽ���ޤ���������Ǥ�����")) {
		f.target = "";
		f.next_action.value='new'
		f.action = "update.php";
		f.submit();
	}
}

//-->
</script>
<? search_script($search) ?>
<? menu_script() ?>
</head>
<body onload="show_reset();document.form1.ps_title.focus()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="enq_save.php">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center"><?=$g_title?></td>
	</tr>
</table>
<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
	<tr>
		<td class="m0" align="left">���оݾ��̾�����Ϥ��Ʋ�����</td>
		<td align="right"><a href="javascript:history.back()"><nobr><font size=-1>>>���</font></nobr></a></td>
	</tr>
</table>
<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
	<tr>
		<td class="m1" width=20%>�����ȥ�</td>
		<td class="n1" width=80%><input class="kanji" type="text" name="ps_title" size=70 maxlength=100></td>
	</tr>
</table>
<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">���оݾ������ꤷ�Ʋ�����</td>
	</tr>
</table>
<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
<? search_condition($search, 'small') ?>
</table><br>
<table class="small" border=0 cellspacing=3 cellpadding=0 width=700>
<div align="center">
<input type="hidden" name="ps_no" <?=value($ps_no)?>>
<input type="hidden" name="next_action">
<input type="button" value="����Ͽ��" onclick="OnClick_regist()">
<input type="button" value="�ꥻ�å�" onclick="document.form1.reset();show_reset()">
<input type="button" value="�Ϳ�����" onclick="OnClick_search()">
<input type="button" value="����롡" onclick="history.back()">
</div>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
