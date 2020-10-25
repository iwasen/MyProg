<?
/******************************************************
' System :�������ơ�net�Хå�����
' Content:��ã�᡼�����
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/batch_log.php");
include("$inc/point.php");
include("$inc/resign.php");
include("mail_pop3.php");

define('FILE_NAME', __FILE__);
define('MAX_ERR_COUNT', 4);

// ��ŵ�ư�����å�
if (!dup_check('lock_err_mail'))
	exit;

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

// ��ã�᡼���������
function recv_err_mail() {
	$fetch = get_system_info("sy_pop_server,sy_envelope_addr,sy_envelope_pwd");

	$mail = new mail_pop3();
	$num_msg = $mail->open($fetch->sy_pop_server, $fetch->sy_envelope_addr, $fetch->sy_envelope_pwd);
	if (!$num_msg)
		return 0;

	$count = 0;
	$num_msg = min($num_msg, 500);
	for ($i = 1; $i <= $num_msg; $i++) {
		// �᡼����ʸ����
		$body = $mail->get_body($i);

		$to_addr = '';
		$news_id = '';
		$warning = false;

		// To: �� X-Kikasete-News: ��õ��
		$lines = explode("\n", $body);
		if (is_array($lines)) {

			foreach ($lines as $line) {
				if ($to_addr == '' && ereg('^To: (.+)', $line, $regs))
					$to_addr = trim($regs[1]);
				elseif ($news_id == '' && ereg('^X-Kikasete-News: ([0-9]+)', $line, $regs))
					$news_id = trim($regs[1]);
				elseif (strpos($line, '(warning-timeout)') !== false)
					$warning = true;
			}
		}

		// ���ä�����ã�᡼�������Ԥ�
		if ($to_addr != '' && $news_id != '' && !$warning)
			error_mail($to_addr, $news_id);

		// �᡼�륵���о�Υ᡼�����
		$mail->delete($i);
	}
	$mail->close(true);

	return $count;
}
//jeon_start seq=ad2
//6��ʾ�в��
function die_mail(){
//	//��˥����������
	//æ�����
	$sql = "SELECT mn_monitor_id FROM t_monitor WHERE mn_status=2 AND now()-COALESCE(mn_update_date,mn_regist_date)>=interval '182 days'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		// ������
		resign_monitor($fetch->mn_monitor_id);
	}

	$sql = "DELETE FROM t_err_mail WHERE er_member_id IN(SELECT mn_monitor_id from t_monitor WHERE mn_status=9) AND er_member_kind = 1";
	db_exec($sql);
}
//jeon_end seq=ad2
function error_mail($to_addr, $news_id) {
	// �᡼��˥塼���������оݤ����
	$sql = "SELECT ns_target FROM t_mail_news WHERE ns_news_id=$news_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow) {
		$fetch = pg_fetch_row($result, 0);
		$target = $fetch[0];

		// ��˥���ID�ޤ��ϥޡ�������ID�����
		switch ($target) {
		case 1:		// ��˥���
			$sql = "SELECT mn_monitor_id FROM t_monitor WHERE mn_mail_addr_low=" . sql_char(strtolower($to_addr)) . " AND mn_status=0";
			break;
		case 2:		// �ޡ�������
			$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_mail_addr_low=" . sql_char(strtolower($to_addr)) . " AND mr_status=0";
			break;
		default:
			return;
		}

		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		if ($nrow) {
			$fetch = pg_fetch_row($result, 0);
			$member_id = $fetch[0];

			// ��ã��������
			$sql = "SELECT er_news_id,er_err_count FROM t_err_mail WHERE er_member_kind=$target AND er_member_id=$member_id";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			if ($nrow) {
				$fetch = pg_fetch_object($result, 0);

				if ($news_id == $fetch->er_news_id)
					return;

				if ($news_id == get_next_news_id($target, $fetch->er_news_id))
					$err_count = $fetch->er_err_count + 1;
				else
					$err_count = 1;

				// �쥳���ɤ�¸�ߤ���й���
				$sql = "UPDATE t_err_mail SET er_news_id=$news_id,er_err_count=$err_count WHERE er_member_kind=$target AND er_member_id=$member_id";
			} else {
				// �쥳���ɤ�¸�ߤ��ʤ��������
				$err_count = 1;
				$sql = "INSERT INTO t_err_mail (er_member_kind,er_member_id,er_news_id,er_err_count) VALUES ($target,$member_id,$news_id,1)";
			}
			db_exec($sql);

			// ������ã�����ã��������֥����ɤ���ã�˥��å�
			if ($err_count >= MAX_ERR_COUNT) {
				switch ($target) {
				case 1:		// ��˥���
					$sql = "UPDATE t_sum_data SET su_sum_data=su_sum_data-1 WHERE su_sum_id=1";
					db_exec($sql);
					$sql = "UPDATE t_monitor SET mn_status=2 WHERE mn_monitor_id=$member_id AND mn_status=0";
					break;
				case 2:		// �ޡ�������
					$sql = "UPDATE t_marketer SET mr_status=2 WHERE mr_marketer_id=$member_id AND mr_status=0";
					break;
				}
				db_exec($sql);
			}
		}
	}
}

// ���Υ᡼��˥塼��ID����
function get_next_news_id($target, $news_id) {
	$sql = "SELECT ns_news_id FROM t_mail_news WHERE ns_news_id>$news_id AND ns_target=$target AND ns_status=1 ORDER BY ns_news_id LIMIT 1";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow) {
		$fetch = pg_fetch_row($result, 0);
		$next_news_id = $fetch[0];
	}

	return $next_news_id;
}

// ��ã�᡼���������
$count = recv_err_mail();
//jeon_start seq=ad2
die_mail();
//jeon_end seq=ad2
// �Хå����񤭹���
if ($count > 0) {
	$desc = "��ã�᡼���{$count}��������ޤ�����";
	batch_log('0', FILE_NAME, $desc);
}
?>
