<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ե��ꥨ���Ȱ�纹�ᤷ����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global('monitor', '��˥�������', '���ե��ꥨ���ȴ���', BACK_TOP);

db_delete('t_affiliate', "af_affiliate_mgr_id=$affiliate_mgr_id AND af_status=2");
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
<p class="msg">̵���Υ��ե��ꥨ���Ȥ��ᤷ�ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='mem_list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
