<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:��˥�������
'******************************************************/

$top = '..';
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
set_global('��˥�������', BACK_TOP);

$search = new search_class;
$show_haimail_flag = true;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? search_script($search) ?>
<? menu_script() ?>
</head>
<body onload="show_reset()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="search_result.php" target="_blank" onsubmit="return check_search_data(this)">
<table border=0 cellspacing=0 cellpadding=0 width=95%>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=800>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
			</table>
			<table class="small" border=0 cellspacing=2 cellpadding=2 width=800>
				<tr>
					<td class="m0" colspan=2 align="left">���оݾ������ꤷ�Ʋ�����</td>
				</tr>
			</table>
			<table class="small" border=0 cellspacing=2 cellpadding=2 width=800>
			<? search_condition($search, 'small', 'agent') ?>
			</table><br>
			<table class="small" border=0 cellspacing=3 cellpadding=0 width=800>
			<div align="center">
			<input type="submit" value="�Ϳ�����">
			<input type="button" value="�ꥻ�å�" onclick="document.form1.reset();show_reset()">
			<input type="button" value="����롡" onclick="history.back()">
			</div>
			</table>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
