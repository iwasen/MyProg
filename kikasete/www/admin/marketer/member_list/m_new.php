<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���С��ꥹ��
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("inc.php");

set_global($class, $section, '���С��ɲ�����', BACK_TOP);

$sql = "SELECT mmg_group_name FROM t_myenq_member_grp WHERE mmg_group_id = $group_id";
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
	if (f.monitor_list.value == "") {
		alert("��Ͽ�᡼�륢�ɥ쥹�ޤ��ϥ�˥���ID�����Ϥ��Ƥ���������");
		f.monitor_list.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body onload="document.form1.monitor_list.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="confirm.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>�����С��ꥹ�Ⱦ�������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="25%">���С��ꥹ��̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->mmg_group_name)?></td>
	</tr>
	<tr>
		<td class="m1">��˥���ID�ޤ��ϥ᡼�륢�ɥ쥹</td>
		<td class="n1">
			<textarea class="alpha" name="monitor_list" cols=60 rows=20></textarea>
			<div class="note">�ʥ�˥���ID�ޤ��ϥ᡼�륢�ɥ쥹�򥫥�ޡ����Զ��ڤ�����Ϥ��Ƥ���������</div>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="group_id" value="<?=$group_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
