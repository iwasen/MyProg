<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ��������᡼�륳��ƥ��ɽ��
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
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '�����᡼�륳��ƥ��ɽ��', BACK_TOP);

// My�ѡ��ȥʡ����饹����
if ($pjt_id) {
	$myp = new my_partner_class;
	$myp->read_db($pjt_id);
} else
	$myp = &$_SESSION['s_my_partner'];
$enquete = &$myp->enquete;

// ��̾�����
if ($myp->subject == '')
	$subject = $myp->enquete->title;
else
	$subject = $myp->subject;

// �إå�����ʸ���եå������
if ($myp->body == '')
	get_enq_body($myp, $header, $body, $footer);
else {
	$header = $myp->header;
	$body = $myp->body;
	$footer = $myp->footer;
}
$mail_text = make_enq_mail($enquete, $header, str_replace('%POINT%', $enquete->point, $body), $footer);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<div align="center">
<br>
<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td>��<?=htmlspecialchars($subject)?></td>
	</tr>
	<tr>
		<td colspan=2>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td><xmp><?=$mail_text?></xmp>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>

<center>
<input type="button" value="����롡" onclick="history.back()">
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
