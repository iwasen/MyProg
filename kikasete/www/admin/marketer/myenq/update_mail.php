<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ����󥱡��ȹ�������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

// �����ѿ�¸�ߥ����å�
function check_variable(&$text, $name, $place) {
	global $msg;

	if (strpos($text, $name) === false)
		$msg[] = "ɬ�פʺ����ѿ���{$name}�ˤ�{$place}��̵���褦�Ǥ���";
}

set_global('myenquete', '�ͣ����󥱡��ȴ���', '�ͣ����󥱡��ȹ���', BACK_TOP);

// ���å�������
$myenq = &$_SESSION['ss_my_enq'];
if ($myenq->my_enq_no != $my_enq_no)
	redirect("show.php?marketer_id=$marketer_id&my_enq_no=$my_enq_no");

// �ե����फ�饯�饹������
$myenq->mail_title = $subject;
$myenq->mail_header = $header;
$myenq->mail_contents = $body;
$myenq->mail_footer = $footer;

// �ꥻ�åȽ���
if ($next_action == 'reset') {
	$myenq->mail_title = '';
	$myenq->mail_header = '';
	$myenq->mail_contents = '';
	$myenq->mail_footer = '';
	redirect("edit_mail.php?marketer_id=$marketer_id&edit=1");
}

// �����ѿ�¸�ߥ����å�
check_variable($myenq->mail_header, '%POINT%', '�إå�');
//check_variable($myenq->mail_contents, '%MONITOR_NAME%', '��ʸ');
check_variable($myenq->mail_contents, '%ENQUETE_PARAM%', '��ʸ');
//check_variable($myenq->mail_contents, '%MAIL_ADDR%', '��ʸ');
check_variable($myenq->mail_contents, '%POINT%', '��ʸ');

if (!$msg) {
	// DB����¸
	$myenq->write_db();

	// ���å�����ѿ����
	unset($_SESSION['ss_my_enq']);
}
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

<br>
<?
if (!$msg) {
?>
<div align="center">
<form>
<p>�ͣ����󥱡��ȤΥ᡼��ʸ�����ꤷ�ޤ�����</p>
<input type="button" value="����롡" onClick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
</form>
</div>
<?
} else
    error_msg($msg);
?>
<? page_footer() ?>
</body>
</html>
