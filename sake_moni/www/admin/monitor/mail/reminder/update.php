<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��¥�᡼���ۿ�������
'******************************************************/

$top = '../../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/get_form.php");
include("$inc/image.php");
include("reminder.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");


if (!isset($_SESSION['ss_enq_rm']))
	redirect('list.php');
$rm = &$_SESSION['ss_enq_rm'];
$enquete_id = $rm->enquete_id;

db_begin_trans();

$search_id = $rm->save_search();
$reminder_id = $rm->save_reminder($search_id);

foreach ($enquete_id as $enq_id)
	$rm->update_my_enq($reminder_id, $enq_id);

db_commit_trans();

// �ᥤ�����
set_global('monitor', '������᡼���ۿ�', '��¥�᡼���ۿ�����', BACK_TOP);


?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg">�᡼����������ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
