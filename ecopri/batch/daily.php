<?
/******************************************************
' System :Eco-footprint �Хå�����
' Content:��������
'******************************************************/

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch_log.php");
include("$inc/mail.php");
include("$inc/encrypt.php");

define('FILE_NAME', __FILE__);

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

regist_reminder();
exit;

function regist_reminder() {
	// ����Ͽ�Τޤޣ����ְʾ�вᤷ����Τ����
	$sql = "SELECT mb_seq_no,mb_id,mb_mail_addr FROM t_member WHERE (mb_step=1 OR mb_step=2) AND mb_reg_reminder IS NULL AND mb_regist_date < current_timestamp - interval '7 day'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow) {
		// �����᡼��ƥ�ץ졼�ȼ���
		get_mail_template('reg_rem', $subject, $from, $cc, $bcc, $repty_to, $body);
		$fetch = get_system_info('sy_url_top');
		$url = $fetch->sy_url_top;
		send_mail($subject, $reg->mb_mail_addr, $from, $body, $cc, $bcc, $reply_to);

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			// �����Ѥߥե饰���å�
			$sql = "UPDATE t_member SET mb_reg_reminder=1 WHERE mb_seq_no=$fetch->mb_seq_no";
			db_exec($sql);

			// ���顼�����å�
			$err_msg = db_errormessage();
			if ($err_msg)
				break;

			// �᡼������
			$body2 = str_replace('%MAIL_ADDR%', $fetch->mb_mail_addr, $body);
			$body2 = str_replace('%MEMBER_ID%', $fetch->mb_id, $body2);
			$body2 = str_replace('%REGIST_URL%', "${url}regist.php?key=" . encrypt_number($fetch->mb_seq_no, 8), $body2);
			send_mail($subject, $fetch->mb_mail_addr, $from, $body2, $cc, $bcc, $reply_to);
		}

		// �Хå���
		if ($err_msg) {
			// �Хå����񤭹��ߡʥ��顼��
			$desc = "����Ͽ��¥�᡼�����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, FILE_NAME, $desc);
		} else {
			// �Хå����񤭹��ߡ������
			$desc = sprintf("����Ͽ��¥�᡼������������λ���ޤ�������%d���", $i);
			batch_log(0, FILE_NAME, $desc);
		}
	}
}
?>