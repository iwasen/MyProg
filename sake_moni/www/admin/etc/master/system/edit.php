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

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '�����ƥ�ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_system WHERE sy_system_id=$sys_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
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
	if (f.sy_url_staff.value == "") {
		alert("�����åեڡ���URL�����Ϥ��Ƥ���������");
		f.sy_url_staff.focus();
		return false;
	}
	if (f.sy_url_admin.value == "") {
		alert("��̳�ɥڡ���URL�����Ϥ��Ƥ���������");
		f.sy_url_admin.focus();
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
		<td class="m1">�����åեڡ���URL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_staff" size=70 maxlength=100 <?=value($fetch->sy_url_staff)?>>
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
		<td class="m1">POP3�����ФΥۥ���̾</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_pop_server" size=70 maxlength=100 <?=value($fetch->sy_pop_server)?>>
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
		<td class="m1">Web���󥱡���������̾��</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_web" size=70 maxlength=100 <?=value($fetch->sy_enq_web)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
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
