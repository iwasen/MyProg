<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�ݥ���Ƚ���CSV
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/csv.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ���ϥѥ�᡼��
$from_date = "{$_POST['from_year']}/{$_POST['from_month']}/{$_POST['from_day']}";
$to_date = "{$_POST['to_year']}/{$_POST['to_month']}/{$_POST['to_day']}";
$net_id = $_POST['net_id'];

switch ($net_id) {
case '1':
	csv_kikasete('kikasete_point.csv', $from_date, $to_date);
	break;
case '2':
	csv_imi('imi_point.csv', $from_date, $to_date);
	break;
}
exit;

// �������ơ�net�ѥݥ���Ƚ���
function csv_kikasete($filename, $from_date, $to_date) {
	// �ݥ������Ϳ��
	$date = date('Y/m/d');

	// CSV���Ͻ���
	prepare_csv($filename);

	// CSV�إå�����
	$csv = '"ID",��Ϳ�ݥ���ȿ�,̤ʧ���ݥ���ȿ�,�ݥ������Ϳ��';
	output_csv($csv);

	// CSV�ǡ�������
	$sb1 = "SELECT lp_member_id AS lp_monitor_id,sum(lp_point) AS sum_point"
			. " FROM l_point"
			. " WHERE lp_date::date BETWEEN '$from_date' AND '$to_date'"
			. " GROUP BY lp_member_id";
	$sql = "SELECT mn_net_id,sum_point"
			. " FROM ($sb1) sb1"
			. " JOIN t_monitor ON mn_monitor_id=lp_monitor_id"
			. " WHERE mn_net_kind=1"
			. " ORDER BY mn_net_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		set_csv($csv, $fetch->mn_net_id);
		set_csv($csv, $fetch->sum_point);
		set_csv($csv, $fetch->sum_point);
		set_csv($csv, $date);

		output_csv($csv);
	}
}


// iMi�ͥå��ѥݥ���Ƚ���
function csv_imi($filename, $from_date, $to_date) {
	// �ݥ������Ϳ��
	$date = date('Y/m/d');

	// CSV���Ͻ���
	prepare_csv($filename);

	// CSV�إå�����
	$csv = '"ID",��Ϳ�ݥ���ȿ�,͹���ֹ�,����,��̾,�����ʧ����ˡ,�����ֹ�,�᡼�륢�ɥ쥹';
	output_csv($csv);

	// CSV�ǡ�������
	$sb1 = "SELECT lp_member_id AS lp_monitor_id,sum(lp_point) AS sum_point"
			. " FROM l_point"
			. " WHERE lp_date::date BETWEEN '$from_date' AND '$to_date'"
			. " GROUP BY lp_member_id";
	$sql = "SELECT mn_net_id,sum_point,mn_jitaku_zip,ar_area_name,mn_jitaku_addr2,mn_name1,mn_name2,mn_payment_type,mn_account_num,mn_mail_addr"
			. " FROM ($sb1) sb1"
			. " JOIN t_monitor ON mn_monitor_id=lp_monitor_id"
			. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
			. " WHERE mn_net_kind=2"
			. " ORDER BY mn_net_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		set_csv($csv, $fetch->mn_net_id);
		set_csv($csv, $fetch->sum_point);
		set_csv($csv, $fetch->mn_jitaku_zip);
		set_csv($csv, "$fetch->ar_area_name$fetch->mn_jitaku_addr2");
		set_csv($csv, "$fetch->mn_name1 $fetch->mn_name2");
		set_csv($csv, $fetch->mn_payment_type != '' ? $fetch->mn_payment_type : '2');
		set_csv($csv, $fetch->mn_account_num);
		set_csv($csv, $fetch->mn_mail_addr);

		output_csv($csv);
	}
}
?>