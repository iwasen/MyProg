<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ݥ������Ϳ������������
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
set_global('mypartner', 'My�ѡ��ȥʡ�����', '�ݥ������Ϳ������������', BACK_TOP);

db_begin_trans();

for ($i = 1; $i <= 12; $i++) {
	$ary = explode('/', $calc_date[$i]);
	$calc_md = sprintf('%02d%02d', $ary[0], $ary[1]);
	$rec['ppd_calc_date'] = sql_char($calc_md);

	$ary = explode('/', $give_date[$i]);
	$give_md = sprintf('%02d%02d', $ary[0], $ary[1]);
	$rec['ppd_give_date'] = sql_char($give_md);

	db_update('m_mp_point_date', $rec, "ppd_month=$i AND (ppd_calc_date<>$calc_md OR ppd_give_date<>$give_md)");
}

db_commit_trans();
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
<p class="msg">�ݥ������Ϳ���������������ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='edit.php?pjt_id=<?=$pjt_id?>'"></p>
</div>

<? page_footer() ?>
</body>
</html>
