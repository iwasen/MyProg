<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:Myパートナーバッチ処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/mail.php");
include("$inc/batch_log.php");
include("$inc/my_partner.php");
include("$inc/point.php");
include("$inc/format.php");
include("$inc/icp_db.php");
include("$inc/icp_func.php");

define('FILE_NAME', __FILE__);

// 最大実行時間（３分）
set_time_limit(180);

$time = time();
//$time = strtotime('2005-04-15 00:00:00');	// for debug

start_my_partner($time);
end_my_partner($time);
point_my_partner($time);
exit;

// Myパートナー開始処理
function start_my_partner($time) {
	$current_date = sql_unix_date($time);

	$sql = "SELECT ppj_pjt_id,ppj_marketer_id,ppj_room_name,ppj_room_start,ppj_room_end,ppj_icp_job_id"
			. " FROM t_mp_pjt"
			. " WHERE ppj_status=" . MPS_ROOM_JUNBI . " AND ppj_room_start<=$current_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$pjt_id = $fetch->ppj_pjt_id;

		db_begin_trans();

		// ステータスを実施中に設定
		$rec['ppj_status'] = MPS_ROOM_JISSHI;
		$rec['ppj_last_point_date'] = 'ppj_room_start';
		db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id");

		// マーケターへの開始通知メール
		start_my_partner_mail($fetch->ppj_marketer_id, $fetch->ppj_room_name, $fetch->ppj_room_start, $fetch->ppj_room_end, $fetch->ppj_icp_job_id);

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "Ｍｙパートナー開始処理でエラーが発生しました。($err_msg)";
			batch_log(9, FILE_NAME, $desc);
		} else {
			db_commit_trans();

			// バッチログ書き込み（正常）
			$desc = "Ｍｙパートナー(ID=$pjt_id)の実施ジョブを開始しました。";
			batch_log(0, FILE_NAME, $desc);
		}
	}
}

// マーケターへ開始通知メール送信
function start_my_partner_mail($marketer_id, $room_name, $room_start, $room_end, $icp_job_id) {
	$sql = "SELECT mr_mail_addr,mr_name1,mr_name2 FROM t_marketer WHERE mr_marketer_id=$marketer_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		get_mail_template('myp_mk2', $subject, $from, $cc, $bcc, $repty_to, $body);

		$body = str_replace('%MARKETER_NAME%', "$fetch->mr_name1 $fetch->mr_name2", $body);
		$body = str_replace('%ROOM_NAME%', $room_name, $body);
		$body = str_replace('%ROOM_MAIL_ADDR%', get_icp_room_addr($icp_job_id), $body);
		$body = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $body);
		$body = str_replace('%ROOM_START_DATE%', format_date($room_start), $body);
		$body = str_replace('%ROOM_END_DATE%', format_date($room_end), $body);

		separate_address($from, $from_addr, $from_name);
		send_mail_one($subject, $from_addr, $fetch->mr_mail_addr, $reply_to, $body, $from_name, $cc, $bcc);
	}
}

// Myパートナー終了処理
function end_my_partner($time) {
	$current_date = sql_unix_date($time);

	$sql = "SELECT ppj_pjt_id,ppj_point_timing,ppj_room_end"
			. " FROM t_mp_pjt"
			. " WHERE ppj_status=" . MPS_ROOM_JISSHI . " AND ppj_room_end<$current_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$pjt_id = $fetch->ppj_pjt_id;

		db_begin_trans();

		// ステータスを終了に設定
		$rec['ppj_status'] = MPS_ROOM_END;
		db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id");

		// 一括ポイント付与の処理日設定
		if ($fetch->ppj_point_timing == 1)
			set_ikkatu_point($pjt_id);

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "Ｍｙパートナー終了処理でエラーが発生しました。($err_msg)";
			batch_log(9, FILE_NAME, $desc);
		} else {
			db_commit_trans();

			// バッチログ書き込み（正常）
			$desc = "Ｍｙパートナー(ID=$pjt_id)の実施ジョブを終了しました。";
			batch_log(0, FILE_NAME, $desc);
		}
	}
}

