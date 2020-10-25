<?
/******************************************************
' System :Eco-footprint �Хå�����
' Content:�����
'******************************************************/

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/rank.php");
include("$inc/cnv_unit.php");
include("$inc/cnv_eng_co2.php");
include("$inc/inp_close.php");
include("$inc/co2_class.php");
include("$inc/point.php");
include("$inc/batch_log.php");

define('FILE_NAME', __FILE__);

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

// ���ߤ�ǯ�����
$date = date('Y-m-01');

// �¹ԺѤߥ����å�
$fetch = get_system_info('sy_batch_month');
if (strtotime($fetch->sy_batch_month) >= strtotime($date))
	exit;

// �������
$date2 = add_date($date, 0, -1, 0);

// �ȥ�󥶥�����󳫻�
db_begin_trans();

// ̤��λ���ϥǡ����������ߥå�
$sql = "SELECT mb_seq_no,mb_sol_flag,bd_seq_no FROM t_member LEFT JOIN t_base_data ON mb_seq_no=bd_mb_seq_no AND"
		. " bd_month='$date2' WHERE mb_step=3 AND (bd_commit_flag<>1 OR bd_commit_flag IS NULL)";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mb_seq_no = $fetch->mb_seq_no;
	$bd_seq_no = $fetch->bd_seq_no;

	// ���ϥǡ�����̵�����Ϻ������ƶ������ߥåȥե饰��Ω�Ƥ�
	if (!$bd_seq_no) {
		$bd_seq_no = insert_base_data($mb_seq_no, $date2);
	// ������϶������ߥåȥե饰��Ω�Ƥ�
	} else {
//		$sql = "UPDATE t_base_data SET bd_auto_commit=1 where bd_mb_seq_no=" . sql_number($mb_seq_no) . " AND bd_month=" . sql_date($date2);
		$sql = "UPDATE t_base_data SET bd_auto_commit=1 where bd_mb_seq_no=" . sql_number($mb_seq_no) . " AND bd_month=" . sql_date($date2)
			. " AND bd_auto_commit IS NULL";
		db_exec($sql);
	}

	// Sol���֤ξ�硢̤���Ϲ��ܤ�ʿ���ͤ򥻥å�
	if ($fetch->mb_sol_flag == 1)
		set_average_data($bd_seq_no);

	// �����������
	$_SESSION['ss_seq_no'] = $mb_seq_no;
	inp_close($bd_seq_no);
}

// CO2�ӽ��̥��饹����

// �����ƥ�ޥ����˷�Хå�����ǯ��򥻥å�
$sql = "UPDATE m_system SET sy_batch_month='$date' WHERE sy_system_id=1";
db_exec($sql);

// ���顼�����å�
$err_msg = db_errormessage();
if ($err_msg) {
	db_rollback();

	// �Хå������񤭹��ߡʥ��顼��
	$desc = "������ǥ��顼��ȯ�����ޤ�����($err_msg)";
	batch_log(9, FILE_NAME, $desc);
	exit;
} else {
	// �Хå������񤭹��ߡ������
	$desc = "�������λ���ޤ�����";
	batch_log(0, FILE_NAME, $desc);
}

// �ȥ�󥶥�����󥳥ߥå�
db_commit_trans();
exit;

// ���ϥǡ���������All0��
function insert_base_data($mb_seq_no, $date) {
	$sql = sprintf("INSERT INTO t_base_data (bd_mb_seq_no,bd_month,bd_auto_commit) values (%s,%s,1)",
			sql_number($mb_seq_no),
			sql_date($date));
	db_exec($sql);

	return get_currval('t_base_data_bd_seq_no_seq');
}

// ʿ���ͥ��å�
function set_average_data($bd_seq_no) {
}
?>