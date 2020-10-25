<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:発言処理
'******************************************************/

// 発言登録処理
function set_remark($remark_type, $job_id, $member_id, $to_member_id, $message_id, $parent_remark_id, &$subject, &$body, $web_mail, $mail_header = '', $attachment = '') {
	// プロジェクトコード、ジョブコード、メンバーコードを取得
	$sql = "SELECT pj_pjt_cd,jb_job_cd,jb_room_type,jb_room_header,jb_room_footer,jm_member_cd,jm_participant_auth,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pm_mail_addr,jb_open_mail_addr"
			. " FROM t_job_member"
			. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
			. " JOIN t_job ON jb_job_id=jm_job_id"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jm_job_id=$job_id AND jm_member_id=$member_id AND jm_status=1";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$pjt_cd = $fetch->pj_pjt_cd;
		$job_cd = $fetch->jb_job_cd;
		$member_cd = $fetch->jm_member_cd;
		$room_type = $fetch->jb_room_type;
	} else
		return;

	// Fromアドレスを編集
	if ($fetch->jb_open_mail_addr == DBFALSE) {
		if (check_participant_auth('司会者', $fetch->jm_participant_auth))
			$from_addr = get_mc_mail_addr($pjt_cd, $job_cd);
		else
			$from_addr = get_member_mail_addr($pjt_cd, $job_cd, $member_cd);
	} else
		$from_addr = $fetch->pm_mail_addr;
	$from = join_mail_from($from_addr, $fetch->pm_nickname);

	// 発言種別による処理分け
	switch ($remark_type) {
	case RT_START:
		$disp_type = DT_REMARK;
		$disp_member_id = 0;
		$seq_no = "(SELECT COALESCE(max(rm_seq_no),0)+1 FROM l_remark WHERE rm_job_id=$job_id AND rm_disp_type=1)";
		break;
	case RT_REMARK:
		$disp_type = DT_REMARK;
		if ($room_type == ROOM_PIR) {
			if (check_participant_auth('司会者', $fetch->jm_participant_auth))
				$disp_member_id = $to_member_id;
			else
				$disp_member_id = $member_id;
			$seq_no = "(SELECT COALESCE(max(rm_seq_no),0)+1 FROM l_remark WHERE rm_job_id=$job_id AND rm_disp_type=1 AND (rm_disp_member_id=$disp_member_id OR rm_disp_member_id=0))";
		} else {
			$disp_member_id = 0;
			$seq_no = "(SELECT COALESCE(max(rm_seq_no),0)+1 FROM l_remark WHERE rm_job_id=$job_id AND rm_disp_type=1)";
		}
		break;
	case RT_HELP:
		$disp_type = DT_HELP;
		$disp_member_id = $member_id;
		$seq_no = "(SELECT COALESCE(max(rm_seq_no),0)+1 FROM l_remark WHERE rm_job_id=$job_id AND rm_disp_type=2)";
		break;
	case RT_ANSWER:
		$disp_type = DT_HELP;
		$disp_member_id = $to_member_id;
		$seq_no = "(SELECT COALESCE(max(rm_seq_no),0)+1 FROM l_remark WHERE rm_job_id=$job_id AND rm_disp_type=2)";
		break;
	default:
		$disp_type = null;
		$disp_member_id = null;
		$seq_no = 'null';
		break;
	}

	// 差し込み変数処理
	$content = replace_room_var($job_id, $body);
	$header = replace_room_var($job_id, $fetch->jb_room_header);
	$footer = replace_room_var($job_id, $fetch->jb_room_footer);

	// 本文中の実メールアドレスを個人マスクアドレスに置換
	if ($fetch->jb_open_mail_addr == DBFALSE) {
		if (check_participant_auth('司会者', $fetch->jm_participant_auth))
			$content = str_replace($fetch->pm_mail_addr, get_mc_mail_addr($pjt_cd, $job_cd), $content);
		else
			$content = str_replace($fetch->pm_mail_addr, get_member_mail_addr($pjt_cd, $job_cd, $member_cd), $content);
	}

	// メーリングリスト名と発言番号を削除
	$reg = sprintf('\[%s-%s-%s:[0-9]+\] *(Re: *)*', get_system_info('mail_prefix'), $pjt_cd, $job_cd);
	$subject = eregi_replace($reg, '', $subject);

	// 件名無しの処置
	if ($subject == '')
		$subject = '（件名無し）';

	db_begin_trans();

	// 発言ログに保存
	$rec['rm_job_id'] = sql_number($job_id);
	$rec['rm_member_id'] = sql_number($member_id);
	$rec['rm_date'] = sql_date(get_received_date($mail_header));
	$rec['rm_parent_remark_id'] = sql_number($parent_remark_id);
	$rec['rm_disp_type'] = sql_number($disp_type);
	$rec['rm_disp_member_id'] = sql_number($disp_member_id);
	$rec['rm_remark_type'] = sql_number($remark_type);
	$rec['rm_seq_no'] = $seq_no;
	$rec['rm_from'] = sql_char($from);
	$rec['rm_message_id'] = sql_char($message_id);
	$rec['rm_mail_header'] = sql_char($mail_header);
	$rec['rm_subject'] = sql_char($subject);
	$rec['rm_content'] = sql_char($content);
	$rec['rm_room_header'] = sql_char($header);
	$rec['rm_room_footer'] = sql_char($footer);
	$rec['rm_web_mail'] = sql_number($web_mail);
	db_insert('l_remark', $rec);
	$remark_id = get_current_seq('l_remark', 'rm_remark_id');

	// 添付ファイルを保存
	if ($attachment) {
		for ($i = 0; $i < count($attachment); $i++) {
			$rec['at_remark_id'] = sql_number($remark_id);
			$rec['at_seq_no'] = sql_number($i + 1);
			$rec['at_filename'] = sql_char($attachment[$i]['filename']);
			$rec['at_data'] = sql_char(base64_encode($attachment[$i]['body']));
			db_insert('t_attachment', $rec);
		}
	}

	// 子発言フラグセット
	if ($parent_remark_id) {
		$rec['rm_child_flag'] = sql_bool(true);
		db_update('l_remark', $rec, "rm_remark_id=$parent_remark_id AND rm_child_flag=false");
	}

	db_commit_trans();
}

