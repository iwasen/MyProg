<?
/******************************************************
' System :�������ơ�net�Хå�����
' Content:��Ͽ���󹹿�����᡼������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/define.php");
include("$inc/mail.php");
include("$inc/batch_log.php");
include("$inc/format.php");

define('FILE_NAME', __FILE__);

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

db_begin_trans();

send_update_mail();

db_commit_trans();

exit;

// ��Ͽ���󹹿�����᡼������
function send_update_mail() {
	// ����������6����вᤷ�����δ֤���Ͽ����ι������ʤ���˥��������
	$sql = "SELECT mn_name1, mn_mail_addr, mp_curr_point, mp_point_period - interval '1 day' AS expire_date"
		. " FROM t_monitor"
		. " JOIN t_monitor_point ON mn_monitor_id = mp_monitor_id"
		. " WHERE mn_status = 0 AND mp_curr_point > 0"
		. " AND date_part('month', current_date - interval '7 months') = date_part('month', mn_birthday)"
		. " AND COALESCE(mn_update_date, mn_regist_date) < date (date_part('year', current_date - interval '7 months') || '-' || date_part('month', mn_birthday) || '-' || date_part('day', mn_birthday))";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow) {
		// �����᡼��ƥ�ץ졼�Ȥ���ǡ�������
	    get_mail_template('reg_upd', $subject, $from, $cc, $bcc, $repty_to, $body);
		separate_address($from, $from_addr, $from_name);
		if ($subject == '')
			return;

		// �᡼�������ǡ������å�
	    $send_mail_id = send_mail_data($subject, $from_addr, $repty_to, $body, '', '', $from_name);
		$sub_seq = 0;

		// ������ꥹ�ȥ��å�
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			send_mail_list($send_mail_id, $fetch->mn_mail_addr, ++$sub_seq);
			send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', $fetch->mn_name1 != '' ? $fetch->mn_name1 : $fetch->mn_mail_addr);
			send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch->mn_mail_addr);
			send_mail_embed($send_mail_id, $sub_seq, '%POINT%', number_format($fetch->mp_curr_point));
			send_mail_embed($send_mail_id, $sub_seq, '%EXPIRE_DATE%', format_date($fetch->expire_date));
		}

		// �᡼�������ϣ�
		send_mail_ok($send_mail_id);
	}
}
?>