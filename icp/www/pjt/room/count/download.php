<?
/******************************************************
' System :ICPプロジェクトページ
' Content:発言集計ダウンロード
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$job_id = $_SESSION[SCD]['job_id'];

// プロジェクト名、ジョブ名、ジョブ詳細を取得
$sql = "SELECT pj_pjt_cd,pj_name,jb_job_cd,jb_name,jb_description"
		. " FROM t_job"
		. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
		. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

// 出力ファイル名
prepare_csv("count_{$fetch->pj_pjt_cd}_{$fetch->jb_job_cd}.csv");

// プロジェクト名出力
set_csv($csv, '■プロジェクト名');
set_csv($csv, $fetch->pj_name);
output_csv($csv);

// ジョブ名出力
set_csv($csv, '■ジョブ名');
set_csv($csv, $fetch->jb_name);
output_csv($csv);

// ジョブ詳細出力
set_csv($csv, '■ジョブ詳細');
set_csv($csv, $fetch->jb_description);
output_csv($csv);

// 改行
output_csv($csv);

// ヘッダ出力
$csv = 'ジョブ参加ID,ニックネーム,性別,年齢,未既婚,居住地,組織ID,組織名,組織メンバーID,総発言,発言日数';
output_csv($csv);

// 発言集計CSV出力
$sb1 = "SELECT rm_member_id,COUNT(*) AS s_count1,COUNT(DISTINCT(date_trunc('day', rm_date))) AS s_count2"
		. " FROM l_remark"
		. " WHERE rm_job_id=$job_id AND rm_remark_type=3 AND rm_status=1"
		. " GROUP BY rm_member_id";
$sql = "SELECT pm_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pm_sex_cd,DATE_PART('Y',AGE(pm_birthday)) AS pm_age,pm_mikikon_cd,pm_address,pm_org_id,or_name,pm_org_member_id,s_count1,s_count2"
		. " FROM t_job_member"
		. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
		. " JOIN m_org ON or_org_id=pm_org_id"
		. " LEFT JOIN ($sb1) sb1 ON rm_member_id=jm_member_id"
		. " WHERE jm_job_id=$job_id AND jm_status=1"
		. " ORDER BY pm_member_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	set_csv($csv, $fetch->pm_member_id);
	set_csv($csv, $fetch->pm_nickname);
	set_csv($csv, $fetch->pm_sex_cd);
	set_csv($csv, $fetch->pm_age);
	set_csv($csv, $fetch->pm_mikikon_cd);
	set_csv($csv, $fetch->pm_address);
	set_csv($csv, $fetch->pm_org_id);
	set_csv($csv, $fetch->or_name);
	set_csv($csv, $fetch->pm_org_member_id);
	set_csv($csv, number_format($fetch->s_count1));
	set_csv($csv, number_format($fetch->s_count2));

	output_csv($csv);
}

exit;
?>