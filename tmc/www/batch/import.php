<?
/******************************************************
' System :�֤���������� �������������ӥ��׿������
' Content:�桼�����󥤥�ݡ��Ƚ���
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/login_id.php");
include("$inc/check.php");

// ����¹Ի��֡ʣ����֡�
set_time_limit(3600);

// ����ݡ��ȥե�����̾�μ���
if ($_SERVER['argc'] != 0) {
	// ���ޥ�ɥ饤�󤫤鵯ư
	for ($i = 1; $i < $_SERVER['argc']; $i++) {
		switch ($_SERVER['argv'][$i]) {
		case '-f':
			$force = true;
			break;
		case '-c':
			$check = true;
			break;
		default:
			$import_file = $_SERVER['argv'][$i];
			break;
		}
	}
} else {
	// Apache���鵯ư
	$import_file = $_FILES['import_file']['tmp_name'];
	$force = $_POST['force'];
	$check = $_POST['check'];
}
if ($import_file == '') {
	message('����ݡ��ȥե�����λ��꤬����ޤ���');
	exit;
}

db_begin_trans();

// ����ݡ��Ƚ���
$ok_count = 0;
$ng_count = 0;
$ig_count = 0;
import_user_data($import_file, $ok_count, $ng_count, $ig_count);

// ���ɽ��
message('');
message("������ǡ�����{$ok_count}��");
message("���顼�ǡ�����{$ng_count}��");
message("��̵��ǡ�����{$ig_count}��");

// �������ɽ��
message('');
if ($ng_count != 0 && !$force || $check) {
	db_rollback();
	err_post_proc();
	if ($check)
		message('����ݡ��ȥǡ��������å��Ͻ�λ���ޤ�����');
	else
		message('���ϥǡ����˥��顼�����뤿�ᡢ����ݡ��ȤϹԤ��ޤ���Ǥ�����');
} else {
	db_commit_trans();
	message("����ݡ��Ȥ�¹Ԥ��ޤ�����");
}
exit;

// �ǡ����쥤�����ȼ���
function get_data_layout(&$layout, &$col) {
	// �쥤�����ȥǡ�������
	$sql = "SELECT * FROM t_data_layout ORDER BY dtl_column_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$layout[] = $fetch;

		// ���ϥǡ����Υ�������
		if ($fetch->dtl_input == DBTRUE || $fetch->dtl_output == DBTRUE)
			$col[$fetch->dtl_group_name][$fetch->dtl_item_name] = $fetch->dtl_column_no - 1;
	}
}

// �桼���ǡ�������ݡ���
function import_user_data($import_file, &$ok_count, &$ng_count, &$ig_count) {
	if (($fp = fopen($import_file, 'r')) == false) {
		message('�ե�����Υ����ץ�˼��Ԥ��ޤ�����');
		exit;
	}

	// �쥤�����ȥǡ�������
	get_data_layout($layout, $col);

	$line = 1;
	while ($data = fgetcsv($fp, 10000, ",")) {
		mb_convert_variables('EUC-JP', 'SJIS', $data);

		if ($data[0] != '') {
			if (check_user_data($data, $layout, $col, $line, $error, $campaign_cd)) {
				// ��Ͽ����
				$user_id = insert_user_regist($error, $data, $col, $campaign_cd);

				// ���顼���η����ʬ�ȥ�󥯤򥻥å�
				if ($error) {
					$data[$col['�ƥ쥳������']['�����ʬ']] = 3;
					$data[$col['�ƥ쥳������']['���']] = 'L';
				}

				// ����ݡ��ȥǡ������ꥸ�ʥ�ǡ���
				insert_user_data($user_id, $data);

				if (!$error) {
					// ���������
					insert_user_login($user_id);

					// ���ơ���������
					insert_user_status($user_id);

					// �Ŀ;���
					insert_user_personal($user_id, $data, $col);

					// ��������
					insert_user_manage($user_id, $data, $col);

					// ����ͭ����
					insert_user_car($user_id, $data, $col);

					// �����ڡ���������
					insert_user_campaign($user_id, $data, $col);

					$ok_count++;
				} else
					$ng_count++;
			} else
				$ig_count++;
		}
		$line++;
	}

	fclose($fp);
}

// �桼���ǡ��������å�
function check_user_data(&$data, &$layout, &$col, $line, &$error, &$campaign_cd) {
	$error = false;

	// �����ڡ��󥳡��ɥ����å�
	$sql = sprintf("SELECT cmp_campaign_cd"
				. " FROM t_campaign"
				. " WHERE cmp_import_cd=%s OR cmp_mail_follow_cd=%s",
			sql_number($data[$col['�����ȥ�']['�ã�̾��']]),
			sql_number($data[$col['�����ȥ�']['�ã�̾��']]));
	$campaign_cd = db_fetch1($sql);
	if ($campaign_cd == '') {
		message("Warning: {$line}���ܤ�\"�ã�̾��\"��{$data[$col['�����ȥ�']['�ã�̾��']]}�ˤϥ����ڡ���ޥ�������Ͽ����Ƥ��ʤ�����̵�뤷�ޤ�����");
		return false;
	}

	// �ּ�̾���ּ拾���ɼ���
	$car_model_name = trans_car_name($data[$col['�����;���']['������˾��̾']], $data[$col['��������']['����Ź������']]);
	$car_model_cd = get_car_model_cd($car_model_name);
	if (!$car_model_cd) {
		if ($data[$col['�����;���']['�������']] == 'C') {
			$car_model_name = trans_car_name($data[$col['�����;���']['������']], $data[$col['��������']['����Ź������']]);
			$car_model_cd = get_car_model_cd($car_model_name);
		}
	}

	// ��Ͽ�Ѥߥ����å�
	$mail_addr = $data[$col['�����;���']['E-mail���ɥ쥹']];
	$sql = sprintf("SELECT COUNT(*)"
			. " FROM t_user_regist"
			. " WHERE urg_customer_cd=%s",
			sql_char($data[$col['�����;���']['������CD']]));
	if (db_fetch1($sql) != 0) {
		message("Warning: {$line}���ܤ�\"������CD\"�Ϥ��Ǥ���Ͽ����Ƥ��뤿��̵�뤷�ޤ�����");
		return false;
	} else {
		// �᡼�륢�ɥ쥹��ʣ�����å�
		if ($mail_addr != '') {
			$sql = sprintf("SELECT urg_campaign_cd,umg_kounyu_kibou_car"
					. " FROM t_user_personal"
					. " JOIN t_user_manage ON umg_user_id=ups_user_id"
					. " JOIN t_user_regist ON urg_user_id=ups_user_id"
					. " WHERE ups_mail_addr=%s",
					sql_char($mail_addr));
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);

				if ($fetch->urg_campaign_cd == $campaign_cd || check_car_model($fetch->umg_kounyu_kibou_car) == check_car_model($car_model_name)) {
					message("Warning: {$line}���ܤ�\"E-mail���ɥ쥹\"��{$mail_addr}�ˤϤ��Ǥ���Ͽ����Ƥ��뤿��̵�뤷�ޤ�����");
					return false;
				}
			}
		}
	}

	// �ǡ���¸�ߤ���ӷ��������å�
	$size = count($layout);
	for ($i = 0; $i < $size; $i++) {
		$l = &$layout[$i];
		if ($l->dtl_input == DBTRUE) {
			$index = $l->dtl_column_no - 1;
			if ($data[$index] == '') {
				if ($l->dtl_must == DBTRUE) {
					message("Error: {$line}���ܤ�\"{$l->dtl_group_name}-{$l->dtl_item_name}\"�˥ǡ���������ޤ���");
					$error = true;
				}
			} else {
				switch ($l->dtl_attribute) {
				case '9':
					if (!is_numeric($data[$index])) {
						message("Error: {$line}���ܤ�\"{$l->dtl_group_name}-{$l->dtl_item_name}\"�������ǤϤ���ޤ���");
						$error = true;
					}
					break;
				case 'N':
				case 'X':
					break;
				}
				if (mb_strlen($data[$index]) > $l->dtl_max_length) {
					message("Error: {$line}���ܤ�\"{$l->dtl_group_name}-{$l->dtl_item_name}\"��{$l->dtl_max_length}ʸ����Ķ���Ƥ��ޤ���");
					$error = true;
				}
			}
		}
	}

	// �᡼�륢�ɥ쥹�����å�
	if ($mail_addr != '') {
		if (!check_mail_addr($mail_addr)) {
			message("Error: {$line}���ܤ�\"E-mail���ɥ쥹\"��{$mail_addr}�ˤ�����������ޤ���");
			$error = true;
		} else if (check_keitai_domain($mail_addr)) {
			message("Error: {$line}���ܤ�\"E-mail���ɥ쥹\"��{$mail_addr}�ˤϷ��ӥ��ɥ쥹�Ǥ���");
			$error = true;
		}
	}

	// �ּ���Ͽ�����å�
	if (!$car_model_cd) {
		message("Error: {$line}���ܤ�\"������˾��̾\"��{$car_model_name}�ˤϼּ�ޥ�������Ͽ����Ƥ��ޤ���");
		$error = true;
	}

	// ����Ź��������Ͽ�����å�
	$dealer_cd = $data[$col['��������']['����Ź������']];
	if ($dealer_cd != '') {
		$sql = sprintf("SELECT COUNT(*) FROM t_dealer WHERE dlr_dealer_cd=%s", sql_char($dealer_cd));
		if (db_fetch1($sql) == 0) {
			message("Error: {$line}���ܤ�\"����Ź������\"��{$dealer_cd}�ˤ�����Ź�ޥ�������Ͽ����Ƥ��ޤ���");
			$error = true;
		} else {
			// �ּ��갷�������å�
			if ($car_model_cd) {
				$sql = sprintf("SELECT COUNT(*) FROM t_dealer_car WHERE dlc_dealer_cd=%s AND dlc_car_model_cd=%s",
						sql_char($dealer_cd),
						sql_number($car_model_cd));
				if (db_fetch1($sql) == 0) {
					message("Error: {$line}���ܤ�\"������˾��̾\"��{$car_model_name}�ˤ�����Ź��{$dealer_cd}�ˤμ�갷���ּ�ǤϤ���ޤ���");
					$error = true;
				}
			}
		}
	}

	return true;
}

// ��Ͽ������¸
function insert_user_regist($error, &$data, &$col, $campaign_cd) {
	$rec['urg_customer_cd'] = sql_char($data[$col['�����;���']['������CD']]);
	$rec['urg_dealer_cd'] = sql_char($data[$col['��������']['����Ź������']]);
	$rec['urg_regist_date'] = 'current_timestamp';
	if ($error) {
		$rec['urg_handover_date'] = 'current_timestamp';
		$rec['urg_follow_date'] = '(SELECT fld_send_date FROM t_follow_date WHERE fld_send_date>current_timestamp ORDER BY fld_send_date LIMIT 1)';
	}
	$rec['urg_campaign_cd'] = sql_number($campaign_cd);
	db_insert('t_user_regist', $rec);
	return get_current_seq('t_user_regist', 'urg_user_id');
}

// �����������¸
function insert_user_login($user_id) {
	$rec['ulg_user_id'] = sql_number($user_id);
	$rec['ulg_login_id'] = sql_char(create_login_id());
	$rec['ulg_password'] = sql_char(create_password());
	db_insert('t_user_login', $rec);
}

// ���ơ�����������¸
function insert_user_status($user_id) {
	$rec['ust_user_id'] = sql_number($user_id);
	$rec['ust_status'] = 1;
	db_insert('t_user_status', $rec);
}

// �Ŀ;���
function insert_user_personal($user_id, &$data, &$col) {
	$col1 = &$col['�����;���'];
	$rec['ups_user_id'] = sql_number($user_id);
	$rec['ups_update_date'] = 'current_timestamp';
	$rec['ups_name_kanji'] = sql_char($data[$col1['������̾�ʴ�����']]);
	$rec['ups_name_kana'] = sql_char($data[$col1['������̾�ʥ��ʡ�']]);
	$rec['ups_zip_cd'] = sql_char($data[$col1['͹���ֹ�']]);
	$rec['ups_address0'] = sql_char($data[$col1['����0']]);
	$rec['ups_address1'] = sql_char($data[$col1['����1']]);
	$rec['ups_address2'] = sql_char($data[$col1['����2']]);
	$rec['ups_tel_no'] = sql_char($data[$col1['�����ֹ�']]);
	$rec['ups_keitai_no'] = sql_char($data[$col1['���������ֹ�']]);
	$rec['ups_mail_addr'] = sql_char($data[$col1['E-mail���ɥ쥹']]);
	$rec['ups_sex_cd'] = sql_char($data[$col1['���̶�ʬ']]);
	$rec['ups_age'] = is_numeric($data[$col1['ǯ��']]) ? sql_number($data[$col1['ǯ��']]) : 'null';
	db_insert('t_user_personal', $rec);
}

// ��������
function insert_user_manage($user_id, &$data, &$col) {
	$col1 = &$col['��������'];
	$col2 = &$col['�����;���'];
	$col3 = &$col['�̎����Ďގʎގ�������'];
	$rec['umg_user_id'] = sql_number($user_id);
	$rec['umg_customer_cd'] = sql_char($data[$col2['������CD']]);
	$rec['umg_dealer_cd'] = sql_char($data[$col1['����Ź������']]);
	$rec['umg_dealer_name'] = sql_char($data[$col1['����Ź̾']]);
	$rec['umg_oubo_shubetsu'] = sql_char($data[$col2['�������']]);
	$rec['umg_jisshi_kai'] = sql_number($data[$col2['�»ܲ�']]);
	$rec['umg_regist_ym'] = sql_char($data[$col2['������Ͽǯ��']]);
	$rec['umg_oubo_date'] = sql_char($data[$col2['����������Ź����']]);
	$rec['umg_jisha_tasha_kubun'] = sql_char($data[$col2['���ҡ�¾�Ҷ�ʬ']]);
	$rec['umg_follow_kubun'] = sql_char($data[$col2['�ե�����ʬ']]);
	$rec['umg_tantou_shop_cd'] = sql_char($data[$col3['ô��Ź��CD']]);
	$rec['umg_tantou_shop_name'] = sql_char($data[$col3['ô��Ź��̾��']]);
	$rec['umg_tantou_staff_name'] = sql_char($data[$col3['ô�������å�̾']]);

	$car_model_name = trans_car_name($data[$col2['������˾��̾']], $data[$col1['����Ź������']]);
	$car_model_cd = get_car_model_cd($car_model_name);
	$rec['umg_kounyu_kibou_car'] = sql_char($car_model_name);
	if ($car_model_cd == 0) {
		$car_model_name = trans_car_name($data[$col2['������']], $data[$col1['����Ź������']]);
		$car_model_cd = get_car_model_cd($car_model_name);
		$rec['umg_kounyu_kibou_car'] = sql_char($car_model_name);
	}

	$rec['umg_car_model_cd'] = sql_number($car_model_cd);
	db_insert('t_user_manage', $rec);
}

// ����ͭ����
function insert_user_car($user_id, &$data, &$col) {
	$col1 = &$col['��ͭ����'];
	$rec['ucr_user_id'] = sql_number($user_id);
	$rec['ucr_car_maker'] = sql_char($data[$col1['��ξ�᡼����']]);
	$rec['ucr_car_model'] = sql_char($data[$col1['�ּ�̾']]);
	$rec['ucr_jikai_shaken'] = sql_char($data[$col1['����ָ�']]);
	$rec['ucr_dealer_cd'] = sql_char($data[$col1['��������Ź������']]);
	$rec['ucr_dealer_type'] = sql_char($data[$col1['��������Ź����']]);
	$rec['ucr_dealer_name'] = sql_char($data[$col1['��������Ź̾']]);
	db_insert('t_user_car', $rec);
}

// �����ڡ���������
function insert_user_campaign($user_id, &$data, &$col) {
	$col1 = &$col['�����ڡ�����祢�󥱡���'];
	$rec['ucm_user_id'] = sql_number($user_id);
	$rec['ucm_raiten_mokuteki'] = sql_char($data[$col1['��Ź��Ū']]);
	$rec['ucm_sonota_riyuu'] = sql_char($data[$col1['����¾��ͳ']]);
	$rec['ucm_cp_ninchi'] = sql_char($data[$col1['CPǧ�Τ���¾����']]);
	$rec['ucm_campaign_ninchi'] = sql_char($data[$col1['�����ڡ���ǧ��']]);
	$rec['ucm_jouhou_teikyou_kyodaku'] = sql_char($data[$col1['�����󶡵���']]);
	db_insert('t_user_campaign', $rec);
}

// ����ݡ��ȥǡ������ꥸ�ʥ�ǡ���
function insert_user_data($user_id, &$data) {
	$size = count($data);

	for ($i = 0; $i < $size; $i++) {
		if ($data[$i] != '') {
			$rec['udt_user_id'] = sql_number($user_id);
			$rec['udt_column_no'] = sql_number($i + 1);
			$rec['udt_value'] = sql_char($data[$i]);
			db_insert('t_user_data', $rec);
		}
	}
}

// ��å���������
function message($text) {
	echo $text, "\n";
	if ($_SERVER['argc'] == 0)
		echo '<br>';
}

// �������ͥ��̤μּ�̾���Ѵ�
function trans_car_name($car_model_name, $dealer_cd) {
	$sql = sprintf("SELECT scc_car_model_name2 FROM t_sales_channel_car JOIN t_dealer ON dlr_sales_channel_cd=scc_sales_channel_cd WHERE scc_car_model_name=%s AND dlr_dealer_cd=%s",
			sql_char($car_model_name),
			sql_char($dealer_cd));
	return db_fetch1($sql, $car_model_name);
}

// �ּ拾���ɼ���
function get_car_model_cd($car_model_name) {
	$sql = sprintf("SELECT crm_car_model_cd FROM t_car_model WHERE crm_car_model_name=%s",
			sql_char($car_model_name));
	return db_fetch1($sql, 0);
}

// Ʊ��ּ�����å�
function check_car_model($car_model_name) {
	switch ($car_model_name) {
	case '�ң��֣���':
	case '�ң��֣���':
		return '�ң��֣�';
	case '����ե����ɣ�':
	case '����ե����ɣ�':
		return '����ե�����';
	case '�����ƥ��ޣ�':
	case '�����ƥ��ޣ�':
		return '�����ƥ���';
	case '���롼������':
	case '���롼������':
		return '���롼����';
	default:
		return $car_model_name;
	}
}

// ���ӥ��ɥ쥹�����å�
function check_keitai_domain($mail_addr) {
	$sql = sprintf("SELECT COUNT(*) FROM t_keitai_domain WHERE %s LIKE '%%'||kdm_keitai_domain",
			sql_char($mail_addr));
	return db_fetch1($sql) != 0;
}

// ���顼�����
function err_post_proc() {
	// vacuum
	vacuum('t_user_data');
	vacuum('t_user_login');
	vacuum('t_user_personal');
	vacuum('t_user_manage');
	vacuum('t_user_car');
	vacuum('t_user_campaign');
	vacuum('t_user_status');

	// �������󥹤��᤹
	$sql = "select setval('t_user_regist_urg_user_id_seq', coalesce(max(urg_user_id)+1,1),false) from t_user_regist";
	db_exec($sql);
}

// vacuum����
function vacuum($table) {
	$sql = "VACUUM $table";
	db_exec($sql);
}
?>