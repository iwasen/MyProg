<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�������ݡ��ȥǡ���CSV���ϥ��󥯥롼�ɥե�����
'******************************************************/

// define���
define('COLUMN_NUM_RES', 127);		// ������
define('COLUMN_NUM_1TO1', 18);		// ������

// Mail��졼�������CSV����
function output_res_data($user_id) {
	$data = array();

	// �쥤�����ȥǡ�������
	get_data_layout($layout, $col);

	// ���ꥸ�ʥ�ǡ�����������ɤ߹���
	$sql = "SELECT udt_column_no,udt_value FROM t_user_data WHERE udt_user_id=$user_id ORDER BY udt_column_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$column_no = (int)$fetch->udt_column_no;
		if ($layout[$column_no]->dtl_input == DBTRUE)
			$data[$column_no] = $fetch->udt_value;
	}

	$sql = "SELECT cmp_mail_follow_cd FROM t_user_regist JOIN t_campaign ON cmp_campaign_cd=urg_campaign_cd WHERE urg_user_id=$user_id";
	$data[$col['�����ȥ�']['�ã�̾��']] = db_fetch1($sql);

	// Mail�ե�����̥��å�
	$sql = "SELECT * FROM t_user_result WHERE urs_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$data[$col['�����Ͱո�']['����¾�ؿ��Τ����']] = $fetch->urs_sonota_kanshin;
		$data[$col['�����Ͱո�']['�ؿ�����᡼����']] = $fetch->urs_kanshin_maker;
		$data[$col['�����Ͱո�']['�ؿ������̾']] = $fetch->urs_kanshin_car;
		$data[$col['�����Ͱո�']['�������٥��Ͷ��']] = $fetch->urs_jishuu_enent;
		$data[$col['�����Ͱո�']['��������']] = $fetch->urs_shiryou_souhu;
		$data[$col['�����Ͱո�']['�������ե�����']] = $fetch->urs_shiryou_comment;
		$data[$col['�����Ͱո�']['�����󶡤η�³']] = $fetch->urs_teikyou_keizoku;
		$data[$col['�����Ͱո�']['�����󶡷���']] = $fetch->urs_teikyou_keitai;
		$data[$col['�����Ͱո�']['��Ź�ؤ���˾']] = $fetch->urs_mise_youbou;
		$data[$col['�����Ͱո�']['������']] = $fetch->urs_commant;

		$data[$col['��ͭ����']['��̣�ʤ�']] = $fetch->urs_shumi;

		$data[$col['�ƥ쥳������']['�����ʬ']] = $fetch->urs_kecchaku;
		$data[$col['�ƥ쥳������']['���']] = $fetch->urs_rank;

		$data[$col['����¾']['������(1)']] = $fetch->urs_commant1;
		$data[$col['����¾']['������(2)']] = $fetch->urs_commant2;
		$data[$col['����¾']['������(3)']] = $fetch->urs_commant3;
		$data[$col['����¾']['������(4)']] = $fetch->urs_commant4;
		$data[$col['����¾']['������(5)']] = $fetch->urs_commant5;
		$data[$col['����¾']['������(6)']] = $fetch->urs_commant6;

		$data[$col['����Ź�ޥ����å�']['��������']] = $fetch->urs_kounyuu_joukyou;
		$data[$col['����Ź�ޥ����å�']['������̾']] = $fetch->urs_kounyuu_shamei;
	}

	// �����;����ѹ��ɲþ��󥻥å�
	$sql = "SELECT * FROM t_user_personal WHERE ups_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$user_id = $fetch->ups_user_id;

		$col_org = &$col['�����;���'];
		$col_set = &$col['�ѹ��ɲü�������(������)'];
		user_chage_info($fetch->ups_name_kana, $data[$col_org['������̾�ʎ��š�']], $data[$col_set['������̾�ʥ��ʡ�']]);
		user_chage_info($fetch->ups_name_kanji, $data[$col_org['������̾�ʴ�����']], $data[$col_set['������̾�ʴ�����']]);
		user_chage_info($fetch->ups_zip_cd, $data[$col_org['͹���ֹ�']], $data[$col_set['��']]);
		user_chage_info($fetch->ups_address0, $data[$col_org['����0']], $data[$col_set['����0']]);
		user_chage_info($fetch->ups_address1, $data[$col_org['����1']], $data[$col_set['����1']]);
		user_chage_info($fetch->ups_address2, $data[$col_org['����2']], $data[$col_set['����2']]);
		user_chage_info($fetch->ups_tel_no, $data[$col_org['�����ֹ�']], $data[$col_set['�����ֹ�']]);
		user_chage_info($fetch->ups_mail_addr, $data[$col_org['E-mail���ɥ쥹']], $data[$col_set['��-�ͣ���']]);
	}

	// ���'L'��OneToOne��Ͽ
	$mail_no = 1;
	$rank = $data[$col['�ƥ쥳������']['���']];
	if ($rank == 'L') {
		// �Ǹ����ã�᡼������������
		$sql = "SELECT cml_date"
				. " FROM t_comm_log"
				. " WHERE cml_user_id=$user_id AND cml_err_mail_flag=true"
				. " ORDER BY cml_comm_id DESC"
				. " LIMIT 1";
		$date = db_fetch1($sql);

		// ���̤��������Ƥ��ʤ����ϡ��ǽ�ν���ե����᡼��������������
		if ($date == '') {
			$sql = "SELECT urg_follow_date"
					. " FROM t_user_regist"
					. " WHERE urg_user_id=$user_id";
			$date = db_fetch1($sql);
		}

		// �����������դ�1to1��Ͽ�����������˥��å�
		if ($date != '') {
			$data[$col['One to One��Ͽ1']['��������']] = date('Ymd', sql_time($date));
			$mail_no++;
		}
	}

	// ���'E'��OneToOne��Ͽ
	if ($rank == 'E') {
		// �᡼����������
		$sql = "SELECT ust_cancel_date"
				. " FROM t_user_status"
				. " WHERE ust_user_id=$user_id";
		$date = db_fetch1($sql);

		// �᡼�����Ǥʤ���кǸ����ã�᡼��������������
		if ($date == '') {
			$sql = "SELECT cml_date"
					. " FROM t_comm_log"
					. " WHERE cml_user_id=$user_id AND cml_err_mail_flag=true"
					. " ORDER BY cml_comm_id DESC"
					. " LIMIT 1";
			$date = db_fetch1($sql);
		}

		// �����������դ�1to1��Ͽ�����������˥��å�
		if ($date != '') {
			$data[$col['One to One��Ͽ1']['��������']] = date('Ymd', sql_time($date));
			$mail_no++;
		}
	}

	// OneToOne��Ͽ���åȡʺǿ�������磵���
	$sql = "SELECT cml_comm_id,cml_date,cml_from_name,cml_send_recv,cmc_subject"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " WHERE cml_user_id=$user_id AND (cml_send_recv='R' OR cmc_comm_type=4)"
			. " ORDER BY cml_comm_id DESC"
			. " LIMIT 5";
	$sql = "SELECT * FROM ($sql) sub ORDER BY cml_comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow && $mail_no <= 5; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$col2 = &$col['One to One��Ͽ' . $mail_no++];
		$data[$col2['��������']] = date('Ymd', sql_time($fetch->cml_date));
		$data[$col2['����������']] = date('His', sql_time($fetch->cml_date));
		$data[$col2['������']] = $fetch->cml_from_name;
		$data[$col2['��������ʬ']] = $fetch->cml_send_recv;
		$data[$col2['Mail��̾']] = $fetch->cmc_subject;
	}

	// Mail�����å�
	$sql = "SELECT COUNT(*)"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " WHERE cml_user_id=$user_id AND (cml_send_recv='R' OR cmc_comm_type=4)";
	$result = db_exec($sql);
	$data[$col['Mail��']['One to One']] = pg_fetch_result($result, 0, 0);


	return csv_data($data, COLUMN_NUM_RES);
}

