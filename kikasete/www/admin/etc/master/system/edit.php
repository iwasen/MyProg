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

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '�����ƥ�ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_system WHERE sy_system_id=$sys_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('�����ƥ�ɣĤ�����', __FILE__);
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
	if (f.sy_system_name.value == "") {
		alert("�����ƥ�̾�Τ����Ϥ��Ƥ���������");
		f.sy_system_name.focus();
		return false;
	}
	if (f.sy_url_monitor.value == "") {
		alert("��˥����ڡ���URL�����Ϥ��Ƥ���������");
		f.sy_url_monitor.focus();
		return false;
	}
	if (f.sy_url_marketer.value == "") {
		alert("�ޡ��������ڡ���URL�����Ϥ��Ƥ���������");
		f.sy_url_marketer.focus();
		return false;
	}
	if (f.sy_url_agant.value == "") {
		alert("����������ȥڡ���URL�����Ϥ��Ƥ���������");
		f.sy_url_agant.focus();
		return false;
	}
	if (f.sy_url_admin.value == "") {
		alert("��̳�ɥڡ���URL�����Ϥ��Ƥ���������");
		f.sy_url_admin.focus();
		return false;
	}
	if (f.sy_news1_from.value == "") {
		alert("��˥������ƥ᡼��˥塼�����������ɥ쥹�����Ϥ��Ƥ���������");
		f.sy_news1_from.focus();
		return false;
	}
	if (f.sy_news2_from.value == "") {
		alert("�ޡ������ݰ��ƥ᡼��˥塼�����������ɥ쥹�����Ϥ��Ƥ���������");
		f.sy_news2_from.focus();
		return false;
	}
	if (f.sy_mail_from.value == "") {
		alert("���ۿ����������ɥ쥹�����Ϥ��Ƥ���������");
		f.sy_mail_from.focus();
		return false;
	}
	return confirm("�����ƥ�ޥ�������򹹿����ޤ���������Ǥ�����");
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�������ƥ�ޥ�����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width=35%>�����ƥ�ID<?=MUST_ITEM?></td>
		<td class="n1" width=65%><?=$fetch->sy_system_id?></td>
	</tr>
	<tr>
		<td class="m1">�����ƥ�̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_system_name" size=70 maxlength=50 <?=value($fetch->sy_system_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���ף��գң�����</td>
	</tr>
	<tr>
		<td class="m1">�����ȥȥå�URL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_top" size=70 maxlength=100 <?=value($fetch->sy_url_top)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��˥����ڡ���URL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_monitor" size=70 maxlength=100 <?=value($fetch->sy_url_monitor)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ޡ��������ڡ���URL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_marketer" size=70 maxlength=100 <?=value($fetch->sy_url_marketer)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����������ȥڡ���URL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_agant" size=70 maxlength=100 <?=value($fetch->sy_url_agant)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�ɥڡ���URL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_admin" size=70 maxlength=100 <?=value($fetch->sy_url_admin)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���᡼��˥塼������</td>
	</tr>
	<tr>
		<td class="m1">��˥������ƥ᡼��˥塼�����������ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_news1_from" size=70 maxlength=100 <?=value($fetch->sy_news1_from)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��˥������ƥ᡼��˥塼��������̾��</td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_news1_name" size=70 maxlength=50 <?=value($fetch->sy_news1_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ޡ������ݰ��ƥ᡼��˥塼�����������ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_news2_from" size=70 maxlength=100 <?=value($fetch->sy_news2_from)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ޡ������ݰ��ƥ᡼��˥塼��������̾��</td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_news2_name" size=70 maxlength=50 <?=value($fetch->sy_news2_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�����ۿ�����</td>
	</tr>
	<tr>
		<td class="m1">���ۿ����������ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mail_from" size=70 maxlength=100 <?=value($fetch->sy_mail_from)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���ۿ�������̾��</td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_mail_name" size=70 maxlength=50 <?=value($fetch->sy_mail_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�������襢�ɥ쥹����</td>
	</tr>
	<tr>
		<td class="m1">�ޡ���������Ͽ�����襢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_marketer_reg_to" size=70 maxlength=100 <?=value($fetch->sy_marketer_reg_to)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�������������Ͽ�����襢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_agent_reg_to" size=70 maxlength=100 <?=value($fetch->sy_agent_reg_to)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ͣ����󥱡��ȿ��������襢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_myenq_to" size=70 maxlength=100 <?=value($fetch->sy_myenq_to)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼��������λ�����襢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mail_notify_to" size=70 maxlength=100 <?=value($fetch->sy_mail_notify_to)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���䤤��碌����</td>
	</tr>
	<tr>
		<td class="m1">�ޡ��������ȥåץڡ����䤤��碌�����襢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mk_inq_to" size=70 maxlength=100 <?=value($fetch->sy_mk_inq_to)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ޡ�������̵���ڡ����䤤��碌�����襢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mku_inq_to" size=70 maxlength=100 <?=value($fetch->sy_mku_inq_to)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ޡ�������ͭ���ڡ����䤤��碌�����襢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mkk_inq_to" size=70 maxlength=100 <?=value($fetch->sy_mkk_inq_to)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���᡼�륵��������</td>
	</tr>
	<tr>
		<td class="m1">�����᡼�륨��٥��ץ��������</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_envelope_addr" size=70 maxlength=100 <?=value($fetch->sy_envelope_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����٥��ץ��ɥ쥹�ѥ����</td>
		<td class="n1">
			<input class="alpha" type="password" name="sy_envelope_pwd" size=20 maxlength=20 <?=value($fetch->sy_envelope_pwd)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">POP3�����ФΥۥ���̾</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_pop_server" size=70 maxlength=100 <?=value($fetch->sy_pop_server)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>����˾�᡼�륢�����������</td>
	</tr>
	<tr>
		<td class="m1">��˾�᡼��ɥᥤ��</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mail_domain" size=70 maxlength=100 <?=value($fetch->sy_mail_domain)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�����󥱡��ȥ᡼������</td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ����ɥᥤ��</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_domain" size=70 maxlength=100 <?=value($fetch->sy_enq_domain)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ������������</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_account" size=70 maxlength=100 <?=value($fetch->sy_enq_account)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ����ѥ����</td>
		<td class="n1">
			<input class="alpha" type="password" name="sy_enq_pwd" size=70 maxlength=100 <?=value($fetch->sy_enq_pwd)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�󥱡���������̾��</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_mail" size=70 maxlength=100 <?=value($fetch->sy_enq_mail)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Web���󥱡���������̾��</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_web" size=70 maxlength=100 <?=value($fetch->sy_enq_web)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���Ϥ��᡼��������</td>
	</tr>
	<tr>
		<td class="m1">�����᡼�륢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_addr" size=70 maxlength=100 <?=value($fetch->sy_haimail_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�󥱡���������̾��</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_mail" size=70 maxlength=100 <?=value($fetch->sy_haimail_mail)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">WEB���󥱡���������̾��</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_web" size=70 maxlength=100 <?=value($fetch->sy_haimail_web)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ����ɥᥤ��</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_domain" size=70 maxlength=100 <?=value($fetch->sy_haimail_domain)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ������������</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_account" size=70 maxlength=100 <?=value($fetch->sy_haimail_account)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ����ѥ����</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_pwd" size=70 maxlength=100 <?=value($fetch->sy_haimail_pwd)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���ͣ���˥����᡼������</td>
	</tr>
	<tr>
		<td class="m1">�ֿ��᡼��������������</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mym_account" size=70 maxlength=100 <?=value($fetch->sy_mym_account)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ֿ��᡼������ѥ����</td>
		<td class="n1">
			<input class="alpha" type="password" name="sy_mym_pwd" size=70 maxlength=100 <?=value($fetch->sy_mym_pwd)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���С��ֿ��ݥ����</td>
		<td class="n1">
			<input class="number" type="text" name="sy_mym_point" size=5 maxlength=5 <?=value($fetch->sy_mym_point)?>>
			<span class="note">�ʿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���ͣ��ѡ��ȥʡ�����</td>
	</tr>
	<tr>
		<td class="m1">�ꥯ�롼�ȥ᡼�����ȯ����</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_max_send" size=5 maxlength=5 <?=value($fetch->sy_myp_max_send)?>>
			<span class="note">�ʿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ꥯ�롼�ȥ���ֺ�������</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_max_question" size=5 maxlength=5 <?=value($fetch->sy_myp_max_question)?>>
			<span class="note">�ʿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ICP�ץ�������ID</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_icp_pjt_id" size=5 maxlength=5 <?=value($fetch->sy_myp_icp_pjt_id)?>>
			<span class="note">�ʿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ICP�ȿ�ID</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_icp_org_id" size=5 maxlength=5 <?=value($fetch->sy_myp_icp_org_id)?>>
			<span class="note">�ʿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���ݥ���ȴ������</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_monthly_point" size=5 maxlength=5 <?=value($fetch->sy_myp_monthly_point)?>>
			<span class="note">�ʿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���ݥ���ȼ�������</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȼ�������</td>
		<td class="n1">
			<input class="number" type="text" name="sy_point_validity_period" size=5 maxlength=5 <?=value($fetch->sy_point_validity_period)?>>����
			<span class="note">�ʿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȼ����������</td>
		<td class="n1">
			<input class="number" type="text" name="sy_point_extension" size=5 maxlength=5 <?=value($fetch->sy_point_extension)?>>����
			<span class="note">�ʿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȹ��������ڡ������</td>
		<td class="n1">
			<input class="number" type="text" name="sy_point_campaign_period1" size=12 maxlength=10 <?=value(format_date($fetch->sy_point_campaign_period1, ''))?>>
			��
			<input class="number" type="text" name="sy_point_campaign_period2" size=12 maxlength=10 <?=value(format_date($fetch->sy_point_campaign_period2, ''))?>>
			<span class="note">��YYYY/MM/DD��</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȹ��������ڡ����Ѥ���Ϳ�ݥ����</td>
		<td class="n1">
			<input class="number" type="text" name="sy_point_campaign_point" size=5 maxlength=5 <?=value($fetch->sy_point_campaign_point)?>>
			<span class="note">�ʿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȹ��������ڡ���ݥ������Ϳ�Ѥߥե饰</td>
		<td class="n1">
			<input type="checkbox" name="campaign_point_reset" <?=value($fetch->sy_point_campaign_point)?>>�ꥻ�åȤ���
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="sys_id" <?=value($sys_id)?>>
<input type="submit" value="��������">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
