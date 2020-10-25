<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * �ҤȤӤ��ؤ��ۿ��Хå�����
 * �ʼ¹Լ����������ʬ��
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
$batch = new batch_class('hitobito_letter');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	global $batch;

	$current_time = sql_unix_time(CURRENT_TIME);

	// ̤�ۿ�����������������᤮����Τ����
	$sql = "SELECT hbl_letter_id, hbl_subject, hbl_from_addr, hbl_header, hbl_body, hbl_footer"
			. " FROM t_hitobito_letter"
			. " WHERE hbl_status=2 AND hbl_send_date<=$current_time"
			. " ORDER BY hbl_letter_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$letter_id = $fetch->hbl_letter_id;

		db_begin_trans();

		//  �ҤȤӤ��ؤ��ۿ�����
		$send_num = send_hitobito_letter($letter_id, $fetch->hbl_subject, $fetch->hbl_from_addr, $fetch->hbl_header, $fetch->hbl_body, $fetch->hbl_footer);

		// ���ơ�������������ˤ��ۿ����򥻥å�
		$rec['hbl_status'] = 3;
		$rec['hbl_send_num'] = sql_number($send_num);
		db_update('t_hitobito_letter', $rec, sprintf("hbl_letter_id=%s", sql_number($letter_id)));

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "�ҤȤӤ��ؤ�(ID={$letter_id})��{$send_num}���������ޤ���");
		} else {
			db_rollback();

			$batch->set_log(9, "�ҤȤӤ��ؤ�(ID={$letter_id})���ۿ�������DB���顼��ȯ�����ޤ�����($err_msg)");
			break;
		}
	}
}

//  ���ҤȤӤ��ؤ��ۿ�����
function send_hitobito_letter($letter_id, $subject, $from_addr, $header, $body, $footer) {
	// ���пͥ��ɥ쥹����
	$from = get_const('hitobito_letter_from');

	// �إå�����ʸ���եå�����
	$contents = make_contents($header, $body, $footer);

	// �����᡼������
	$sql = sprintf("UPDATE t_hitobito_letter SET hbl_status=4, hbl_end_date=current_timestamp WHERE hbl_letter_id=%s", sql_number($letter_id));
	$send_mail_id = send_mail_data($subject, $from, '', $contents, '', '', '0', $sql);

	// �ۿ�������
	$sql = "SELECT mbr_member_id,mbr_mail_addr FROM t_member WHERE mbr_status=1 AND mbr_hitobito_letter=1";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$sub_seq = $i + 1;

		// �����襻�å�
		send_mail_member($send_mail_id, $fetch->mbr_mail_addr, $sub_seq, $fetch->mbr_member_id);

		// �����ѿ����å�
		send_mail_embed($send_mail_id, $sub_seq, '%MEMBER_ID%', $fetch->mbr_member_id);
	}
	$send_num = $i;

	send_mail_ok($send_mail_id);

	return $send_num;
}
?>