// メール配信処理
function send_remark($remark_id, $where, $add_send_to = '') {
	$sql = "SELECT rm_job_id,rm_remark_type,rm_seq_no,rm_parent_remark_id,rm_subject,rm_from,rm_message_id,rm_content,rm_room_header,rm_room_footer,jb_room_type,rm_disp_member_id,pj_pjt_cd,jb_job_cd"
			. " FROM l_remark"
			. " JOIN t_job ON jb_job_id=rm_job_id"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE rm_remark_id=$remark_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// プロジェクトCD,ジョブCD等取得
		$pjt_cd = $fetch->pj_pjt_cd;
		$job_cd = $fetch->jb_job_cd;
		$job_id = $fetch->rm_job_id;
		$room_type = $fetch->jb_room_type;
		$remark_type = $fetch->rm_remark_type;
		$disp_member_id = $fetch->rm_disp_member_id;

		// 親メッセージID取得
		if ($fetch->rm_parent_remark_id) {
			$sql = "SELECT rm_message_id FROM l_remark WHERE rm_remark_id=$fetch->rm_parent_remark_id AND rm_status=1";
			$ref_message_id = db_fetch1($sql);
		} else
			$ref_message_id = '';

		// メールヘッダ編集
		$mail_header = make_mail_header($fetch->rm_message_id, $ref_message_id);

		// ヘッダ・本文・フッタを編集
		$content = make_contents($fetch->rm_room_header, $fetch->rm_content, $fetch->rm_room_footer);

		// 使用差し込み変数名取得
		$var_name_ary = get_member_var_name($content);

		// 件名にメーリングリスト名と発言番号を付加
		$subject = $fetch->rm_subject;
		if ($remark_type == 2 || $remark_type == 3)
			$subject = sprintf('[%s-%s-%s:%d] %s', get_system_info('mail_prefix'), $pjt_cd, $job_cd, $fetch->rm_seq_no, $subject);

		db_begin_trans();

		// 送信メールデータセット
		$send_mail_id = send_mail_data($subject, $fetch->rm_from, '', $content);

		// 添付ファイルをセット
		$sql = "SELECT at_filename,at_data FROM t_attachment WHERE at_remark_id=$remark_id ORDER BY at_seq_no";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			send_mail_file($send_mail_id, $i + 1, $fetch->at_filename, base64_decode($fetch->at_data));
		}

		// 配信先メンバー情報取得
		$sql = "SELECT jm_member_id,jm_member_cd,pm_mail_addr,pm_name1,pm_name2,pm_nickname,pm_password,pm_org_id,pm_org_member_id"
				. " FROM t_job_member"
				. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
				. " WHERE jm_job_id=$job_id AND jm_status=1";
		if ($where != '')
			$sql .= " AND $where";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$sub_seq = $i + 1;

			// 返信先取得
			switch ($remark_type) {
			case RT_GUIDE:
			case RT_END:
				$reply_to = get_help_mail_addr($pjt_cd, $job_cd);
				break;
			case RT_START:
				if ($room_type == ROOM_PIR)
					$reply_to = get_mc_mail_addr($pjt_cd, $job_cd);
				else
					$reply_to = get_room_mail_addr($pjt_cd, $job_cd);
				break;
			case RT_REMARK:
				if ($room_type == ROOM_PIR) {
					if ($fetch->jm_member_id == $disp_member_id)
						$reply_to = get_mc_mail_addr($pjt_cd, $job_cd);
					else {
						$sql = "SELECT jm_member_cd FROM t_job_member WHERE jm_job_id=$job_id AND jm_member_id=$disp_member_id";
						$reply_to = get_member_mail_addr($pjt_cd, $job_cd, db_fetch1($sql));
					}
				} else
					$reply_to = get_room_mail_addr($pjt_cd, $job_cd);
				break;
			case RT_HELP:
			case RT_ANSWER:
				if ($fetch->jm_member_id == $disp_member_id)
					$reply_to = get_help_mail_addr($pjt_cd, $job_cd);
				else {
					$sql = "SELECT jm_member_cd FROM t_job_member WHERE jm_job_id=$job_id AND jm_member_id=$disp_member_id";
					$reply_to = get_member_mail_addr($pjt_cd, $job_cd, db_fetch1($sql));
				}
				break;
			}

			// 配信先リスト設定
			send_mail_list($send_mail_id, $fetch->pm_mail_addr, $sub_seq, $mail_header, $reply_to);

			// 差し込み変数設定
			$var_val_ary = get_member_var_val($var_name_ary,
					$pjt_cd,
					$job_cd,
					$fetch->pm_mail_addr,
					$fetch->pm_name1,
					$fetch->pm_name2,
					$fetch->pm_nickname,
					$fetch->pm_password,
					$fetch->jm_member_cd,
					$fetch->pm_org_id,
					$fetch->pm_org_member_id);

			// 差し込み変数テーブルに格納
			foreach ($var_val_ary as $var_name => $var_val)
				send_mail_embed($send_mail_id, $sub_seq, $var_name, $var_val);

			// To:ヘッダをルームアドレスに設定
			if (($room_type == ROOM_GIR || $room_type == ROOM_SR) && ($remark_type == 2 || $remark_type == 3))
				send_mail_embed($send_mail_id, $sub_seq, '%%TO_ADDR%%', get_room_mail_addr($pjt_cd, $job_cd));
		}

		// 追加配信先に配信
		$add_send_ary = explode(' ', $add_send_to);
		foreach ($add_send_ary as $add_mail_addr) {
			if ($add_mail_addr != '') {
				$sub_seq++;

				// 配信先リスト設定
				send_mail_list($send_mail_id, $add_mail_addr, $sub_seq, $mail_header);

				// 差し込み変数設定
				$var_val_ary = get_member_var_val($var_name_ary,
						$pjt_cd,
						$job_cd,
						$add_mail_addr,
						'[差込：姓]',
						'[差込：名]',
						'[差込：ニックネーム]',
						'[差込：ログインパスワード]',
						'******',
						'[差込：組織ID]',
						'[差込：組織メンバID]');

				// 差し込み変数テーブルに格納
				foreach ($var_val_ary as $var_name => $var_val)
					send_mail_embed($send_mail_id, $sub_seq, $var_name, $var_val);
			}
		}

		send_mail_ok($send_mail_id);

		db_commit_trans();
	}
}

// 子発言有無フラグ設定
function set_child_flag($remark_id, $child_flag = null) {
	if ($remark_id) {
		if ($child_flag === null)
			$child_flag = "EXISTS (SELECT * FROM l_remark WHERE rm_parent_remark_id=$remark_id)";
		else
			$child_flag = sql_bool($child_flag);
		$rec['rm_child_flag'] = $child_flag;
		db_update('l_remark', $rec, "rm_remark_id=$remark_id AND rm_child_flag<>$child_flag");
	}
}

// メールヘッダから受信日時取得
function get_received_date(&$mail_header) {
	$date = 'now';

	if ($mail_header != '') {
		$ary = explode("\n", $mail_header);
		foreach ($ary as $header) {
			if (eregi('Received:.+;(.+)', $header, $regs)) {
				$date = trim($regs[1]);
				break;
			}
		}
	}

	return $date;
}
?>