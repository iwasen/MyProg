<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content����åɰ�ư�¹�
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/remark.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");
include("$inc/err_msg.php");

// ȯ��ID����
function get_remark_id($job_id, $disp_type, $seq_no) {
	if ($disp_type == 1 && $_SESSION[SCD]['room_type'] == ROOM_PIR) {
		$where = sprintf("rm_job_id=%s AND rm_disp_type=%s AND (rm_disp_member_id=%s OR rm_disp_member_id=0) AND rm_seq_no=%s AND rm_status=1",
				sql_number($job_id),
				sql_number($disp_type),
				sql_number($_SESSION[SCD]['disp_member_id']),
				sql_number($seq_no));
	} else {
		$where = sprintf("rm_job_id=%s AND rm_disp_type=%s AND rm_seq_no=%s AND rm_status=1",
				sql_number($job_id),
				sql_number($disp_type),
				sql_number($seq_no));
	}

	$sql = "SELECT rm_remark_id FROM l_remark WHERE $where";

	return db_fetch1($sql);
}

// �ƻҴط������å�
function check_parent_child($job_id, $disp_type, $from_id, $to_id) {
	$sql = sprintf(
				"SELECT rm_parent_remark_id"
			. " FROM l_remark"
			. " WHERE rm_job_id=%s AND rm_disp_type=%s AND rm_remark_id=%s AND rm_status=1",
			sql_number($job_id),
			sql_number($disp_type),
			sql_number($to_id));
	$parent_remark_id = db_fetch1($sql);

	if ($parent_remark_id == 0)
		return true;
	elseif ($parent_remark_id == $from_id)
		return false;
	else
		return check_parent_child($job_id, $disp_type, $from_id, $parent_remark_id);
}

// ���ϥѥ�᡼��
$move_from = $_POST['move_from'];
$move_to = $_POST['move_to'];
$move_target = $_POST['move_target'];
$disp_type = $_POST['disp_type'];
$job_id = $_SESSION[SCD]['job_id'];

// ��ư��ID�����å�
$from_id = get_remark_id($job_id, $disp_type, $move_from);
if (!$from_id)
	$msg[] = "���Ϥ��줿��ư��ID $move_from ��¸�ߤ��ޤ���";

// ��ư��ID�����å�
if ($move_to == 0)
	$to_id = 0;
else {
	$to_id = get_remark_id($job_id, $disp_type, $move_to);
	if (!$to_id)
		$msg[] = "���Ϥ��줿��ư��ID $move_to ��¸�ߤ��ޤ���";
}

// �ƻҴط������å�
if (!$msg) {
	if ($move_target == 2 && $to_id != 0) {
		if (!check_parent_child($job_id, $disp_type, $from_id, $to_id))
			$msg[] = '����å����Τ򤽤Υ���åɤλҤΰ��֤˰�ư�����뤳�ȤϤǤ��ޤ���';
	}
}

if (!$msg) {
	db_begin_trans();

	// ���ꤷ��ȯ���Τߤξ��Ϥ��λҤ�ʬ�οƤ���³
	if ($move_target == 1) {
		// �оݤο�ȯ��ID�����
		$rec['rm_parent_remark_id'] = "(SELECT rm_parent_remark_id FROM l_remark WHERE rm_remark_id=$from_id)";
		db_update('l_remark', $rec, "rm_parent_remark_id=$from_id");

		// ��ȯ��̵ͭ�ե饰����
		set_child_flag($from_id, false);
	}

	// ����åɤΰ�ư
	$rec['rm_parent_remark_id'] = sql_number($to_id);
	db_update('l_remark', $rec, "rm_remark_id=$from_id");

	// ��ȯ��̵ͭ�ե饰����
	set_child_flag($to_id, true);

	db_commit_trans();

	redirect('move.php?reload_subject=1');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>����åɰ�ư���顼</title>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">

<? disp_err('����åɰ�ư�ǥ��顼������ޤ�����', $msg) ?>

</body>

</html>
