<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * ���ޥ��ۿ��Хå�����
 * �ʼ¹Լ��������0,15,30,45ʬ��
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

// ����������
if (isset($_GET['dt']))
	define('CURRENT_TIME', strtotime($_GET['dt']));	// �ƥ�����
else
	define('CURRENT_TIME', time());

// �Хå��������饹����
$batch = new batch_class('melmaga_send');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	global $batch;

	$current_time = sql_unix_time(CURRENT_TIME);

	// ̤�ۿ�����������������᤮����Τ����
	$sql = "SELECT mmg_melmaga_id, mmg_melmaga_name, mmg_notify_mail, mmc_melmaga_contents_id, mmc_subject, mmc_body, mmc_footer"
			. " FROM t_melmaga"
			. " JOIN t_melmaga_contents ON mmc_melmaga_id=mmg_melmaga_id"
			. " WHERE mmg_status=1 AND mmc_status=1 AND mmc_send_date<=$current_time"
			. " ORDER BY mmc_melmaga_contents_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$melmaga_id = $fetch->mmg_melmaga_id;
		$melmaga_contents_id = $fetch->mmc_melmaga_contents_id;

		db_begin_trans();

		//  ���ޥ��ۿ�����
		$send_num = send_melmaga($melmaga_id, $fetch->mmg_melmaga_name, $fetch->mmc_subject, $fetch->mmc_body, $fetch->mmc_footer);

		// ���ơ������������Ѥߡˤ��ۿ����򥻥å�
		$rec['mmc_status'] = 2;
		$rec['mmc_send_num'] = sql_number($send_num);
		db_update('t_melmaga_contents', $rec, sprintf("mmc_melmaga_contents_id=%s", sql_number($melmaga_contents_id)));

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "���ޥ�(ID={$melmaga_id}:{$melmaga_contents_id})��{$send_num}���������ޤ���");
		} else {
			db_rollback();

			$batch->set_log(9, "���ޥ�(ID={$melmaga_id}:{$melmaga_contents_id})���ۿ�������DB���顼��ȯ�����ޤ�����($err_msg)");
			break;
		}

	}
}

//  �����ޥ��ۿ�����
function send_melmaga($melmaga_id, $melmaga_name, $subject, $body, $footer) {
	$from = $melmaga_name . ' <' . get_const('melmaga_from') . '>';

	$contents = make_contents('', $body, $footer);

	$send_mail_id = send_mail_data($subject, $from, '', $contents);

	// ���ޥ����С������
	$sql = "SELECT mbr_member_id,mbr_mail_addr"
			. " FROM t_melmaga_member"
			. " JOIN t_member ON mbr_member_id=mmm_member_id"
			. " WHERE mmm_melmaga_id=$melmaga_id AND mbr_status=1";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		send_mail_member($send_mail_id, $fetch->mbr_mail_addr, '', $fetch->mbr_member_id);
	}
	$send_num = $i;

	send_mail_ok($send_mail_id);

	// �ɼԿ�,ȯ�Բ���򥫥���ȥ��å�
	$rec['mmn_reader_cnt'] = sql_number($send_num);
	$rec['mmn_publish_cnt'] = 'mmn_publish_cnt + 1';
	$rec['mmn_last_publish_date'] = 'current_timestamp';
	db_update('c_melmaga_count', $rec, "mmn_melmaga_id=$melmaga_id");

	return $send_num;
}
?>