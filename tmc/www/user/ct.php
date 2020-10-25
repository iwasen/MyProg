<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����å�������
'******************************************************/

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$ary = explode('-', trim($_SERVER['QUERY_STRING']));
if (count($ary) != 2)
	param_error();

// ����å�������ID����ӥ桼��ID�����
$cc_id = $ary[0];
$user_id = $ary[1];

// ����å�������ID���������å�
if (!is_numeric($cc_id))
	param_error();

// �ƥ��Ȼ�(%CLICK%)�ϥ桼��ID��0
if ($user_id == '%CLICK%')
	$user_id = 0;
elseif (!is_numeric($user_id))
	param_error();

// ����å���������Ͽ�������
$sql = "SELECT clc_jump_url,clc_start_date,clc_end_date FROM t_click_counter WHERE clc_cc_id=$cc_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$time = time();

	// �������������å�
	if ($fetch->clc_start_date != null && sql_time($fetch->clc_start_date) > $time)
		$user_id = 0;

	// ��λ���������å�
	if ($fetch->clc_end_date != null && sql_time($fetch->clc_end_date) < $time - 24*60*60)
		$user_id = 0;

	$jump_url = $fetch->clc_jump_url;
} else
	param_error();

if ($user_id) {
	// �桼������Ͽ����Ƥ��뤫�����å�
	$sql = "SELECT COUNT(*)"
			. " FROM t_user_personal"
			. " WHERE ups_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_fetch_result($result, 0, 0)) {
		db_begin_trans();

		// ����å�������������ɲ�
		$sql = "INSERT INTO t_click_counter_log (ccl_cc_id,ccl_user_id,ccl_ip_adr)"
				. " VALUES ($cc_id,$user_id,'{$_SERVER['REMOTE_ADDR']}')";
		db_exec($sql);

		// ����å���������ȥ��å�
		$sql = "UPDATE t_click_counter SET clc_click_num=clc_click_num+1 WHERE clc_cc_id=$cc_id";
		db_exec($sql);

		db_commit_trans();
	}
}

// �����������إ�����
redirect($jump_url);

// �ѥ�᡼�����顼
function param_error() {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>