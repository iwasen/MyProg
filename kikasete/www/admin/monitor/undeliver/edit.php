<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��ã�᡼�����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// �ᥤ�����
set_global('monitor', '��˥�������', '��ã�᡼�����', BACK_TOP);

$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2"
		. " FROM t_monitor"
		. " WHERE mn_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('��˥����ɣĤ�����', __FILE__);
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
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return confirm("���Υ᡼�륢�ɥ쥹�Υ�˥�����ͭ���ˤ��ޤ�����������Ǥ�����");
	case "delete":
		return confirm("���Υ᡼�륢�ɥ쥹�Υ�˥�������񤵤��ޤ�����������Ǥ�����");
	}
	return false;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����ã�᡼�륢�ɥ쥹�ѹ�</td>
	</tr>
	<tr>
		<td class="m1" width="22%">̾��</td>
		<td class="n1">
			<?=htmlspecialchars($fetch->mn_name1)?> <?=htmlspecialchars($fetch->mn_name2)?>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->mn_mail_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="monitor_id" <?=value($monitor_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��ͭ����" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�����" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>