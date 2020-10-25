<?
/******************************************************
' System :�������ơ�net�Хå�����
' Content:My��˥�������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/mail.php");
include("$inc/batch_log.php");
include("$inc/format.php");

define('FILE_NAME', __FILE__);

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

start_my_monitor();
end_my_monitor();
exit;

// My��˥������Ͻ���
function start_my_monitor() {
	$sql = "SELECT mm_room_id,mm_room_name,mm_start_date,mm_end_date,mm_start_mail FROM t_my_monitor WHERE mm_status=2 AND mm_start_date<=CURRENT_DATE AND mm_start_mail_flag=FALSE";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$room_id = $fetch->mm_room_id;

		db_begin_trans();

		get_mail_template('mym_mn1', $subject, $from, $cc, $bcc, $repty_to, $body);
		separate_address($from, $from_addr, $from_name);

		// �᡼�������ơ��֥�˥��å�
		$send_mail_id = send_mail_data($subject, $from_addr, $repty_to, $fetch->mm_start_mail, '', '', $from_name);

		$send_num = 0;
		$sql = "SELECT mn_name1,mn_name2,mn_mail_addr"
				. " FROM t_my_monitor_list JOIN t_monitor ON mn_monitor_id=rl_monitor_id"
				. " WHERE rl_room_id=$room_id";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);

			$sub_seq = $j + 1;
			send_mail_list($send_mail_id, $fetch2->mn_mail_addr, $sub_seq);
			send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch2->mn_mail_addr);
			send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', "$fetch2->mn_name1 $fetch2->mn_name2");
			send_mail_embed($send_mail_id, $sub_seq, '%ROOM_NAME%', $fetch->mm_room_name);
			send_mail_embed($send_mail_id, $sub_seq, '%START_DATE%', format_date($fetch->mm_start_date));
			send_mail_embed($send_mail_id, $sub_seq, '%END_DATE%', format_date($fetch->mm_end_date));

			$send_num++;
		}

		// ���ơ�������������
		send_mail_ok($send_mail_id);

		// �ͣ���˥����»��桢�������Υ᡼�������Ѥ�
		$sql = "UPDATE t_my_monitor SET mm_status=3,mm_start_mail_flag=TRUE WHERE mm_room_id=$room_id";
		db_exec($sql);

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "�ͣ���˥������Ͻ����ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, FILE_NAME, $desc);
		} else {
			db_commit_trans();

			// �Хå����񤭹��ߡ������
			$desc = "�ͣ���˥���(ID=$room_id)�γ������Υ᡼���{$send_num}���������ޤ�����";
			batch_log(0, FILE_NAME, $desc);
		}
	}
}

// My��˥�����λ����
function end_my_monitor() {
	$sql = "SELECT mm_room_id,mm_room_name,mm_start_date,mm_end_date FROM t_my_monitor WHERE (mm_status=3 OR mm_status=4) AND mm_end_date-7<CURRENT_DATE AND mm_end_mail_flag=FALSE";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$room_id = $fetch->mm_room_id;

		db_begin_trans();

		get_mail_template('mym_mn2', $subject, $from, $cc, $bcc, $repty_to, $body);
		separate_address($from, $from_addr, $from_name);

		// �᡼�������ơ��֥�˥��å�
		$send_mail_id = send_mail_data($subject, $from_addr, $repty_to, $body, '', '', $from_name);

		$send_num = 0;
		$sql = "SELECT mn_name1,mn_name2,mn_mail_addr"
				. " FROM t_my_monitor_list JOIN t_monitor ON mn_monitor_id=rl_monitor_id"
				. " WHERE rl_room_id=$room_id";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);

			$sub_seq = $j + 1;
			send_mail_list($send_mail_id, $fetch2->mn_mail_addr, $sub_seq);
			send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch2->mn_mail_addr);
			send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', "$fetch2->mn_name1 $fetch2->mn_name2");
			send_mail_embed($send_mail_id, $sub_seq, '%ROOM_NAME%', $fetch->mm_room_name);
			send_mail_embed($send_mail_id, $sub_seq, '%START_DATE%', format_date($fetch->mm_start_date));
			send_mail_embed($send_mail_id, $sub_seq, '%END_DATE%', format_date($fetch->mm_end_date));

			$send_num++;
		}

		// ���ơ�������������
		send_mail_ok($send_mail_id);

		// ��λ���Υ᡼�������Ѥ�
		$sql = "UPDATE t_my_monitor SET mm_end_mail_flag=TRUE WHERE mm_room_id=$room_id";
		db_exec($sql);

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "�ͣ���˥�����λ�����ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, FILE_NAME, $desc);
		} else {
			db_commit_trans();

			// �Хå����񤭹��ߡ������
			$desc = "�ͣ���˥���(ID=$room_id)�ν�λ���Υ᡼���{$send_num}���������ޤ�����";
			batch_log(0, FILE_NAME, $desc);
		}

	}

	$sql = "SELECT mm_room_id FROM t_my_monitor WHERE (mm_status=3 OR mm_status=4) AND mm_end_date<CURRENT_DATE";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$room_id = $fetch->mm_room_id;

		db_begin_trans();

		// My��˥�����λ
		$sql = "UPDATE t_my_monitor SET mm_status=5 WHERE mm_room_id=$room_id";
		db_exec($sql);

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "�ͣ���˥�����λ�����ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, FILE_NAME, $desc);
		} else {
			db_commit_trans();

			// �Хå����񤭹��ߡ������
			$desc = "�ͣ���˥���(ID=$room_id)��λ���ޤ�����";
			batch_log(0, FILE_NAME, $desc);
		}
	}
}
?>