<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦�б��Ѥ߰�������
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

// ���ߥ�˥�������������̤�б��ե饰�򥯥ꥢ
$rec['cml_answer_flag'] = 'false';
db_update('t_comm_log', $rec, "cml_comm_id=$comm_id");

// ����åɴ�����̤�б��ե饰�򥯥ꥢ
$sql = "SELECT cml_thread_comm_id FROM t_comm_log WHERE cml_comm_id=$comm_id";
$thread_comm_id = db_fetch1($sql);
if ($thread_comm_id) {
	$rec['cmt_staff_id'] = sql_number($g_staff_id);
	$rec['cmt_answer_flag'] = "EXISTS(SELECT * FROM t_comm_log WHERE cml_thread_comm_id=cmt_comm_id AND cml_answer_flag=true)";
	db_update('t_comm_thread', $rec, "cmt_comm_id=$thread_comm_id");
}

db_commit_trans();

redirect($_GET['url']);
?>
