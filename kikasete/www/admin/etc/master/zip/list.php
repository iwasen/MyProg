<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content͹���ֹ�ޥ�����Ͽ�ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '͹���ֹ�ޥ���', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="edit.php">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>��͹���ֹ�ޥ�������</td>
	</tr>
	<tr>
		<td class="m1" width=35%>�ǽ�������</td>
		<td class="n1" width=65%>2002ǯ2��1��</td>
	</tr>
</table>
<br>
<input type="hidden" name="next_action">
<input type="submit" value="��������">
<input type="button" value="����󥻥�" onclick="location.href='../menu.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
