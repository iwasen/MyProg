<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦̵ȿ�����ֲ������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$user_id = $_GET['user_id'];
$url = $_GET['url'];

// ̵ȿ�����ֲ�
db_delete('t_mail_reply', "mrp_user_id=$user_id");

redirect($url);
?>
