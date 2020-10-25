<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�У�異�󥱡��ȹ�������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
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

set_global('proenquete', '�У�異�󥱡��ȴ���', '�У�異�󥱡��ȹ���', BACK_TOP);

// ���å�������
$pro_enq = &$_SESSION['ss_pro_enq'];
if ($pro_enq->pro_enq_no != $pro_enq_no)
    redirect("show.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");

// �ե����फ�饯�饹������
$pro_enq->mail_title = $subject;
$pro_enq->mail_header = $header;
$pro_enq->mail_contents = $body;
$pro_enq->mail_footer = $footer;

// �ꥻ�åȽ���
if ($next_action == 'reset') {
    $pro_enq->mail_title = '';
    $pro_enq->mail_header = '';
    $pro_enq->mail_contents = '';
    $pro_enq->mail_footer = '';
    redirect("edit_mail.php?marketer_id=$marketer_id&edit=1");
}

// �����ѿ�¸�ߥ����å�
check_variable($pro_enq->mail_header, '%POINT%', '�إå�');
check_variable($pro_enq->mail_contents, '%MONITOR_NAME%', '��ʸ');
check_variable($pro_enq->mail_contents, '%ENQUETE_PARAM%', '��ʸ');
check_variable($pro_enq->mail_contents, '%MAIL_ADDR%', '��ʸ');
check_variable($pro_enq->mail_contents, '%POINT%', '��ʸ');

if (!$msg) {
    // DB����¸
    $pro_enq->write_db();

    // ���å�����ѿ����
    unset($_SESSION['ss_pro_enq']);
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
<p>�У�異�󥱡��ȤΥ᡼��ʸ�����ꤷ�ޤ�����</p>
<input type="button" value="����롡" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
</form>
</div>
<?
} else
    error_msg($msg);
?>
<? page_footer() ?>
</body>
</html>
