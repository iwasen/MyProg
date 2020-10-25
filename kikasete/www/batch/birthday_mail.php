<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:誕生日メール送信
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/define.php");
include("$inc/mail.php");
include("$inc/batch_log.php");
include("$inc/encrypt.php");

define('FILE_NAME', __FILE__);

// 最大実行時間（３分）
set_time_limit(180);

db_begin_trans();

send_birthday_mail();

db_commit_trans();

exit;

// 誕生日メール送信
function send_birthday_mail() {
	$adr_ary = Array();

	$sql = "SELECT * FROM t_birthday_mail WHERE bm_void_flag=1 ORDER BY bm_priority";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$seq_no = $fetch->bm_seq_no;
		$sex = $fetch->bm_sex;
		$age_from = $fetch->bm_age_from;
		$age_to = $fetch->bm_age_to;
		$subject = $fetch->bm_subject;
		$body = $fetch->bm_body;
		separate_address($fetch->bm_from_adr, $from_adr, $from_name);
		$ad_where = '';
		$sub_seq = 0;

		// 性別
		switch($sex) {
		case 1:
			$ad_where = ' AND mn_sex = 1';
			break;
		case 2:
			$ad_where = ' AND mn_sex = 2';
			break;
		}

		// 年齢
		if($age_from && $age_to)
			$ad_where .= " AND DATE_PART('Y', AGE(current_timestamp, mn_birthday)) BETWEEN $age_from AND $age_to";
		elseif($age_from && !$age_to)
			$ad_where .= " AND DATE_PART('Y', AGE(current_timestamp, mn_birthday)) >= $age_from";
		elseif(!$age_from && $age_to)
			$ad_where .= " AND DATE_PART('Y', AGE(current_timestamp, mn_birthday)) <= $age_to";

		// モニタ抽出
		$sql = "SELECT mn_monitor_id, mn_name1,  mn_mail_addr FROM t_monitor WHERE "
			. " TO_CHAR(mn_birthday,'MMDD')=TO_CHAR(current_date,'MMDD')"
			. ' AND mn_status=0'
			. $ad_where;
		$result_m = db_exec($sql);
		$nrow_m = pg_numrows($result_m);
		if ($nrow_m) {

			// メール送信データセット
			$send_mail_id = send_mail_data($subject, $from_adr, $reply_to, $body, '', '', $from_name);

			for ($n = 0; $n < $nrow_m; $n++) {
				$fetch = pg_fetch_object($result_m, $n);
				if(!in_array($fetch->mn_mail_addr, $adr_ary)) {
					array_push($adr_ary, $fetch->mn_mail_addr);
					$sub_seq++;

					// ログインURL
					$fetch2 = get_system_info('sy_url_monitor');
					$bm_param = encrypt_param($seq_no, $fetch->mn_monitor_id);
					$login_url = $fetch2->sy_url_monitor . 'birthday.php?id=' . $bm_param;

					// 誕生日メール送信
					send_mail_list($send_mail_id, $fetch->mn_mail_addr, $sub_seq);
					send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', $fetch->mn_name1 != '' ? $fetch->mn_name1 : $fetch->mn_mail_addr);
					send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch->mn_mail_addr);
					send_mail_embed($send_mail_id, $sub_seq, '%LOGIN_URL%', $login_url);
				}
			}

			// メール送信ＯＫ
			send_mail_ok($send_mail_id);
		}
	}
}
?>