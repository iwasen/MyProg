<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ����᡼��ʸ��Ͽ
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/enq_temp.php");
include("$inc/enq_mail.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '�᡼��ʸ��Ͽ', BACK_TOP);

if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	$myp->read_db($pjt_id);
}
$enquete = &$myp->enquete;

if ($myp->subject == '')
	$subject = $enquete->title;
else
	$subject = $myp->subject;

if ($myp->body == '')
	get_enq_body($myp, $header, $body, $footer);
else {
	$header = $myp->header;
	$body = $myp->body;
	$footer = $myp->footer;
}
$mail_text = make_enq_mail($enquete, $header, $body, $footer);
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
	if (document.form1.next_action.value == 'update')
		return confirm("���Υ᡼��ʸ�򹹿����ޤ���������Ǥ�����");
	else
		return true;
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update_mail.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center">�᡼��ʸ�Υ������ޥ���</td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m1">��̾</td>
					<td class="n1"><input class="kanji" type="text" name="subject" size=80 maxlength=100 <?=value($subject)?>><span class="note">�����ѣ�����ʸ���ޤǡ�</span></td>
				</tr>
				<tr>
					<td class="m1">�إå�</td>
					<td class="n1"><textarea name="header" cols=72 rows=10><?=htmlspecialchars($header)?></textarea></td>
				</tr>
				<tr>
					<td class="m1" width=140>��ʸ</td>
					<td class="n1">
						<span class="note">��������ʸ�����Ȥ��ޤ����ʥ�˥�����̾= %MONITOR_NAME% ��</span>
					<textarea name="body" cols=72 rows=25><?=htmlspecialchars($body)?></textarea></td>
				</tr>
				<tr>
					<td class="m1">�եå�</td>
					<td class="n1"><textarea name="footer" cols=72 rows=8><?=htmlspecialchars($footer)?></textarea></td>
				</tr>
			</table><br>
			<div align="center">
				<input type="submit" value="�᡼��ץ�ӥ塼" onclick="document.form1.next_action.value='preview'">
				<input type="submit" value="�嵭���Ƥ���Ͽ" onclick="document.form1.next_action.value='update'">
				<input type="submit" value="�ꥻ�å�" onclick="document.form1.next_action.value='reset'">
				<input type="button" value="����롡" onclick="history.back()">
				<input type="hidden" name="next_action">
			</div>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
