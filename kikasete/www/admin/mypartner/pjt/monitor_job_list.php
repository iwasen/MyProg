<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ�����������
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
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ�����������', BACK_TOP);
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
		<td class="lt">����Ϻ���Ԥ���λ�������</td>
	</tr>
</table>
<input type="hidden" name="sort_col" value="1">
<input type="hidden" name="sort_dir" value="1">
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>


<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<th bgcolor="pink" onclick='sort_list(1,0)' style='cursor:hand' title='����å�����ȷ���ե饰�Ǿ���˥����Ȥ��ޤ�'>��ļ�ID</th>
<th onclick='sort_list(2,0)' style='cursor:hand' title='����å�����ȥޡ�����������ե饰�Ǿ���˥����Ȥ��ޤ�'>��ļ�̾</th>
<th onclick='sort_list(5,0)' style='cursor:hand' title='����å������̾���Ǿ���˥����Ȥ��ޤ�'>���̾</th>
<th onclick='sort_list(6,0)' style='cursor:hand' title='����å���������̤Ǿ���˥����Ȥ��ޤ�'>ô����̾</th>
<th onclick='sort_list(6,0)' style='cursor:hand' title='����å���������̤Ǿ���˥����Ȥ��ޤ�'>���ơ�����</th>
	</tr>
	<tr class="tc0">
		<td align="center">1111</td>
		<td align="center"><a href="index.php" target="_blank">��ļ���ļ�</a></td>
		<td align="center"><a href="#" target="_blank">������Ҵ��̾</a></td>
		<td align="center"><a href="#" target="_blank">ô���ǥ���</a></td>
		<td align="center"><strong>�»���</strong></td>
	</tr>
	<tr class="tc1">
		<td align="center">1111</td>
		<td align="center"><a href="index.php" target="_blank">��ļ���ļ�</a></td>
		<td align="center"><a href="#" target="_blank">������Ҵ��̾</a></td>
		<td align="center"><a href="#" target="_blank">ô���ǥ���</a></td>
		<td align="center">��λ</td>
	</tr>
	<tr class="tc0">
		<td align="center">1111</td>
		<td align="center"><a href="index.php" target="_blank">��ļ���ļ�</a></td>
		<td align="center"><a href="#" target="_blank">������Ҵ��̾</a></td>
		<td align="center"><a href="#" target="_blank">ô���ǥ���</a></td>
		<td align="center">��λ</td>
	</tr>
</table>
<br>
			<div align="center">
			<input type="button" value="���Ĥ��롡" onclick="javascript:window.close()">��
			</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
