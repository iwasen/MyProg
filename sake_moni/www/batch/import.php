<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥����Хå�����
' Content:�桼������ݡ��Ƚ���
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");

define('FILE_NAME', __FILE__);

define('COL_KEY_ID', 0);		// �����ɣ�
define('COL_NAME1', 1);			// ��
define('COL_NAME2', 2);			// ̾
define('COL_SEX', 3);				// ����
define('COL_MIKIKON', 4);		// ̤����
define('COL_BIRTHDAY', 5);	// ��ǯ����
define('COL_CHILD', 6);			// �Ҷ���̵ͭ
define('COL_SHOKUGYOU', 7);	// ����
define('COL_MAIL_ADDR', 8);	// �᡼�륢�ɥ쥹
define('COL_PASSWORD', 9);	// �ѥ����
define('COL_NET_ID', 10);		// ��������ID��imiID
define('COL_RESEARCH', 11);	// ��ϢĴ���ؤλ��ò�ǽFlg
define('COL_NET_FLAG', 12);	// ��������/imi���̥ե饰
define('COL_CHAIN', 13);		// ��������
define('COL_ZIP', 14);			// ͹���ֹ�
define('COL_ADDRESS1', 15);	// �ｻ�ϰ�
define('COL_ADDRESS2', 16);	// ���ꣲ�ʻԶ�Į¼�����ϡ��ӥ�̾��

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

message("���������ʪ���󥱡��ȥ�˥��������ƥࡡ�桼������ݡ��ȥץ����");

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

if (($fp = fopen($import_file, 'r')) == false) {
	message('�ե�����Υ����ץ�˼��Ԥ��ޤ�����');
	exit;
}

// ��Ͽ�ѤߤΥ᡼�륢�ɥ쥹�����
// $sql = "SELECT mn_mail_addr_low FROM t_monitor WHERE mn_status<>9";
$sql = "SELECT mn_mail_addr_low, count(*) as C FROM t_chain LEFT JOIN t_monitor ON mn_monitor_id = dc_monitor_id WHERE mn_status<>9 GROUP BY mn_mail_addr_low";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
//	$mail_addr_ary[$fetch->mn_mail_addr_low] = true;
	$mail_addr_ary[$fetch->mn_mail_addr_low] = $fetch->c ;
}

$line_no = 0;
$ok_count = 0;
$ng_count = 0;
$data_ary = array();

$data = fgetcsv($fp, 10000, ",");
while ($data = fgetcsv($fp, 10000, ",")) {
	mb_convert_variables('EUC-JP', 'SJIS', $data);
	$line_no++;

//	if ($data[0] != '') {
	if ($data[1] != '') {
		$err = null;

//		if (!check_num($data[COL_KEY_ID]))
//			$err[] = '�����ɣĤ������ǤϤ���ޤ���';

		if ($data[COL_NAME1] == '')
			$err[] = '��������ޤ���';

		if (!check_num($data[COL_SEX], 1, 2))
			$err[] = '���̤�����������ޤ���';

		if (!check_num($data[COL_MIKIKON], 1, 2))
			$err[] = '̤����������������ޤ���';

		if (!check_date($data[COL_BIRTHDAY]))
			$err[] = '��ǯ����������������ޤ���';

		if (!check_num($data[COL_SHOKUGYOU]))
			$err[] = '���Ȥ�����������ޤ���';

		if (!check_mail_addr($data[COL_MAIL_ADDR]))
			$err[] = '�᡼�륢�ɥ쥹������������ޤ���';
		else {
			$mail_addr = strtolower($data[COL_MAIL_ADDR]);
//			if ($mail_addr_ary[$mail_addr])
			if ($mail_addr_ary[$mail_addr] == 2)
//				$err[] = "�᡼�륢�ɥ쥹�Ͻ�ʣ���Ƥ��ޤ���({$mail_addr})";
				$err[] = "�᡼�륢�ɥ쥹�ϴ���2�����������Ͽ����Ƥ��ޤ���({$mail_addr})";
			else
//				$mail_addr_ary[$mail_addr] = true;
				++$mail_addr_ary[$mail_addr];
		}

		if ($data[COL_PASSWORD] == '')
			$err[] = '�ѥ���ɤ�����ޤ���';

		if ($data[COL_NET_ID] == '')
			$err[] = '��������ID��imiID������ޤ���';

		if (!check_num($data[COL_RESEARCH], 1, 2))
			$err[] = '��ϢĴ���ؤλ��ò�ǽFlg������������ޤ���';

		if (!check_num($data[COL_NET_FLAG], 1, 2))
			$err[] = '��������/imi���̥ե饰������������ޤ���';

		if ($data[COL_CHAIN] == '')
			$err[] = '������������������ޤ���';

		if(!is_numeric($data[COL_ADDRESS1]))
			$data[COL_ADDRESS1] = get_area_cd($data[COL_ADDRESS1]);
		if ($data[COL_ADDRESS1] == '')
			$err[] = '�ｻ�ϰ褬����������ޤ���';

		if ($err) {
			foreach ($err as $e)
				message("{$line_no}���ܡʥ����ɣ�={$data[0]}�ˤ�{$e}");

			$err_flag = true;
			$ng_count++;
		} else
			$ok_count++;

		$data['err'] = $err;
		$data_ary[] = $data;
	}
}

