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

set_global('proenquete', '�У�異�󥱡��ȴ���', '�У�異�󥱡��ȹ���', BACK_TOP);

// ���å�������
$pro_enq = &$_SESSION['ss_pro_enq'];
if ($pro_enq->pro_enq_no != $pro_enq_no)
    redirect("show.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");

if ($main)
    $enquete = &$pro_enq->enquete2;
else
    $enquete = &$pro_enq->enquete;

// �ե����फ�饯�饹������
$enquete->description = $description;

// �ꥻ�åȽ���
if ($next_action == 'reset') {
    $enquete->description = '';
    redirect("edit_web.php?marketer_id=$marketer_id&main=$main&edit=1");
}

if (!$msg) {
    // DB����¸
    $rec['en_description'] = sql_char($enquete->description);
    db_update('t_enquete', $rec, "en_enquete_id=$pro_enq->enquete_id");

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
<p>�У�異�󥱡��ȤΥ��󥱡��ȣףţ²��̤����ꤷ�ޤ�����</p>
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
