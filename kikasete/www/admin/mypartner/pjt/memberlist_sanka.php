<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ������ü԰���
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
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '���ü԰���', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function OnChange_client_id() {
	document.form1.tantousha_id.value = '';
	document.form1.submit();
}
function onclick_project(project_id) {
	var win;
	win = window.open("project_info.php?project_id=" + project_id, "project_info", "resizable=yes,width=600,height=400");
	win.focus();
}


//-->
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

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<th onclick='sort_list(3,0)' style='cursor:hand' title='����å�����ȥ�˥���IO�Ǿ���˥����Ȥ��ޤ�'>�ӎƎ���ID</th>
<th onclick='sort_list(5,0)' style='cursor:hand' title='����å������̾���Ǿ���˥����Ȥ��ޤ�'>̾��</th>
<th onclick='sort_list(6,0)' style='cursor:hand' title='����å���������̤Ǿ���˥����Ȥ��ޤ�'>����</th>
<th onclick='sort_list(7,0)' style='cursor:hand' title='����å������ǯ��Ǿ���˥����Ȥ��ޤ�'>ǯ��</th>
<th onclick='sort_list(7,0)' style='cursor:hand' title='����å�����ȵｻ�ϤǾ���˥����Ȥ��ޤ�'>�ｻ��</th>
	</tr>
	<tr class="tc0">
		<td align="center">111</td>
		<td align="center"><a href="monitor_job_list.php" target="_blank">������Ϻ</a></td>
		<td align="center">����</td>
		<td align="center">22</td>
		<td align="center">�����</td>
	</tr>
	<tr class="tc1">
		<td align="center">111</td>
		<td align="center"><a href="monitor_job_list.php" target="_blank">������Ϻ</a></td>
		<td align="center">����</td>
		<td align="center">22</td>
		<td align="center">�����</td>
	</tr>
	</tr>
	<tr class="tc0">
		<td align="center">111</td>
		<td align="center"><a href="monitor_job_list.php" target="_blank">������Ϻ</a></td>
		<td align="center">����</td>
		<td align="center">22</td>
		<td align="center">�����</td>
	</tr>
	</tr>
</table>
<br>
			<div align="center">
			<input type="button" value="���Ĥ��롡" onclick="javascript:window.close()">
			</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
