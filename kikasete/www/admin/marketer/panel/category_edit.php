<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ڥ����ѥͥ����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('myenquete', '�ͣ����󥱡��ȴ���', '���ڥ����ѥͥ����', BACK_TOP);

// ���ƥ���������
$sql = sprintf("SELECT pnc_category_name FROM t_panel_category WHERE pnc_category_id=%s", sql_number($_REQUEST['category_id']));
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onload_body() {
	var f = document.form1;
	f.category_name.focus();
}
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		if (f.category_name.value == "") {
			alert("���ƥ���̾�����Ϥ��Ƥ���������");
			f.category_name.focus();
			return false;
		}
		return confirm("���ڥ����ѥͥ륫�ƥ���򹹿����ޤ���������Ǥ�����");
	case 'delete':
		return confirm("���ڥ����ѥͥ륫�ƥ���������ޤ���������Ǥ�����");
	}
}
//-->
</script>
</head>
<body onload="onload_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="category_update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>�����ڥ����ѥͥ륫�ƥ��깹��</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���ƥ���ID</td>
		<td class="n1"><?=$category_id?><input type="hidden" name="category_id" <?=value($_REQUEST['category_id'])?>></td>
	</tr>
	<tr>
		<td class="m1" width="20%">���ƥ���̾<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input type="text" name="category_name" size=80 maxlength=100 <?=value($fetch->pnc_category_name)?>>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<? page_footer() ?>
</body>
</html>
