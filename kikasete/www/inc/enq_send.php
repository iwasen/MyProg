<?
/******************************************************
' System :きかせて・net共通
' Content:アンケートメール送信
'******************************************************/

// アンケートメール生成
function make_enquete_mail(&$enquete, $header, $body, $footer, $point, $qr_point = 0) {
	$mail_text = make_enq_mail($enquete, $header, $body, $footer);
	return str_replace('%POINT%', number_format($point), str_replace('%QR-POINT%', number_format($qr_point), $mail_text));
}

// アンケートメール配信処理
function send_enquete_mail($enquete_id, $enq_type, $enq_account, $sql, $subject, $mail_text, $add_send_no = 'null') {
	if ($enq_type == 1)
		$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_mail AS from_name');
	else
		$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_web AS from_name');
	$enq_domain = $fetch->sy_enq_domain;
	$enq_account = strtok($fetch->sy_enq_account, '%');
	$from = "$enq_account@$enq_domain";

	// メール送信内容設定
	$send_mail_id = send_mail_data($subject, $from, '', $mail_text, '', '', $fetch->from_name);

	// アンケート対象者抽出
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$monitor_id = $fetch->mn_monitor_id;

		// アンケート対象者リストに登録
		$sql = "INSERT INTO t_enquete_list (el_enquete_id,el_monitor_id,el_send_date,el_status,el_add_send_no)"
				. " VALUES ($enquete_id,$monitor_id,CURRENT_TIMESTAMP,1,$add_send_no)";
		db_exec($sql);

		// アンケートパラメータ取得
		$enquete_param = encrypt_param($enquete_id, $monitor_id);

		// メール送信リストに追加
		$sub_seq = $i + 1;
		send_mail_list($send_mail_id, $fetch->mn_mail_addr, $sub_seq, $mail_header);

		// 差込データ
		send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch->mn_mail_addr);
		send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', $fetch->mn_name1);
		switch ($enq_type) {
		case 1:   // メール
			send_mail_embed($send_mail_id, $sub_seq, '%%FROM_ADDR%%', "$enq_account-$enquete_param@$enq_domain");
			break;
		case 2:   // Web
			send_mail_embed($send_mail_id, $sub_seq, '%ENQUETE_PARAM%', $enquete_param);
			break;
		}

		// モニターのアンケート情報更新
		$sql = "UPDATE t_monitor_point SET mp_enq_chance=mp_enq_chance+1,mp_enq_history=SUBSTR('0'||mp_enq_history,1,10)"
				. " WHERE mp_monitor_id=$monitor_id";
		db_exec($sql);
	}

	send_mail_ok($send_mail_id);

	return $nrow;
}

// アンケート対象モニター属性保存
function save_enq_monitor_data($enquete_id, $enquete2_id = 0) {
	$sql = "INSERT INTO t_enq_monitor "
		. " (mn_enquete_id,mn_monitor_id,mn_enq_date,mn_birthday,mn_age,mn_sex,mn_mikikon,mn_jitaku_zip,mn_jitaku_area,mn_jitaku_addr1,mn_jitaku_addr2,mn_jitaku_addr3,mn_shokugyou_cd,mn_gyoushu_cd,mn_shokushu_cd,mn_kinmu_area,mn_have_child,mn_housing_form,mn_have_car,mn_spouse_flg,mn_spouse_birthday,mn_spouse_shokugyo_cd,mn_senior_flg)"
		. " SELECT el_enquete_id,mn_monitor_id,current_timestamp,mn_birthday,DATE_PART('Y',AGE(mn_birthday)),mn_sex,mn_mikikon,mn_jitaku_zip,mn_jitaku_area,mn_jitaku_addr1,mn_jitaku_addr2,mn_jitaku_addr3,mn_shokugyou_cd,mn_gyoushu_cd,mn_shokushu_cd,mn_kinmu_area,mn_have_child,mn_housing_form,mn_have_car,mn_spouse_flg,mn_spouse_birthday,mn_spouse_shokugyo_cd,mn_senior_flg"
		. " FROM t_enquete_list"
		. " JOIN t_monitor ON mn_monitor_id=el_monitor_id"
		. " WHERE el_enquete_id=$enquete_id AND NOT EXISTS (SELECT * FROM t_enq_monitor WHERE mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id)";
	db_exec($sql);

	// 本アンケート分
	if ($enquete2_id != 0 && $enquete_id != $enquete2_id) {
		$sql = "INSERT INTO t_enq_monitor"
			. " (mn_enquete_id,mn_monitor_id,mn_enq_date,mn_birthday,mn_age,mn_sex,mn_mikikon,mn_jitaku_zip,mn_jitaku_area,mn_jitaku_addr1,mn_jitaku_addr2,mn_jitaku_addr3,mn_shokugyou_cd,mn_gyoushu_cd,mn_shokushu_cd,mn_kinmu_area,mn_have_child,mn_housing_form,mn_have_car,mn_spouse_flg,mn_spouse_birthday,mn_spouse_shokugyo_cd,mn_senior_flg)"
			. " SELECT $enquete2_id,mn_monitor_id,mn_enq_date,mn_birthday,mn_age,mn_sex,mn_mikikon,mn_jitaku_zip,mn_jitaku_area,mn_jitaku_addr1,mn_jitaku_addr2,mn_jitaku_addr3,mn_shokugyou_cd,mn_gyoushu_cd,mn_shokushu_cd,mn_kinmu_area,mn_have_child,mn_housing_form,mn_have_car,mn_spouse_flg,mn_spouse_birthday,mn_spouse_shokugyo_cd,mn_senior_flg"
			. " FROM t_enq_monitor T1"
			. " WHERE mn_enquete_id=$enquete_id AND NOT EXISTS (SELECT * FROM t_enq_monitor T2 WHERE T2.mn_enquete_id=$enquete2_id AND T2.mn_monitor_id=T1.mn_monitor_id)";
		db_exec($sql);
	}
}
?>