<?
/******************************************************
' System :ICP会議室ページ
' Content:全話題の発言録ダウンロード実行
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
include("$inc/record_all.php");

// 入力パラメータ
$job_id = $_SESSION[SCD]['job_id'];

// ジョブコードを取得
$sql = "SELECT jb_job_cd"
		. " FROM t_job"
		. " WHERE jb_job_id=$job_id";
$job_cd = db_fetch1($sql);

// 出力ファイル名
prepare_csv("alltopics_{$job_cd}.csv");


// 発言内容をトピック毎に取得
$where = sprintf("rm_job_id=%s AND rm_disp_type=1 AND rm_status=1 AND rm_marking_id=1", sql_number($job_id));
if ($_SESSION[SCD]['room_type'] == ROOM_PIR) {
	if ($_SESSION[SCD]['disp_member_id'])
		$where .= sprintf(" AND (rm_disp_member_id=%s OR rm_disp_member_id=0)", sql_number($_SESSION[SCD]['disp_member_id']));
	else
		$where = 'false';
}

// 発言録を配列に格納
$topic_ary = array();
$sql = "SELECT rm_remark_id,rm_subject"
		. " FROM l_remark"
		. " WHERE $where"
		. " ORDER BY rm_remark_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$topic_ary[$i]['subject'] = '★' . $fetch->rm_subject;
	$topic_ary[$i]['content'] = get_record_ary($job_id, $fetch->rm_remark_id);
}

// メンバーの発言の有無をチェック
$member_remark_ary = array();
foreach ($topic_ary as $topic) {
	foreach ($topic['content'] as $remark) {
		foreach (array_keys($remark) as $member_id)
			$member_remark_ary[$member_id] = true;
	}
}

// ニックネーム出力
set_csv($csv, 'トピック', true);
$nickname_ary = get_nickname_ary($job_id);
$member_id_ary = array();
foreach ($nickname_ary as $member_id => $nickname) {
	if ($member_remark_ary[$member_id]) {
		set_csv($csv, $nickname, true);
		$member_id_ary[] = $member_id;
	}
}
output_csv($csv);

// 発言録を出力
foreach ($topic_ary as $topic) {
	$subject = $topic['subject'];

	foreach ($topic['content'] as $content) {
		// 件名を出力
		set_csv($csv, $subject, true);
		$subject = '';

		foreach ($member_id_ary as $member_id)
			set_csv($csv, str_replace("\r", '', $content[$member_id]), true);

		output_csv($csv);
	}
}

exit;
?>