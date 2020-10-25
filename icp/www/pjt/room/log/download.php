<?
/******************************************************
' System :ICP会議室ページ
' Content:発言ログダウンロード実行
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/csv.php");
include("$inc/format.php");
include("$inc/define.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$job_id = $_SESSION[SCD]['job_id'];
$disp_type = $_POST['disp_type'];
$file_name = $_POST['file_name'];

// プロジェクト名、ジョブ名、ジョブ詳細を取得
$sql = "SELECT pj_pjt_cd,pj_name,jb_job_cd,jb_name,jb_description"
		. " FROM t_job"
		. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
		. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

// 出力ファイル名
prepare_csv("{$file_name}_{$fetch->pj_pjt_cd}_{$fetch->jb_job_cd}.csv");

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
$csv = '発言SEQ,受信日時,件名,本文,ジョブ参加ID,ニックネーム,性別,年齢,未既婚,居住地';
if (check_staff_auth('参加者メンバエクスポート'))
	$csv .= ',組織ID,組織名,組織メンバーID';
output_csv($csv);

// 抽出条件
if ($_SESSION[SCD]['room_type'] == ROOM_PIR) {
	if ($_SESSION[SCD]['disp_member_id']) {
		$where = sprintf("rm_job_id=%s AND rm_disp_type=1 AND (rm_disp_member_id=%s OR rm_disp_member_id=0) AND rm_status=1",
				sql_number($job_id),
				sql_number($_SESSION[SCD]['disp_member_id']));
	} else
		$where = 'false';
} else
	$where = sprintf("rm_job_id=%s AND rm_disp_type=1 AND rm_status=1", sql_number($job_id));

switch ($_POST['download']) {
case '1':
	if ($_POST['from_y'] != '')
		$where .= " AND date_trunc('day', rm_date)>=" . sql_date("{$_POST['from_y']}/{$_POST['from_m']}/{$_POST['from_d']}");

	if ($_POST['to_y'] != '')
		$where .= " AND date_trunc('day', rm_date)<=" . sql_date("{$_POST['to_y']}/{$_POST['to_m']}/{$_POST['to_d']}");
	break;
case '2':
	if ($_POST['from_no'] != '')
		$where .= " AND rm_seq_no>=" . sql_number($_POST['from_no']);

	if ($_POST['to_no'] != '')
		$where .= " AND rm_seq_no<=" . sql_number($_POST['to_no']);
	break;
case '3':
	if ($_POST['no'] != '') {
		$ary = explode(',', $_POST['no']);
		foreach ($ary as $no) {
			if ($no != '') {
				$ary2 = explode('-', $no);
				if (count($ary2) == 1)
					$ary3[] = "rm_seq_no=" . sql_number($ary2[0]);
				elseif (count($ary2) == 2)
					$ary3[] = "rm_seq_no BETWEEN " . sql_number($ary2[0]) . " AND " . sql_number($ary2[1]);
			}
		}
		if ($ary3)
			$where .= " AND (" . join(' OR ', $ary3) . ")";
	}
	break;
}

// データ出力
$sql = "SELECT rm_seq_no,rm_date,rm_subject,rm_content,jm_member_cd,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pm_sex_cd,DATE_PART('Y',AGE(pm_birthday)) AS pm_age,pm_mikikon_cd,pm_address,pm_org_id,or_name,pm_org_member_id,pm_staff_auth"
		. " FROM l_remark"
		. " LEFT JOIN t_job_member ON jm_job_id=rm_job_id AND jm_member_id=rm_member_id"
		. " LEFT JOIN t_pjt_member ON pm_member_id=rm_member_id"
		. " LEFT JOIN m_org ON or_org_id=pm_org_id"
		. " WHERE $where"
		. " ORDER BY rm_seq_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	set_csv($csv, $fetch->rm_seq_no);
	set_csv($csv, format_datetime($fetch->rm_date));
	set_csv($csv, $fetch->rm_subject);
	set_csv($csv, $fetch->rm_content);
	set_csv($csv, $fetch->jm_member_cd);
	set_csv($csv, $fetch->pm_nickname);
	set_csv($csv, $fetch->pm_sex_cd);
	set_csv($csv, $fetch->pm_age);
	set_csv($csv, $fetch->pm_mikikon_cd);
	set_csv($csv, $fetch->pm_address);

	if (check_staff_auth('参加者メンバエクスポート')) {
		set_csv($csv, $fetch->pm_org_id);
		set_csv($csv, $fetch->or_name);
		set_csv($csv, $fetch->pm_org_member_id);
	}
	output_csv($csv);
}

exit;
?>