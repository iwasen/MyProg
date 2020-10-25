<?
/******************************************************
' System :�֤�����Factory�ץХå�����
' Content:����󥻥�᡼�����
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/pop3.php");

// ��ŵ�ư�����å�
if (!dup_check('lock_recv_cancel'))
	exit;

// ����¹Ի��֡ʣ���ʬ��
set_time_limit(600);

// ����󥻥�᡼���������
$count = recv_cancel_mail();

// �Хå����񤭹���
if ($count > 0) {
	$desc = "����󥻥�᡼���{$count}��������ޤ�����";
	batch_log('0', __FILE__, $desc);
}
exit;

// ����󥻥�᡼���������
function recv_cancel_mail() {
	// �᡼�륵����̾����
	$server = get_system_info('server_pop3');

	// �᡼�������������ȼ���
	$account = explode('/', get_system_info('account_cancel_mail'), 2);

	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account[0], $account[1]);
	if (!$num_msg)
		return 0;

	db_begin_trans();

	$count = 0;
	for ($i = 1; $i <= $num_msg; $i++) {
		// �᡼��إå�����
		$header = $mail->get_header($i);

		// From���ɥ쥹����
		$from = "{$header->from[0]->mailbox}@{$header->from[0]->host}";

		// ��ʸ�����
		$body = $mail->get_body($i);

		// ��ʸ����ID�������
		$random_id = get_id($body, $from);
		if ($random_id != '') {
			delete_account($random_id);

			$count++;

			if (db_errormessage() != '') {
				$mail->close(false);
				db_rollback();
				exit;
			}
		}

		$mail->delete($i);
	}

	$mail->close(true);
	db_commit_trans();

	return $count;
}

// ID����
function get_id(&$body, &$from) {
	$ary = explode("\n", $body);
	$line = count($ary);
	for ($i = 0; $i < $line; $i++) {
		$ary2 = explode('=', $ary[$i], 2);
		if (count($ary2) == 2) {
			switch (strtolower(trim($ary2[0]))) {
			case 'aid':
				$random_id = trim($ary2[1]);
				break;
			}
		}
	}

	return $random_id;
}

// ID�������
function delete_account($random_id) {
	db_delete('t_nigaoe_data', "nd_random_id=" . sql_char($random_id));
}
?>