<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ɲ�ȯ��Ƚ�ǥޥ�������
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
set_global('mypartner', 'My�ѡ��ȥʡ�����', '�ɲ�ȯ��Ƚ�ǥޥ�������', BACK_TOP);

$sql = "TRUNCATE TABLE m_addition_send";
db_exec($sql);

foreach (array_keys($hour_start) as $i) {
	if ($hour_start[$i] != '' && $hour_end[$i] != '' && $factor[$i] != '') {
		$rec['mas_hour'] = sql_number($hour_start[$i]);
		$rec['mas_factor'] = sql_number($factor[$i]);
		db_insert('m_addition_send', $rec);
	}
}
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
<p class="msg">�ɲ�ȯ��Ƚ�ǥޥ����򹹿����ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
