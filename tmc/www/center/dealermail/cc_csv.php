<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����Ź�᡼�륯��å�������CSV����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
//include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/csv.php");

// ���ϥѥ�᡼��
$dealer_mail_id = $_GET['dealer_mail_id'];

// CSV���Ͻ���
prepare_csv('cc_$dealer_mail_id.csv');

// �إå�����
set_csv($csv, '����å�����');
set_csv($csv, 'URL̾��');
set_csv($csv, '������URL');
set_csv($csv, '�桼��ID');
set_csv($csv, 'IP���ɥ쥹');
output_csv($csv);

// ����å���������������
$sql = "SELECT ccl_date,clc_url_name,clc_jump_url,ccl_user_id,ccl_ip_adr"
		. " FROM t_dealer_mail_click"
		. " JOIN t_click_counter ON clc_cc_id=dmn_cc_id"
		. " JOIN t_click_counter_log ON ccl_cc_id=dmn_cc_id"
		. " WHERE dmn_dealer_mail_id=$dealer_mail_id"
		. " ORDER BY ccl_seq_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$csv = '';
	set_csv($csv, format_datetime($fetch->ccl_date));
	set_csv($csv, $fetch->clc_url_name);
	set_csv($csv, $fetch->clc_jump_url);
	set_csv($csv, $fetch->ccl_user_id);
	set_csv($csv, $fetch->ccl_ip_adr);
	output_csv($csv);
}
exit;
?>