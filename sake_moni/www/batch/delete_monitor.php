<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥����Хå�����
' Content:�桼�������������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");

define('FILE_NAME', __FILE__);

//�ե���������ֹ�
define('COL_NET_ID', 0);		// �����ɣ�

//�ޥ���ʸ����
define('MASK_NAME1', 		'������');
define('MASK_NAME2', 		'������');
define('MASK_NAME1_KANA', 	'������');
define('MASK_NAME2_KANA', 	'������');
define('MASK_MAIL_ADDR', 	'dummy_addr');
define('MASK_MAIL_ADDR_LOW','dummy_addr');



// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

message("���������ʪ���󥱡��ȥ�˥��������ƥࡡ�桼������ץ����");



//***** ����ݡ��ȥե�����̾�μ���
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
	$import_file = $_FILES['delete_import_file']['tmp_name'];
	$force = $_POST['force'];
	$check = $_POST['check'];
}
if ($import_file == '') {
	message('����ѥ���ݡ��ȥե�����λ��꤬����ޤ���');
	exit;
}

if (($fp = fopen($import_file, 'r')) == false) {
	message('�ե�����Υ����ץ�˼��Ԥ��ޤ�����');
	exit;
}



//***** �ե�������ɤ߹���
$line_no = 0;
$ok_count = 0;
$ng_count = 0;
$data_ary = array();

while ($data = fgetcsv($fp, 10000, ",")) {

	//ʸ�������ɤ�EUC-JP�ˤ���
	mb_convert_variables('EUC-JP', 'SJIS', $data);
	$line_no++;

	if ($data[0] != '') {
		$err = null;

		//���顼�����å�
		//if (!check_num($data[COL_NET_ID]))
			//$err[] = '�����ɣĤ������ǤϤ���ޤ���';

		//���顼ɽ��
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



//***** �ģ���Ͽ
$import_cont = 0;

if ($force || (!$check && !$err_flag)) {
	db_begin_trans();

	foreach ($data_ary as $data) {
		if ($data['err'] == null) {
			// update��κ���
			$rec['mn_status']		= sql_number(9);
//			$rec['mn_name1']		= sql_char(MASK_NAME1);
//			$rec['mn_name2']		= sql_char(MASK_NAME1);
//			$rec['mn_name1_kana']	= sql_char(MASK_NAME1_KANA);;
//			$rec['mn_name2_kana']	= sql_char(MASK_NAME2_KANA);
//			$rec['mn_mail_addr']	= sql_char(MASK_MAIL_ADDR);
//			$rec['mn_mail_addr_low']= sql_char(MASK_MAIL_ADDR_LOW);
			
			//where��κ���
			$where = 'mn_net_id = ' . sql_char($data[COL_NET_ID]);

			//�ǡ������������
			db_update('t_monitor', $rec, $where);

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



//***** ���ɽ��
message('');
message("������ǡ�����{$ok_count}��");
message("���顼�ǡ�����{$ng_count}��");

message('');
if ($import_cont != 0)
	message("{$import_cont}�������ޤ�����");
else
	message("����ϹԤ��ޤ���Ǥ�����");

exit;

//-----------------------------
// ��å���������
//-----------------------------
function message($text) {
	echo $text, "\n";
	if ($_SERVER['argc'] == 0)
		echo '<br>';
}
?>