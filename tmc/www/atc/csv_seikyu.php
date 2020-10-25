<?
/******************************************************
' System :�֤���������� �������������ӥ��ץ����ڡ����̳���ѥڡ���
' Content:����Ź�̷������CSV����
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");

// ���ϥѥ�᡼��
$sales_channel_cd = $_GET['sales_channel_cd'];
$ym = $_GET['ym'];
$campaign_cd = $_GET['campaign_cd'];

// CSV���Ͻ���
prepare_csv('����Ź�̷������CSV�ǡ���.csv');

// �إå�����
set_csv($csv, '����Ź̾');
set_csv($csv, '�᡼��ե����»ܷ��');
set_csv($csv, '������顼���');
output_csv($csv);

// ��о��
if ($sales_channel_cd != '')
	and_join($where, "dlr_sales_channel_cd=$sales_channel_cd");
if ($ym != '')
	and_join($where, "to_char(urg_follow_date, 'YYYYMM')='$ym'");
if ($campaign_cd != '')
	and_join($where, "urg_campaign_cd='$campaign_cd'");
and_join($where, 'urg_follow_date<=current_timestamp');
$where = "WHERE $where";

$sql = "SELECT dlr_dealer_cd,dlr_dealer_name"
		. ",COUNT(CASE WHEN NOT (ust_user_id IS NULL OR urs_rank IS NOT NULL AND urs_rank='L') THEN 1 END) AS count_jisshi"
		. ",COUNT(CASE WHEN ust_user_id IS NULL OR urs_rank='L' THEN 1 END) AS count_err"
		. ",COUNT(CASE WHEN NOT (ust_user_id IS NULL OR urs_rank IS NOT NULL AND urs_rank='L') AND NOT EXISTS (SELECT * FROM t_comm_log WHERE cml_user_id=urg_user_id AND cml_err_mail_flag=false) THEN 1 END) AS count_futatsu"
		. " FROM t_dealer"
		. " JOIN t_user_regist ON urg_dealer_cd=dlr_dealer_cd"
		. " LEFT JOIN t_user_status ON ust_user_id=urg_user_id"
		. " LEFT JOIN t_user_result ON urs_user_id=urg_user_id"
		. " $where"
		. " GROUP BY dlr_dealer_cd,dlr_dealer_name"
		. " ORDER BY dlr_dealer_cd";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$csv = '';
	set_csv($csv, $fetch->dlr_dealer_name);
	set_csv($csv, $fetch->count_jisshi - $fetch->count_futatsu);
	set_csv($csv, $fetch->count_err);
	output_csv($csv);
}
exit;
?>