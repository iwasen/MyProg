<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:�����ƥ�ޥ������󹹿�����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");

set_global('�����ƥ�ޥ�������', BACK_TOP);

$sql = "UPDATE m_system SET" .
			" sy_site_url=" . sql_char($site_url) .
			", sy_pop_server=" . sql_char($pop_server) .
			", sy_envelope_addr=" . sql_char($envelope_addr) .
			", sy_envelope_pwd=" . sql_char($envelope_pwd) .
			" WHERE sy_system_id=$sys_id";
db_exec($sql);

$msg = '�����ƥ�ޥ�������򹹿����ޤ�����';
$ret = 'location.href=\'list.php\'';
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
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
