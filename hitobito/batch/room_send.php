<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * �磻�磻�����ƥ᡼�������Хå�����
 * �ʼ¹Լ�������ʬ��
 *
 * @package
 * @author
 * @version
 */

$inc = "../inc";
include("$inc/common.php");
include("$inc/const.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/batch.php");
include("$inc/remark.php");

// �Хå��������饹����
$batch = new batch_class('room_send');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	global $batch;

	// ̤�ۿ��᡼�����
	$sql = "SELECT rmk_remark_id"
			. " FROM l_room_remark"
			. " WHERE rmk_send_flag=false";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$remark_id = $fetch->rmk_remark_id;

		db_begin_trans();

		// �оݼԤ��ۿ�
		$send_num = send_remark($remark_id);

		// ȯ�����ۿ��Ѥߤ˥��å�
		$rec['rmk_send_flag'] = sql_bool(true);
		$rec['rmk_send_num'] = sql_number($send_num);
		db_update('l_room_remark', $rec, "rmk_remark_id=$remark_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			// �Хå����񤭹��ߡ������
			$batch->set_log(0, "�磻�磻��ġ����ID={$remark_id}�ˤ�{$send_num}���������ޤ�����");
		} else {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$batch->set_log(9, "�磻�磻��ġ����ID={$remark_id}�ˤ����������ǥ��顼��ȯ�����ޤ�����($err_msg)");
			break;
		}
	}
}
?>