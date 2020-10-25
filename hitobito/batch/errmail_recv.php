<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * ��ã�᡼������Хå�����
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
include("$inc/pop3.php");
include("$inc/batch.php");

// �Хå��������饹����
$batch = new batch_class('errmail_recv');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	global $batch;

	$count = 0;

	// POP�����С���������ȼ���
	$pop3_server = get_const('pop3_server');
	list($pop3_account, $pop3_password) = explode('/', get_const('error_mail_account'));

	// �᡼�륵���Ф���³
	$mail = new pop3_class();
	$num_msg = $mail->open($pop3_server, $pop3_account, $pop3_password);
	if (!$num_msg)
		return;

	db_begin_trans();

	$count = 0;
	$num_msg = min($num_msg, 500);
	for ($i = 1; $i <= $num_msg; $i++) {
		// �᡼����ʸ����
		$body = $mail->get_body($i);

		$member_id = '';
		$warning = false;

		// X-Member-Id:: ��õ��
		$lines = explode("\n", $body);
		if (is_array($lines)) {

			foreach ($lines as $line) {
				if ($member_id == '' && preg_match('/^X-Member-Id: ([0-9]+)/', $line, $regs))
					$member_id = $regs[1];
				elseif (strpos($line, '(warning-timeout)') !== false)
					$warning = true;
			}
		}

		// ���ä�����ã�᡼�������Ԥ�
		if ($member_id != '' && !$warning) {
			errmail_recv($member_id);
			$count++;
		}

		// �᡼�륵���о�Υ᡼�����
		$mail->delete($i);
	}

	// ���顼�����å�
	$err_msg = db_errormessage();
	if ($err_msg == '') {
		db_commit_trans();
		$mail->close(true);

		if ($count)
			$batch->set_log(0, "��ã�᡼���{$count}��������ޤ���");
	} else {
		db_rollback();
		$mail->close(false);

		$batch->set_log(9, "��ã�᡼�������DB���顼��ȯ�����ޤ�����($err_msg)");
	}
}

// ����ã�᡼���������
function errmail_recv($member_id) {
	// ��ã����򥫥���ȥ��å�
	$sql = sprintf("SELECT und_count FROM c_undeliver WHERE und_member_id=%s", sql_number($member_id));
	$count = db_fetch1($sql, false);

	$rec['und_last_date'] = 'current_timestamp';
	if ($count === false) {
		$rec['und_member_id'] = sql_number($member_id);
		$rec['und_count'] = sql_number(1);
		db_insert('c_undeliver', $rec);
	} else {
		$rec['und_count'] = sql_number($count + 1);
		db_update('c_undeliver', $rec, sprintf("und_member_id=%s", sql_number($member_id)));
	}
}
?>