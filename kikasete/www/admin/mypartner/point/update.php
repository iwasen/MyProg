<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ݥ������Ϳ
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

$rec['ppj_point_timing'] = sql_number($point_timing);
$rec['ppj_point_rule'] = sql_number($point_rule);
$rec['ppj_point_max'] = sql_number($point_max);
$rec['ppj_point_unit'] = sql_number($point_unit);
$rec['ppj_point_bonus'] = sql_bool($point_bonus);
db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id");
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
<p class="msg">�ݥ������Ϳ�����ꤷ�ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='<?=get_return_page()?>'"></p>
</div>

<? page_footer() ?>
</body>
</html>
