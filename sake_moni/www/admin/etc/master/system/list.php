<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�����ƥ�ޥ�����Ͽ�ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

function password($str) {
	return str_repeat('*', strlen($str));
}

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '�����ƥ�ޥ���', BACK_TOP);

$sys_id = 1;

$sql = "SELECT * FROM m_system WHERE sy_system_id=$sys_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
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
		<td class="m0" colspan=2>�������ƥ����</td>
	</tr>
	<tr>
		<td class="m1" width=35%>�����ƥ�ID</td>
		<td class="n1" width=65%><?=$fetch->sy_system_id?></td>
	</tr>
	<tr>
		<td class="m1">�����ƥ�̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_system_name)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���ף��գң�����</td>
	</tr>
	<tr>
		<td class="m1">�����ȥȥå�URL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_top)?></td>
	</tr>
	<tr>
		<td class="m1">��˥����ڡ���URL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_monitor)?></td>
	</tr>
	<tr>
		<td class="m1">�����åեڡ���URL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_staff)?></td>
	</tr>
	<tr>
		<td class="m1">��̳�ɥڡ���URL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_admin)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�����ۿ�����</td>
	</tr>
	<tr>
		<td class="m1">���ۿ����������ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mail_from)?></td>
	</tr>
	<tr>
		<td class="m1">���ۿ�������̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mail_name)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�������襢�ɥ쥹����</td>
	</tr>
	<tr>
		<td class="m1">�ͣ����󥱡��ȿ��������襢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_myenq_to)?></td>
	</tr>
	<tr>
		<td class="m1">�᡼��������λ�����襢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mail_notify_to)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���᡼�륵��������</td>
	</tr>
	<tr>
		<td class="m1">�����᡼�륨��٥��ץ��������</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_envelope_addr)?></td>
	</tr>
	<tr>
		<td class="m1">POP3�����ФΥۥ���̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_pop_server)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�����󥱡��ȥ᡼������</td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ����ɥᥤ��</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_enq_domain)?></td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ������������</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_enq_account)?></td>
	</tr>
	<tr>
		<td class="m1">Web���󥱡���������̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_enq_web)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="sys_id" <?=value($sys_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������">
<input type="button" value="����롡" onclick="location.href='../menu.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
