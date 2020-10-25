<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/list.php");

prepare_csv('affiliate.csv');

$csv = '申請者モニターID,申請者メールアドレス,申請者パスワード,登録者モニターID,登録者メールアドレス,登録者氏名,登録者パスワード,登録者メールアドレス＠前,登録者メールアドレス＠後,登録者アンケート回数,登録者本登録日時,登録者ポイント状態,登録者ステータス';
output_csv($csv);

get_session_vars($pset, 'affiliate_reg', 'search', 'status', 'month');

// where条件
if ($affiliate_id)
	and_join($where, "af_affiliate_id=$affiliate_id");
else
	and_join($where, "af_affiliate_mgr_id=$affiliate_mgr_id");

// 検索テキスト
if ($search != '') {
	if (is_numeric($search))
		and_join($where, "m2.mn_monitor_id=$search");
	else
		and_join($where, "(m2.mn_mail_addr LIKE '%$search%' OR m2.mn_name1 LIKE '%$search%' OR m2.mn_name2 LIKE '%$search%')");
}

if ($status != '')
	and_join($where, "m2.mn_status=$status");

if ($month != '')
	and_join($where, "date_part('month',m2.mn_regist_date)=$month");

$sql = "SELECT m1.mn_monitor_id AS m1_monitor_id,m1.mn_mail_addr AS m1_mail_addr,m1.mn_password AS m1_password,m2.mn_monitor_id AS m2_monitor_id,m2.mn_mail_addr AS m2_mail_addr,m2.mn_password AS m2_password,m2.mn_name1 AS m2_name1,m2.mn_name2 AS m2_name2,m2.mn_regist_date AS m2_regist_date,m2.mn_status AS m2_status,afr_point,char_length(translate(COALESCE(mp_enq_history,''), '0', '')) AS ans_count"
		. " FROM t_affiliate"
		. " JOIN t_affiliate_reg ON afr_affiliate_id=af_affiliate_id"
		. " JOIN t_monitor m1 ON m1.mn_monitor_id=af_monitor_id"
		. " JOIN t_monitor m2 ON m2.mn_monitor_id=afr_monitor_id"
		. " JOIN t_monitor_point ON mp_monitor_id=afr_monitor_id"
		. " WHERE $where"
		. " ORDER BY m1.mn_monitor_id,m2.mn_monitor_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$mail_addr = explode('@', $fetch->m2_mail_addr);

	set_csv($csv, $fetch->m1_monitor_id);
	set_csv($csv, $fetch->m1_mail_addr);
	set_csv($csv, $fetch->m1_password);
	set_csv($csv, $fetch->m2_monitor_id);
	set_csv($csv, $fetch->m2_mail_addr);
	set_csv($csv, "$fetch->m2_name1 $fetch->m2_name2");
	set_csv($csv, $fetch->m2_password);
	set_csv($csv, $mail_addr[0]);
	set_csv($csv, $mail_addr[1]);
	set_csv($csv, $fetch->ans_count);
	set_csv($csv, format_datetime($fetch->m2_regist_date, '-'));
	set_csv($csv, $fetch->afr_point ? '有' : '無');
	set_csv($csv, decode_monitor_status($fetch->m2_status));

	output_csv($csv);
}
?>