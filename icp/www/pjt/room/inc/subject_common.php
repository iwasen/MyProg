<?
/******************************************************
' System :ICP会議室ページ
' Content:件名表示関数
'******************************************************/

// 表示ページ選択肢
function select_page($page_max, $selected) {
	if ($page_max == 0)
		echo '<option value="">表示データ無し</option>', "\n";
	else {
		for ($i = 1; $i <= $page_max; $i++)
			echo '<option ', value_selected($i, $selected), '>', $i, 'ページ目</option>', "\n";
	}
}

// マーキング色読み込み
function get_marking_data() {
	global $marking_ary;

	$sql = "SELECT mk_marking_id,mk_color FROM m_marking";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$marking_ary[$fetch->mk_marking_id] = $fetch->mk_color;
	}
}
?>