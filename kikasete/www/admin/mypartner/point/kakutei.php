<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ݥ���ȳ������
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
set_global('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ����ݥ������Ϳ', BACK_TOP);

if ($cancel) {
	$rec['ppl_fix_flag'] = 'false';
	$msg = '�ݥ���Ȥγ������ä��ޤ�����';
} else {
	$rec['ppl_fix_flag'] = 'true';
	$msg = '�ݥ���Ȥ���ꤷ�ޤ�����';
}
db_update('t_mp_point_log', $rec, "ppl_pjt_id=$pjt_id AND ppl_year_month='$ym'");
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
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='index.php?pjt_id=<?=$pjt_id?>'"></p>
</div>

<? page_footer() ?>
</body>
</html>
