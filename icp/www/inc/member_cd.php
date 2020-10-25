<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:メンバーCD生成関数
'******************************************************/

// ログインID生成
function create_member_cd() {
	while (true) {
		// ID生成
		$member_cd = get_rand_chars(6);

		// ダブりが無いかチェック
		$sql = "SELECT COUNT(*) FROM t_job_member WHERE jm_member_cd='$member_cd'";
		$result = db_exec($sql);
		if (pg_fetch_result($result, 0, 0) == 0)
			break;
	}

	return $member_cd;
}

// 乱数初期化
function init_rand() {
	global $g_init_rand;

	if (!$g_init_rand) {
		mt_srand((double)microtime() * 1000000);
		$g_init_rand = true;
	}
}

// ランダムな文字列を生成
function get_rand_chars($n) {
	$chars = 'abcdefghjkmnprstwxyz23456789';

	init_rand();

	$s = '';
	for ($i = 0; $i < $n; $i++)
		$s .= $chars{mt_rand(0, strlen($chars) - 1)};

	return $s;
}
?>