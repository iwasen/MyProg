<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ����ףţ¥��󥱡��ȼ�ư����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

// �ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '�᡼��ʸ��Ͽ', BACK_TOP);

// �����ѿ�¸�ߥ����å�
function check_variable(&$text, $name, $place) {
	global $msg;

	if (strpos($text, $name) === false)
		$msg[] = "ɬ�פʺ����ѿ���{$name}�ˤ�{$place}��̵���褦�Ǥ���";
}

// My�ѡ��ȥʡ����饹����
$mp = &$_SESSION['s_my_partner'];
$enquete = &$mp->enquete;
$pjt_id = $mp->pjt_id;

// �ե����फ�饯�饹������
$mp->subject = $subject;
$mp->header = $header;
$mp->body = $body;
$mp->footer = $footer;

// �ꥻ�åȽ���
if ($next_action == 'reset') {
	$mp->subject = '';
	$mp->header = '';
	$mp->body = '';
	$mp->footer = '';
	redirect('edit_mail.php?edit=1');
}

// �ץ�ӥ塼����
if ($next_action == 'preview') {
	redirect('preveiw_mail.php');
}

// �����ѿ�¸�ߥ����å�
check_variable($mp->header, '%POINT%', '�إå�');
check_variable($mp->body, '%MONITOR_NAME%', '��ʸ');
check_variable($mp->body, '%ENQUETE_PARAM%', '��ʸ');
check_variable($mp->body, '%MAIL_ADDR%', '��ʸ');
check_variable($mp->body, '%POINT%', '��ʸ');

if (!$msg) {
	// DB����¸
	$mp->write_db();
}
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

<?
if (!$msg) {
?>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg">My�ѡ��ȥʡ��Υ᡼��ʸ�򹹿����ޤ�����</p>
			<p><input type="button" value="����롡" onclick="location.href='<?=get_return_page()?>'"></p>
		</td>
	</tr>
</table>
<?
} else
	error_msg($msg);
?>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