// 一括ポイント付与の処理日設定
function set_ikkatu_point($pjt_id) {
	$ym = '999999';
	$sql = "SELECT COUNT(*) FROM t_mp_point_log WHERE ppl_pjt_id=$pjt_id AND ppl_year_month='$ym'";
	if (db_fetch1($sql) == 0) {
		$sql = "SELECT ppj_room_end FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
		$room_end = db_fetch1($sql);
		$y = get_datepart('Y', $room_end);
		$md = sprintf('%02d%02d', get_datepart('M', $room_end), get_datepart('D', $room_end));

		$sql = "SELECT ppd_calc_date,ppd_give_date FROM m_mp_point_date WHERE ppd_calc_date>='$md' ORDER BY ppd_calc_date LIMIT 1";
		$result = db_exec($sql);
		if (pg_numrows($result) == 0) {
			$sql = "SELECT ppd_calc_date,ppd_give_date FROM m_mp_point_date WHERE ppd_month=1";
			$result = db_exec($sql);
			$y++;
		}
		$fetch = pg_fetch_object($result, 0);
		sscanf($fetch->ppd_calc_date, '%2d%2d', $calc_date_m, $calc_date_d);
		sscanf($fetch->ppd_give_date, '%2d%2d', $give_date_m, $give_date_d);

		$rec['ppl_pjt_id'] = sql_number($pjt_id);
		$rec['ppl_year_month'] = sql_char($ym);
		$rec['ppl_calc_date'] = sql_date("$y/$calc_date_m/$calc_date_d");
		$rec['ppl_give_date'] = sql_date("$y/$give_date_m/$give_date_d");
		db_insert('t_mp_point_log', $rec);
	}
}

// ポイント処理
function point_my_partner($time) {
	db_begin_trans();

	// ポイント履歴レコード作成
	make_point_log($time);

	// ポイント計算処理
	calc_point($time);

	// ポイント付与処理
	give_point($time);

	// エラーチェック
	$err_msg = db_errormessage();
	$icp_err_msg = icp_db_errormessage();

	if ($err_msg == '' && $icp_err_msg == '')
		db_commit_trans();
	else {
		db_rollback();
		$desc = "Ｍｙパートナーのポイント処理でエラーが発生しました。($err_msg$icp_err_msg)";
		batch_log(9, FILE_NAME, $desc);
	}
}

// ポイント履歴レコード作成
function make_point_log($time) {
	$year = date('Y', $time);
	$month = date('n', $time);
	$ym = date('Ym', $time);

	// 当該月の計算日、付与日をマスタから取得
	$sql = "SELECT ppd_calc_date,ppd_give_date FROM m_mp_point_date WHERE ppd_month=$month";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0)
		return;
	$fetch = pg_fetch_object($result, 0);
	sscanf($fetch->ppd_calc_date, '%2d%2d', $calc_date_m, $calc_date_d);
	sscanf($fetch->ppd_give_date, '%2d%2d', $give_date_m, $give_date_d);

	$sql = "SELECT ppj_pjt_id"
			. " FROM t_mp_pjt"
			. " LEFT JOIN t_mp_point_log ON ppl_pjt_id=ppj_pjt_id AND ppl_year_month='$ym'"
			. " WHERE ppj_status>=" . MPS_ROOM_JISSHI . " AND ppl_pjt_id IS NULL"
			. " AND ppj_room_end>=ppj_last_point_date::timestamp";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$rec['ppl_pjt_id'] = sql_number($fetch->ppj_pjt_id);
		$rec['ppl_year_month'] = sql_char($ym);
		$rec['ppl_calc_date'] = sql_date("$year/$calc_date_m/$calc_date_d");
		$rec['ppl_give_date'] = sql_date("$year/$give_date_m/$give_date_d");
		db_insert('t_mp_point_log', $rec);
	}
}

