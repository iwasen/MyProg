<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦����åɾ����ѹ�����
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

db_begin_trans();

// �����ѹ��ѹ�
$rec['cmt_status'] = $status;
db_update('t_comm_thread', $rec, "cmt_comm_id=$comm_id");

// ���ƽ�λ���Ƥ�����᡼���ۿ��Ƴ�
if ($status == 9) {
	$sql = "SELECT cml_user_id FROM t_comm_log WHERE cml_comm_id=$comm_id";
	$user_id = db_fetch1($sql);
	if ($user_id) {
		$sql = "SELECT COUNT(*)"
				. " FROM t_comm_thread JOIN t_comm_log ON cml_comm_id=cmt_comm_id"
				. " WHERE cmt_status=1 AND cml_user_id=$user_id";
		if (db_fetch1($sql) == 0) {
			$rec['ust_rel_stop_flag'] = 'false';
			db_update('t_user_status', $rec, "ust_user_id=$user_id AND ust_rel_stop_flag=true");
		}
	}
}

db_commit_trans();

redirect($url);
?>
