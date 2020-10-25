<?
/******************************************************
' System :きかせて・net共通
' Content:生活情報関連処理
'******************************************************/

// チェックボックス表示処理
function checkbox_common($sql, $name, $cols, $readonly) {
	echo "<table border=0 cellspacing=0 cellpadding=1>\n";

	$readonly = $readonly ? ' onclick="return false"' : '';
	$col = 0;
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td><input type='checkbox' name='{$name}[]'", value_checked($fetch[0], $fetch[2]), "$readonly>{$fetch[1]}</td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}

// よく行くコンビニ
function checkbox_conveni($monitor_id, $cols, $readonly = false) {
	if ($monitor_id) {
		$sql = "SELECT cv_conveni_cd,cv_name,dc_conveni_cd"
				. " FROM m_conveni LEFT JOIN t_conveni ON dc_monitor_id=$monitor_id AND cv_conveni_cd=dc_conveni_cd"
				. " WHERE cv_status=0"
				. " ORDER BY cv_order";
	} else {
		$sql = "SELECT cv_conveni_cd,cv_name,NULL FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
	}

	checkbox_common($sql, 'conveni_cd', $cols, $readonly);
}

// よく行くスーパー
function checkbox_super($monitor_id, $cols, $readonly = false) {
	if ($monitor_id) {
		$sql = "SELECT sp_super_cd,sp_name,ds_super_cd"
				. " FROM m_super LEFT JOIN t_super ON ds_monitor_id=$monitor_id AND sp_super_cd=ds_super_cd"
				. " WHERE sp_status=0"
				. " ORDER BY sp_order";
	} else {
		$sql = "SELECT sp_super_cd,sp_name,NULL FROM m_super WHERE sp_status=0 ORDER BY sp_order";
	}

	checkbox_common($sql, 'super_cd', $cols, $readonly);
}

// 興味ジャンル
function checkbox_genre($monitor_id, $cols, $readonly = false) {
	if ($monitor_id) {
		$sql = "SELECT kg_genre_cd,kg_name,dk_genre_cd"
				. " FROM m_genre LEFT JOIN t_genre ON dk_monitor_id=$monitor_id AND kg_genre_cd=dk_genre_cd"
				. " WHERE kg_status=0"
				. " ORDER BY kg_order";
	} else {
		$sql = "SELECT kg_genre_cd,kg_name,NULL FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
	}

	checkbox_common($sql, 'genre_cd', $cols, $readonly);
}

// 追加プロファイル
function checkbox_ma_profile($monitor_id, $profile_id, $cols, $readonly = false) {
	if ($monitor_id) {
		$sql = "SELECT pfs_profile_cd,pfs_select_text,mnp_profile_cd"
				. " FROM t_profile_sel LEFT JOIN t_monitor_profile ON mnp_monitor_id=$monitor_id AND mnp_profile_cd=pfs_profile_cd"
				. " WHERE pfs_profile_id=$profile_id"
				. " ORDER BY pfs_order";
	} else {
		$sql = "SELECT pfs_profile_cd,pfs_select_text,NULL FROM t_profile_sel WHERE pfs_profile_id=$profile_id ORDER BY pfs_order";
	}

	checkbox_common($sql, 'profile_cd', $cols, $readonly);
}
?>