// ポイント計算処理
function calc_point($time) {
	$current_time = sql_unix_time($time);

	$sql = "SELECT ppl_pjt_id,ppl_year_month,ppl_calc_date,ppj_point_timing,ppj_point_rule,ppj_point_max,ppj_point_unit,ppj_icp_job_id,ppj_last_point_date,ppj_room_end"
			. " FROM t_mp_point_log"
			. " JOIN t_mp_pjt ON ppj_pjt_id=ppl_pjt_id"
			. " WHERE ppl_calc_flag=false AND ppl_calc_date<=$current_time AND ppj_point_timing IS NOT NULL"
			. " ORDER BY ppl_year_month"
			. " LIMIT 1";
	for (;;) {
		$result = db_exec($sql);
		if (pg_numrows($result) == 0)
			break;

		$fetch = pg_fetch_object($result, 0);
		$pjt_id = $fetch->ppl_pjt_id;
		$year_month = $fetch->ppl_year_month;

		calc_pjt_point($pjt_id, $year_month, $fetch->ppl_calc_date, $fetch->ppj_point_timing, $fetch->ppj_point_rule, $fetch->ppj_point_max, $fetch->ppj_point_unit, $fetch->ppj_icp_job_id, $fetch->ppj_last_point_date, $fetch->ppj_room_end);

		// ポイント計算済みフラグセット
		$rec['ppl_calc_flag'] = 'true';
		db_update('t_mp_point_log', $rec, "ppl_pjt_id=$pjt_id AND ppl_year_month='$year_month'");

		// 一括ポイント計算後の未計算レコード削除
		if ($year_month == '999999')
			db_delete('t_mp_point_log', "ppl_pjt_id=$pjt_id AND ppl_calc_flag=false");

		// 最終ポイント計算日セット
		$rec['ppj_last_point_date'] = sql_date($fetch->ppl_calc_date);
		db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id");

		if (db_errormessage() != '')
			break;
	}
}

// プロジェクト別ポイント計算処理
function calc_pjt_point($pjt_id, $year_month, $calc_date, $point_timing, $point_rule, $point_max, $point_unit, $icp_job_id, $last_point_date, $room_end) {
	$sql = "SELECT pmn_monitor_id,pmn_icp_member_id"
			. " FROM t_mp_monitor"
			. " WHERE pmn_pjt_id=$pjt_id AND pmn_admin_choice=2 AND pmn_icp_member_id IS NOT NULL";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$monitor_id = $fetch->pmn_monitor_id;
		$icp_member_id = $fetch->pmn_icp_member_id;
		switch ($point_rule) {
		case 1:		// 発言日数
			$count = get_hatsugen_nissuu($icp_job_id, $icp_member_id, $last_point_date, $calc_date);
			break;
		case 2:		// 総発言
			$count = get_hatsugen_suu($icp_job_id, $icp_member_id, $last_point_date, $calc_date);
			break;
		case 3:		// 月ぎめ
			$count = get_tsuki_suu($last_point_date, $calc_date, $room_end);
			break;
		}

		// ポイント計算
		$point = $point_unit * $count;

		// 一括ポイント計算
		if ($year_month == '999999')
			$point += calc_ikkatu_point($pjt_id, $monitor_id);

		// 最大ポイント数までに制限
		if ($point > $point_max)
			$point = $point_max;

		// ポイントを書き込み
		$rec['pmp_point'] = sql_number($point);
		$sql = "SELECT COUNT(*) FROM t_mp_monitor_point WHERE pmp_pjt_id=$pjt_id AND pmp_year_month='$year_month' AND pmp_monitor_id=$monitor_id";
		if (db_fetch1($sql))
			db_update('t_mp_monitor_point', $rec, "pmp_pjt_id=$pjt_id AND pmp_year_month='$year_month' AND pmp_monitor_id=$monitor_id");
		else {
			$rec['pmp_pjt_id'] = sql_number($pjt_id);
			$rec['pmp_year_month'] = sql_char($year_month);
			$rec['pmp_monitor_id'] = sql_number($monitor_id);
			db_insert('t_mp_monitor_point', $rec);
		}
	}
}

// 発言日数取得
function get_hatsugen_nissuu($icp_job_id, $icp_member_id, $last_point_date, $calc_date) {
	$sql = "SELECT COUNT(DISTINCT(date_trunc('day', rm_date)))"
			. " FROM l_remark"
			. " WHERE rm_job_id=$icp_job_id AND rm_remark_type=3 AND rm_member_id=$icp_member_id AND rm_status=1 AND rm_date>='$last_point_date' AND rm_date<'$calc_date'";
	return icp_db_fetch1($sql, 0);
}

