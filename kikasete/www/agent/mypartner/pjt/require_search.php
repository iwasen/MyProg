<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ����оݿͿ��������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '�оݿͿ��������', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<div align="center">
<table width=200 border=1>
	<tr>
		<td class="m2" align="center">�оݿͿ��������</td>
	</tr>
	<tr>
		<td class="n1" align="center"><?=number_format($n)?>��</td>
	</tr>
</table>
<br>
<input type="button" value="���Ĥ��롡" onclick="window.close()">
</div>
</body>
</html>