// �ǡ����쥤�����ȼ���
function get_data_layout(&$layout, &$col) {
	// �쥤�����ȥǡ�������
	$sql = "SELECT * FROM t_data_layout ORDER BY dtl_column_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$layout[(int)$fetch->dtl_column_no] = $fetch;

		// ���ϥǡ����Υ�������
		if ($fetch->dtl_input == DBTRUE || $fetch->dtl_output == DBTRUE)
			$col[$fetch->dtl_group_name][$fetch->dtl_item_name] = $fetch->dtl_column_no;
	}
}

// �����;����ѹ��ɲþ��󥻥å�
function user_chage_info(&$data_new, &$data_org, &$data_set) {
	if ($data_new != $data_org)
		$data_set = $data_new;
}

// OneToOne�᡼��CSV����
function output_1to1_data($user_id) {
	$data = array();

	// ���ꥸ�ʥ�ǡ�����������ɤ߹���
	$sql = "SELECT udt_column_no,udt_value"
			. " FROM t_user_data"
			. " WHERE udt_user_id=$user_id AND udt_column_no<=8"
			. " ORDER BY udt_column_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$data[(int)$fetch->udt_column_no] = $fetch->udt_value;
	}

	$sql = "SELECT cmp_1to1_cd FROM t_user_regist JOIN t_campaign ON cmp_campaign_cd=urg_campaign_cd WHERE urg_user_id=$user_id";
	$data[1] = db_fetch1($sql);

	// OneToOne��Ͽ���å�
	$sql = "SELECT cml_comm_id,cml_date,cml_from_addr,cml_send_recv,cml_to_addr,cmc_subject,cmc_body"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " WHERE cml_user_id=$user_id AND (cml_send_recv='R' OR cmc_comm_type=4)"
			. " ORDER BY cml_comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$data[9] = $i + 1;
		$data[10] = $nrow;
		$data[11] = $fetch->cml_send_recv == 'S' ? 1 : 2;
		$data[12] = $fetch->cml_from_addr;
		$data[13] = date('Ymd', sql_time($fetch->cml_date));
		$data[14] = date('His', sql_time($fetch->cml_date));
		$data[15] = $fetch->cml_to_addr;
		$data[16] = '';
		$data[17] = $fetch->cmc_subject;
		$data[18] = $fetch->cmc_body;

		$csv[] = csv_data($data, COLUMN_NUM_1TO1);
	}

	return $csv;
}

// �ǡ�����CSV����
function csv_data(&$data, $column_num) {
	for ($i = 1; $i <= $column_num; $i++)
		set_csv_export($csv, $data[$i]);

	return $csv;
}
?>