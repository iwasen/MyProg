<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�����ƥ�ޥ�����Ͽ�ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");

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
		<td class="m1">�ޡ��������ڡ���URL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_marketer)?></td>
	</tr>
	<tr>
		<td class="m1">����������ȥڡ���URL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_agant)?></td>
	</tr>
	<tr>
		<td class="m1">��̳�ɥڡ���URL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_admin)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���᡼��˥塼������</td>
	</tr>
	<tr>
		<td class="m1">��˥������ƥ᡼��˥塼�����������ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_news1_from)?></td>
	</tr>
	<tr>
		<td class="m1">��˥������ƥ᡼��˥塼��������̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_news1_name)?></td>
	</tr>
	<tr>
		<td class="m1">�ޡ������ݰ��ƥ᡼��˥塼�����������ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_news2_from)?></td>
	</tr>
	<tr>
		<td class="m1">�ޡ������ݰ��ƥ᡼��˥塼��������̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_news2_name)?></td>
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
		<td class="m1">�ޡ���������Ͽ�����襢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_marketer_reg_to)?></td>
	</tr>
	<tr>
		<td class="m1">�������������Ͽ�����襢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_agent_reg_to)?></td>
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
		<td class="m0" colspan=2>���䤤��碌����</td>
	</tr>
	<tr>
		<td class="m1">�ޡ��������ȥåץڡ����䤤��碌�����襢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mk_inq_to)?></td>
	</tr>
	<tr>
		<td class="m1">�ޡ�������̵���ڡ����䤤��碌�����襢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mku_inq_to)?></td>
	</tr>
	<tr>
		<td class="m1">�ޡ�������ͭ���ڡ����䤤��碌�����襢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mkk_inq_to)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���᡼�륵��������</td>
	</tr>
	<tr>
		<td class="m1">�����᡼�륨��٥��ץ��������</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_envelope_addr)?></td>
	</tr>
	<tr>
		<td class="m1">����٥��ץ��ɥ쥹�ѥ����</td>
		<td class="n1"><?=password($fetch->sy_envelope_pwd)?></td>
	</tr>
	<tr>
		<td class="m1">POP3�����ФΥۥ���̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_pop_server)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>����˾�᡼�륢�����������</td>
	</tr>
	<tr>
		<td class="m1">��˾�᡼��ɥᥤ��</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mail_domain)?></td>
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
		<td class="m1">���󥱡��ȼ����ѥ����</td>
		<td class="n1"><?=password($fetch->sy_enq_pwd)?></td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�󥱡���������̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_enq_mail)?></td>
	</tr>
	<tr>
		<td class="m1">Web���󥱡���������̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_enq_web)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���Ϥ��᡼��������</td>
	</tr>
	<tr>
		<td class="m1">�����᡼�륢�ɥ쥹</td>
		<td class="n1"><?=$fetch->sy_haimail_addr?></td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�󥱡���������̾��</td>
		<td class="n1"><?=$fetch->sy_haimail_mail?></td>
	</tr>
	<tr>
		<td class="m1">WEB���󥱡���������̾��</td>
		<td class="n1"><?=$fetch->sy_haimail_web?></td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ����ɥᥤ��</td>
		<td class="n1"><?=$fetch->sy_haimail_domain?></td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ������������</td>
		<td class="n1"><?=$fetch->sy_haimail_account?></td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ����ѥ����</td>
		<td class="n1"><?=$fetch->sy_haimail_pwd?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���ͣ���˥����᡼������</td>
	</tr>
	<tr>
		<td class="m1">�ֿ��᡼��������������</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mym_account)?></td>
	</tr>
	<tr>
		<td class="m1">�ֿ��᡼������ѥ����</td>
		<td class="n1"><?=password($fetch->sy_mym_pwd)?></td>
	</tr>
	<tr>
		<td class="m1">���С��ֿ��ݥ����</td>
		<td class="n1"><?=$fetch->sy_mym_point?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���ͣ��ѡ��ȥʡ�����</td>
	</tr>
	<tr>
		<td class="m1">�ꥯ�롼�ȥ᡼�����ȯ����</td>
		<td class="n1"><?=$fetch->sy_myp_max_send?></td>
	</tr>
	<tr>
		<td class="m1">�ꥯ�롼�ȥ���ֺ�������</td>
		<td class="n1"><?=$fetch->sy_myp_max_question?></td>
	</tr>
	<tr>
		<td class="m1">ICP�ץ�������ID</td>
		<td class="n1"><?=$fetch->sy_myp_icp_pjt_id?></td>
	</tr>
	<tr>
		<td class="m1">ICP�ȿ�ID</td>
		<td class="n1"><?=$fetch->sy_myp_icp_org_id?></td>
	</tr>
	<tr>
		<td class="m1">���ݥ���ȴ������</td>
		<td class="n1"><?=$fetch->sy_myp_monthly_point?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���ݥ���ȼ�������</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȼ�������</td>
		<td class="n1"><?=$fetch->sy_point_validity_period?></td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȼ����������</td>
		<td class="n1"><?=$fetch->sy_point_extension?></td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȹ��������ڡ������</td>
		<td class="n1"><?=format_date($fetch->sy_point_campaign_period1, '')?>��<?=format_date($fetch->sy_point_campaign_period2, '')?></td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȹ��������ڡ����Ѥ���Ϳ�ݥ����</td>
		<td class="n1"><?=$fetch->sy_point_campaign_point?></td>
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
