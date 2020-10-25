<?
//メール形式DECODE
function decode_mail_format($code, $def = '不明') {
	switch ($code) {
	case 0:
		return 'テキスト';
	case 1:
		return 'HTML';
	}
	return $def;
}

//ジョブ種別DECODE
function decode_job_type($code, $def = '不明') {
	switch ($code) {
	case 0:
		return '発信JOB';
	case 1:
		return 'アンケートレスポンスJOB';
	case 2:
		return 'クリックレスポンスJOB';
	}
	return $def;
}

//親ジョブ名DECODE
function parent_job_name_decode($code) {
	if ($code) {
		$sql = "SELECT jb_job_name FROM t_job WHERE jb_job_id=$code";
		$result = db_exec($sql);
		$fetch = pg_fetch_object($result, 0);
		echo $fetch->jb_job_name;
	}
}
?>