<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:退会者個人情報データ削除処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/define.php");
include("$inc/batch_log.php");

define('FILE_NAME', __FILE__);

// 最大実行時間（３分）
set_time_limit(180);

db_begin_trans();

erase_member_info();

db_commit_trans();

exit;

// 退会者個人情報データ削除
function erase_member_info() {
	$sql = "UPDATE t_monitor SET "
		. "mn_name1 = '退会済',"
		. "mn_name2 = '退会済'||mn_monitor_id,"
		. "mn_name1_kana = 'タイカイズミ',"
		. "mn_name2_kana = 'タイカイズミ',"
		. "mn_mail_addr = mn_monitor_id||'@taikai.xxxxxxx.co.jp',"
		. "mn_mail_addr_low = mn_monitor_id||'@taikai.xxxxxxx.co.jp',"
		. "mn_password = 'taikaizumi',"
		. "mn_jitaku_addr1 = '',"
		. "mn_jitaku_addr2 = '',"
		. "mn_jitaku_addr3 = '',"
		. "mn_jitaku_tel = '',"
		. "mn_jitaku_fax = '',"
		. "mn_urgent_addr = '',"
		. "mn_kinmu_addr1 = '',"
		. "mn_kinmu_addr2 = '',"
		. "mn_kinmu_addr3 = '',"
		. "mn_kinmu_tel = '',"
		. "mn_kinmu_fax = '',"
		. "mn_kinmu_name = '',"
		. "mn_kinmu_unit = '',"
		. "mn_kinmu_post = '',"
		. "mn_free_email = '',"
		. "mn_self_introduction = ''"
		. "WHERE mn_status = 9 AND date_trunc('month', COALESCE(mn_taikai_date, mn_update_date, mn_regist_date)) + interval '3 month' < date_trunc('month', current_date) AND mn_name1 <> '退会済'";
	$result = db_exec($sql);
	$n = pg_affected_rows($result);

	batch_log(0, FILE_NAME, "{$n}件の退会者個人情報を消去しました。");
}
?>