$import_cont = 0;

if ($force || (!$check && !$err_flag)) {
	db_begin_trans();

	foreach ($data_ary as $data) {
		if ($data['err'] == null) {
			// ��˥���������Ͽ
			$rec['mn_net_kind'] = sql_number($data[COL_NET_FLAG]);
			$rec['mn_net_id'] = sql_char($data[COL_NET_ID]);
			$rec['mn_name1'] = sql_char($data[COL_NAME1]);
			$rec['mn_name2'] = sql_char($data[COL_NAME2]);
			$rec['mn_birthday'] = sql_date($data[COL_BIRTHDAY]);
			$rec['mn_sex'] = sql_number($data[COL_SEX]);
			$rec['mn_mikikon'] = sql_number($data[COL_MIKIKON]);
			$rec['mn_mail_addr'] = sql_char($data[COL_MAIL_ADDR]);
			$rec['mn_mail_addr_low'] = sql_char(strtolower($data[COL_MAIL_ADDR]));
			$rec['mn_password'] = sql_char($data[COL_PASSWORD]);
			$rec['mn_jitaku_zip'] = sql_char($data[COL_ZIP]);
			$rec['mn_jitaku_area'] = sql_number($data[COL_ADDRESS1]);
			$rec['mn_jitaku_addr2'] = sql_char($data[COL_ADDRESS2]);
			$rec['mn_shokugyou_cd'] = sql_number($data[COL_SHOKUGYOU]);
			$rec['mn_research'] = sql_number($data[COL_RESEARCH]);
			$rec['mn_child'] = sql_number($data[COL_CHILD]);
			db_insert('t_monitor', $rec);
			$monitor_id = get_currval('t_monitor_mn_monitor_id_seq');

			// �������������Ͽ
			$chain_ary = explode(',', $data[COL_CHAIN]);
			if (is_array($chain_ary)) {
				foreach ($chain_ary as $chain) {
					if ($chain != '') {
						$sql = "SELECT count(*) FROM t_chain WHERE dc_monitor_id=" . sql_number($monitor_id) . " AND dc_chain_cd=" . sql_number($chain);
						if (db_fetch1($sql) == 0) {
							$rec['dc_monitor_id'] = sql_number($monitor_id);
							$rec['dc_chain_cd'] = sql_number($chain);
							db_insert('t_chain', $rec);
						}
					}
				}
			}

			// �ݥ���Ⱦ���쥳���ɺ���
			$rec['mp_monitor_id'] = sql_number($monitor_id);
			db_insert('t_monitor_point', $rec);

			if (db_errormessage() != '')
				break;

			$import_cont++;
		}
	}

	if (db_errormessage() == '')
		db_commit_trans();
	else {
		db_rollback();
		$import_cont = 0;
	}
}

// ���ɽ��
message('');
message("������ǡ�����{$ok_count}��");
message("���顼�ǡ�����{$ng_count}��");

message('');
if ($import_cont != 0)
	message("{$import_cont}�磻��ݡ��Ȥ��ޤ�����");
else
	message("����ݡ��ȤϹԤ��ޤ���Ǥ�����");

exit;

// ��ƻ�ܸ������ɼ���
function get_area_cd($area) {
	$sql = "SELECT ar_area_cd FROM m_area WHERE ar_area_name=" . sql_char($area);
	return db_fetch1($sql);
}

// ��å���������
function message($text) {
	echo $text, "\n";
	if ($_SERVER['argc'] == 0)
		echo '<br>';
}
?>
