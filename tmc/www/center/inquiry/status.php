<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�䤤��碌�б�������åɾ����ѹ�����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$comm_id = $_GET['comm_id'];
$status = $_GET['status'];
$url = $_GET['url'];

// �����ѹ��ѹ�
$rec['cmt_status'] = $status;
db_update('t_comm_thread', $rec, "cmt_comm_id=$comm_id");

redirect($url);
?>
