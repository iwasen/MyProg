<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��˥���������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/condition.php");
include("$inc/select.php");

//�ᥤ�����
set_global('monitor', '��˥�������', '��˥���������', BACK_TOP);

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
</head>
<body onload="show_reset()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="result.php" onsubmit="return check_search_data(this)">
<table border=0 cellspacing=2 cellpadding=3 width="90%" class="small">
	<tr>
		<td class="m0" colspan=2>���������</td>
	</tr>
<? search_condition($search, 'small') ?>
</table>

<br>
<input type="submit" value="��������">
<input type="button" value="�ꥻ�å�" onclick="document.form1.reset();show_reset()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
