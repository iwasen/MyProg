<?
/******************************************************
' System :ICP�Хå�����
' Content:����ֳ��Ͻ�λ����
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/status.php");

// ��ŵ�ư�����å�
if (!dup_check('lock_job'))
	exit;

// ����¹Ի��֡ʣ���ʬ��
set_time_limit(600);

// ���������
$g_time = time();
$g_sql_date = sql_date(date('Y-m-d', $g_time));

job_start();
job_end();
job_stop();
exit;

// ����ֳ��Ͻ���
function job_start() {
	global $g_job_status, $g_sql_date;

	// �Ե���ǥ���ֳ��ϻ��֤ˤʤä���Τ򸡺�
	$sql = "SELECT jb_job_id"
			. " FROM t_job"
			. " WHERE jb_status={$g_job_status['�Ե���']} AND jb_start_date<=$g_sql_date AND jb_reorder_flag=false";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$job_id = $fetch->jb_job_id;

		db_begin_trans();

		// ���֥����ɤ�»��������
		$rec['jb_status'] = sql_number($g_job_status['�»���']);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "JOBID={$job_id}�γ��Ͻ����ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// �Хå����񤭹��ߡ������
			$desc = "JOBID={$job_id}�Υ���֤򳫻Ϥ��ޤ�����";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// �������߽���
function job_stop() {
	global $g_job_status, $g_sql_date;

	// �»���ޤ��ϵٻ���ǽ�λ���֤ˤʤä�����֤򸡺�
	$sql = "SELECT jb_job_id"
			. " FROM t_job"
			. " WHERE (jb_status={$g_job_status['�»���']} OR jb_status={$g_job_status['�ٻ���']}) AND jb_end_date<$g_sql_date";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$job_id = $fetch->jb_job_id;

		db_begin_trans();

		// ���֥����ɤ�ƿ���ͱͽ�����������
		$rec['jb_status'] = sql_number($g_job_status['ͱͽ��']);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "JOBID={$job_id}����߽����ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// �Хå����񤭹��ߡ������
			$desc = "JOBID={$job_id}�Υ���֤���ߤ��ޤ�����";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// ����ֽ�λ����
function job_end() {
	global $g_job_status, $g_sql_date;

	// �ƿ���ͱͽ���֤�15���᤮������֤򸡺�
	$sql = "SELECT jb_job_id"
			. " FROM t_job"
			. " WHERE jb_status={$g_job_status['ͱͽ��']} AND (jb_end_date + interval '15 day')<$g_sql_date AND jb_reorder_flag=false";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$job_id = $fetch->jb_job_id;

		db_begin_trans();

		// ���֥����ɤ�λ������
		$rec['jb_status'] = sql_number($g_job_status['��λ']);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "JOBID={$job_id}�ν�λ�����ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// �Хå����񤭹��ߡ������
			$desc = "JOBID={$job_id}�Υ���֤�λ���ޤ�����";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}
?>