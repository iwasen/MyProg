<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:�����᡼��ƥ�ץ졼���ѹ�����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '�����᡼��ƥ�ץ졼��', BACK_TOP);

switch ($template_id) {
case 'present1':
	$msg = "<span class='note'>������ʸ�����Ȥ��ޤ����ʲ����̾�� %MEMBER_NAME% ������ȥå�URL�� %TOP_URL%��</span>
";
	break;
case 'present2':
	$msg = "<span class='note'>������ʸ�����Ȥ��ޤ����ʥ����ȥ롧 %TITLE% ��ȯ������ %SEND_DATE%��</span>
";
 break;
}

$sql = "SELECT * FROM m_mail_template WHERE mt_template_id='$template_id'";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("template_id������", __FILE__);
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
	return confirm("�����᡼��ƥ�ץ졼�Ȥ򹹿����ޤ���������Ǥ�����");
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
		<td class="m0" colspan=2>�������᡼��ƥ�ץ졼�Ⱦ�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�ƥ�ץ졼��̾</td>
		<td class="n1">
			<input type="text" name="guide" size=70 <?=value($fetch->mt_guide)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">��̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=100 <?=value($fetch->mt_subject)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">From���ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="from" size=70 maxlength=100 <?=value($fetch->mt_from)?>>
		</td>
	</tr>
<?
if ($template_id != 'present2') {
?>
	<tr>
		<td class="m1">Cc���ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="cc" size=70 maxlength=100 <?=value($fetch->mt_cc)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">Bcc���ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="bcc" size=70 maxlength=100 <?=value($fetch->mt_bcc)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">Reply-To���ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="reply_to" size=70 maxlength=100 <?=value($fetch->mt_reply_to)?>>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">��ʸ</td>
		<td class="n1"><?=$msg?><textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->mt_body)?></textarea></td>
	</tr>
</table>
<br>
<input type="hidden" name="template_id" <?=value($fetch->mt_template_id)?>>
<input type="submit" value="��������">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