// 発言日数取得
function get_hatsugen_suu($icp_job_id, $icp_member_id, $last_point_date, $calc_date) {
	$sql = "SELECT COUNT(*)"
			. " FROM l_remark"
			. " WHERE rm_job_id=$icp_job_id AND rm_remark_type=3 AND rm_member_id=$icp_member_id AND rm_status=1 AND rm_date>='$last_point_date' AND rm_date<'$calc_date'";
	return icp_db_fetch1($sql, 0);
}

// 月数取得
function get_tsuki_suu($last_point_date, $calc_date, $room_end) {
	// 月ぎめ基準日数取得
	$fetch = get_system_info('sy_myp_monthly_point');
	$monthly_point = $fetch->sy_myp_monthly_point;

	// 日数取得
	$from_date = strtotime($last_point_date);
	$to_date = strtotime($calc_date);
	$end_date = strtotime($room_end);
	if ($to_date > $end_date)
		$to_date = $end_date;
	$days = ($to_date - $from_date) / (24 * 60 * 60);

	// 基準日数を超えていればカウント
	return $days >= $monthly_point ? 1 : 0;
}

// 一括ポイント計算処理
function calc_ikkatu_point($pjt_id, $monitor_id) {
	$sql = "SELECT SUM(pmp_point) FROM t_mp_monitor_point WHERE pmp_pjt_id=$pjt_id AND pmp_year_month<>'999999' AND pmp_monitor_id=$monitor_id";
	return (int)db_fetch1($sql, 0);
}

// ポイント付与処理
function give_point($time) {
	$current_time = sql_unix_time($time);

	$sql = "SELECT ppl_pjt_id,ppl_year_month"
			. " FROM t_mp_point_log"
			. " WHERE ppl_give_flag=false AND ppl_give_date<=$current_time AND ppl_calc_flag=true AND ppl_fix_flag=true";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$pjt_id = $fetch->ppl_pjt_id;
		$year_month = $fetch->ppl_year_month;

		// プロジェクト別ポイント付与処理
		give_pjt_point($pjt_id, $year_month);

		// ポイント付与済みフラグセット
		$rec['ppl_give_flag'] = 'true';
		if ($year_month != '999999')
			db_update('t_mp_point_log', $rec, "ppl_pjt_id=$pjt_id AND ppl_year_month='$year_month'");
		else
			db_update('t_mp_point_log', $rec, "ppl_pjt_id=$pjt_id");
	}
}

// プロジェクト別ポイント付与処理
function give_pjt_point($pjt_id, $year_month) {
	get_mail_template('myp_pnt', $subject, $from, $cc, $bcc, $repty_to, $body);

	// 会議室名差込
	$sql = "SELECT ppj_room_name FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$body = str_replace('%ROOM_NAME%', db_fetch1($sql), $body);

	// メール送信データセット
	separate_address($from, $from_addr, $from_name);
	$send_mail_id = send_mail_data($subject, $from_addr, $reply_to, $body, '', '', $from_name);

	// ポイント付与モニター抽出
	$sql = "SELECT pmp_monitor_id,pmp_point,pmp_bonus,mn_mail_addr,mn_name1"
			. " FROM t_mp_monitor_point"
			. " JOIN t_monitor ON mn_monitor_id=pmp_monitor_id AND mn_status<>9"
			. " WHERE pmp_pjt_id=$pjt_id AND pmp_year_month='$year_month'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$monitor_id = $fetch->pmp_monitor_id;

		$point = $fetch->pmp_point + $fetch->pmp_bonus;
		if ($point != 0) {
			// ポイント付与
			monitor_point($monitor_id, PT_MY_PARTNER, $point);

			// ポイントメール送信
			$sub_seq++;
			send_mail_list($send_mail_id, $fetch->mn_mail_addr, $sub_seq);
			send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', $fetch->mn_name1);
			send_mail_embed($send_mail_id, $sub_seq, '%POINT%', $point);
		}
	}

	// メール送信ＯＫ
	send_mail_ok($send_mail_id);
}
?>