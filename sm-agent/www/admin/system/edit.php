<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:�����ƥ�ޥ�����Ͽ�ѹ�����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");

// �ᥤ�����
set_global('�����ƥ�ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_system WHERE sy_system_id=$sys_id";
$result = db_exec($sql);
if (pg_numrows($result))
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
		<td class="m0" colspan=2>�������Ⱦ���</td>
	</tr>
	<tr>
		<td class="m1" width=30%>�����ȥȥå�URL</td>
		<td class="n1" width=70%>
			<input class="alpha" type="text" name="site_url" size=70 maxlength=100 <?=value($fetch->sy_site_url)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���᡼�륵��������</td>
	</tr>
	<tr>
		<td class="m1">POP3�����ФΥۥ���̾</td>
		<td class="n1">
			<input class="alpha" type="text" name="pop_server" size=70 maxlength=100 <?=value($fetch->sy_pop_server)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ã�᡼��������������</td>
		<td class="n1">
			<input class="alpha" type="text" name="envelope_addr" size=70 maxlength=100 <?=value($fetch->sy_envelope_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ã�᡼������ѥ����</td>
		<td class="n1">
			<input class="alpha" type="password" name="envelope_pwd" size=20 maxlength=20 <?=value($fetch->sy_envelope_pwd)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
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
