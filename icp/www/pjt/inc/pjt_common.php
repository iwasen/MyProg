<?
/******************************************************
' System :ICPプロジェクトページ
' Content:共通関数
'******************************************************/

define('SCD', 'p' . get_pjt_cd());

// パスからプロジェクトコード取得
function get_pjt_cd() {
	global $top;

	$ary = explode('/', $_SERVER['PHP_SELF']);
	return $ary[count($ary) - substr_count($top, '..') - 2];
}

// ジョブ名（ジョブコード）取得
function get_job_name_cd($job_id) {
	$sql = "SELECT jb_name||'（'||jb_job_cd||'）'"
			. " FROM t_job"
			. " WHERE jb_job_id=" . sql_number($job_id);
	return db_fetch1($sql);
}
?>