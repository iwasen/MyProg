<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:挨拶文取得関数
'******************************************************/

function get_season_greeting() {
	$sql = "SELECT sgr_greeting"
			. " FROM t_season_greetings"
			. " WHERE date_part('month', current_date) BETWEEN sgr_from_month AND sgr_to_month"
			. " AND date_part('day', current_date) BETWEEN sgr_from_day AND sgr_to_day"
			. " ORDER BY sgr_seq_no DESC"
			. " LIMIT 1";
	$result = db_exec($sql);
	if (pg_num_rows($result) != 0)
		$greeting = pg_fetch_result($result, 0, 0);

	return $greeting;
}
?>