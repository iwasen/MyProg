<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��̳�ɥڡ������̥����ƥ२�顼����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header('�����ƥ२�顼') ?>
<div align="center">

<p>�����ƥ२�顼��ȯ�����ޤ�����<br>
���ټ¹Ԥ��Ƥ⥨�顼�ˤʤ���ϥ����ƥ�����Ԥˤ�Ϣ����������
</p>
<input type="button" value="����롡" onclick="history.back()">
</div>

<? center_footer() ?>

</body>
</html>
