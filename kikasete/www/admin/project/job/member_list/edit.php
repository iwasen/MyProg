<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���С��ꥹ��
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global2('project', '�ץ������ȴ����å��С��ꥹ��', '���С��ꥹ���ѹ�', BACK_TOP);

$sql = "SELECT * FROM t_member_grp WHERE mg_group_id = $group_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("���С��ꥹ��ID������", __FILE__);
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
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		if (f.group_name.value == "") {
			alert("���С��ꥹ��̾�����Ϥ��Ƥ���������");
			f.group_name.focus();
			return false;
		}
		return confirm("���С��ꥹ�Ȥ򹹿����ޤ���������Ǥ�����");
	case "delete":
		return confirm("���С��ꥹ�Ȥ������ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="document.form1.group_name.focus()">
<? page_header() ?>
<? menu_start() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>�����С��ꥹ�Ⱦ�������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="25%">���С��ꥹ��ID</td>
		<td class="n1"><?=$group_id?><input type="hidden" name="group_id" value="<?=$group_id?>"></td>
	</tr>
	<tr>
		<td class="m1">���С��ꥹ��̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="group_name" size=50 <?=value($fetch->mg_group_name)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<textarea class="kanji" name="summary" cols=50 rows=5><?=htmlspecialchars($fetch->mg_summary)?></textarea>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
