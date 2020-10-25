<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:ポイント失効処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/define.php");
include("$inc/mail.php");
include("$inc/batch_log.php");
include("$inc/point.php");
include("$inc/format.php");

define('FILE_NAME', __FILE__);

// 二重起動チェック
if (!dup_check('lock_point'))
	exit;

// 最大実行時間（無制限）
set_time_limit(0);

//db_begin_trans();

// ポイント失効処理
point_expire();

//db_commit_trans();

exit;

// ポイント失効処理
function point_expire() {
	$sql = "SELECT mn_monitor_id,mp_curr_point,COALESCE(SUM(lp_point),0)+mp_haimail_point AS lp_valid_point"
			. " FROM t_monitor"
			. " JOIN t_monitor_point ON mp_monitor_id=mn_monitor_id"
			. " LEFT JOIN l_point ON lp_member_kind=1 AND lp_member_id=mn_monitor_id AND lp_point>0 AND (lp_expire_date>current_date OR lp_expire_date IS NULL)"
			. " WHERE mn_status<>9"
			. " GROUP BY mn_monitor_id,mp_curr_point,mp_haimail_point";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($fetch->mp_curr_point > $fetch->lp_valid_point)
			monitor_point($fetch->mn_monitor_id, PT_EXPIRE, $fetch->lp_valid_point - $fetch->mp_curr_point);
	}

	batch_log(0, FILE_NAME, "ポイントの失効処理を行いました。");
}
?>