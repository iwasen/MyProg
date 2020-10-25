<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * ���С����󥱡��ȳ��Ͻ�λ�Хå�����
 * �ʼ¹Լ���������0��0ʬ��
 *
 * @package
 * @author
 * @version
 */

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/date.php");
include("$inc/batch.php");

// ����������
if (isset($_GET['dt']))
	define('CURRENT_TIME', strtotime($_GET['dt']));	// �ƥ�����
else
	define('CURRENT_TIME', time());

// �Хå��������饹����
$batch = new batch_class('member_enquete');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	// �о�ǯ�����
	$date = sql_date2(get_date_from_ut(CURRENT_TIME));

	// ���󥱡��ȳ��Ͻ���
	start_enquete($date);

	// ���󥱡��Ƚ�λ����
	end_enquete($date);
}

// �����󥱡��ȳ��Ͻ���
function start_enquete($date) {
	global $batch;

	$sql = "SELECT ren_enquete_id"
		. " FROM t_room_enquete"
		. " WHERE ren_status=1 AND ren_start_date<=$date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->ren_enquete_id;

		db_begin_trans();

		$rec['ren_status'] = sql_number(2);
		db_update('t_room_enquete', $rec, "ren_enquete_id=$enquete_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "���С����󥱡��ȡ�ID={$enquete_id}�ˤ򳫻Ϥ��ޤ���");
		} else {
			db_rollback();

			$batch->set_log(9, "���С����󥱡��ȳ��Ͻ�����DB���顼��ȯ�����ޤ�����($err_msg)");
		}
	}
}

// �����󥱡��Ƚ�λ����
function end_enquete($date) {
	global $batch;

	$sql = "SELECT ren_enquete_id"
		. " FROM t_room_enquete"
		. " WHERE ren_status=2 AND ren_end_date<$date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->ren_enquete_id;

		db_begin_trans();

		$rec['ren_status'] = sql_number(9);
		db_update('t_room_enquete', $rec, "ren_enquete_id=$enquete_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "���С����󥱡��ȡ�ID={$enquete_id}�ˤ�λ���ޤ���");
		} else {
			db_rollback();

			$batch->set_log(9, "���С����󥱡��Ƚ�λ������DB���顼��ȯ�����ޤ�����($err_msg)");
		}
	}
}
?>