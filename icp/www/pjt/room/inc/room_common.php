<?
/******************************************************
' System :ICP会議室ページ
' Content:共通関数
'******************************************************/

// パスからプロジェクトコード取得
function get_pjt_cd() {
	global $top;

	$ary = explode('/', $_SERVER['PHP_SELF']);
	return $ary[count($ary) - substr_count($top, '..') - 2];
}

// ニックネーム取得
function get_nickname($remark_type, $job_id, $member_id) {
	// ニックネーム取得（開始メールの場合は差出人名を取得）
	if ($remark_type == 2)
		$sql = "SELECT jb_start_from_name FROM t_job WHERE jb_job_id=$job_id";
	else
		$sql = "SELECT COALESCE(jm_nickname,pm_nickname) AS pm_nickname FROM t_job_member JOIN t_pjt_member ON pm_member_id=jm_member_id WHERE jm_job_id=$job_id AND jm_member_id=$member_id";

	return db_fetch1($sql);
}

define('SCD', 'r' . get_pjt_cd());
?>