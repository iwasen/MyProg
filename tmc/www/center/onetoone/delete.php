<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦�᡼��������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$comm_id = $_GET['comm_id'];

db_begin_trans();

// �쥳���ɺ��
db_delete('t_comm_thread', "cmt_comm_id=$comm_id");
db_delete('t_comm_log', "cml_comm_id=$comm_id");

db_commit_trans();

redirect($_GET['url']);
?>
