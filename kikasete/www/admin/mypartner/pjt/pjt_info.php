<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ץ��������߷׾���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ��ץ��������߷׾���', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">

</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">�������ˤĤ��Ƥβ�ļ�</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='../index.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" value="1">
<input type="hidden" name="sort_dir" value="1">
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" width="35%">����Ū</td>
		<td class="m0" align="right" width="65%"><input type="button" name="" value="����"></td>
	</tr>
	<tr>
		<td class="n1" colspan=2>��Ū�Ǥ�����Ū�Ǥ�����Ū�Ǥ�����Ū�Ǥ�����Ū�Ǥ�����Ū�Ǥ�����Ū�Ǥ�����Ū�Ǥ���</td>
	</tr>
	<tr>
		<td class="m0">�����þ��</td>
		<td class="m0" align="right"><input type="button" name="" value="����"></td>
	</tr>
	<tr>
		<td class="n1" colspan=2>�ʤ�餫�η��ǥܥ��ƥ����˷Ȥ�äƤ����<br>
		�����аʾ������<br>
		��Ҥ��࿦�������ߤ����Ұ��ǤϤʤ���
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>��ô���Ծ���</td>
	</tr>
	<tr>
		<td class="m1">���ID</td>
		<td class="n1"><a href="#" title="��Ⱦ����ɽ�����ޤ�">22</a></td>
	</tr>
	<tr>
		<td class="m1">���̾</td>
		<td class="n1">������ҡ�����������</td>
	</tr>
	<tr>
		<td class="m1">ô����ID</td>
		<td class="n1"><a href="#" title="ô���Ծ����ɽ�����ޤ�">999</a></td>
	</tr>
	<tr>
		<td class="m1">ô����̾</td>
		<td class="n1">����̴��</td>
	</tr>
</table>
<br>
<div align="center"><input type="button" value="����롡" onclick="location.href='../index.php'"></div>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
