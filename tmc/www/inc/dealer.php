<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」販売店様用ページ
' Content:販売店様用ページ共通関数
'******************************************************/

// 販売店名取得
function get_dealer_name() {
	global $g_dealer_cd;

	$sql = "SELECT dlr_dealer_name FROM t_dealer WHERE dlr_dealer_cd='$g_dealer_cd'";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// ページ遷移履歴セット
function set_page_history($level, $name, $title, $url = '') {
	$_SESSION['page_history'][$level]['name'] = $name; 
	$_SESSION['page_history'][$level]['url'] = $url != '' ? $url : $_SERVER['REQUEST_URI'];
	$_SESSION['page_history'][$level]['title'] = $title;
	$_SESSION['page_history_level'] = $level;
}

// ページ遷移履歴取得
function get_page_history() {
	$level = $_SESSION['page_history_level'];
	for ($i = 0; $i <= $level; $i++) {
		$ary = &$_SESSION['page_history'][$i];
		if ($i < $level)
			$history .= "<a href='{$ary['url']}' title='{$ary['title']}'>{$ary['name']}</a> ＞ ";
		else
			$history .= $ary['name'];
	}

	return $history;
}
?>