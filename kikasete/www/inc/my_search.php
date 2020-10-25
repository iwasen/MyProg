<?
/******************************************************
' System :きかせて・net共通
' Content:Myサーチ関連
'******************************************************/

function disp_master($sql) {
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		echo htmlspecialchars($fetch[0]), '　';
	}
}

function disp_age($code, $def = '<br>') {
	if ($code != '') {
		$sql = "SELECT ae_age_text FROM m_age WHERE ae_age_cd IN ($code) ORDER BY ae_age_cd";
		disp_master($sql);
	} else
		echo $def;
}

function disp_age2($age_cd, $age_from, $age_to, $def = '<br>') {
	if ($age_cd != '') {
		$sql = "SELECT ae_age_text FROM m_age WHERE ae_age_cd IN ($age_cd) ORDER BY ae_age_cd";
		disp_master($sql);
	} elseif ($age_from != '' || $age_to != '') {
		if ($age_from != '')
			echo $age_from, '才';
		echo '〜';
		if ($age_to != '')
			echo $age_to, '才';
	} else
		echo $def;
}

function disp_area($code, $def = '<br>') {
	if ($code != '') {
		$sql = "SELECT ar_area_name FROM m_area WHERE ar_area_cd IN ($code) ORDER BY ar_area_cd";
		disp_master($sql);
	} else
		echo $def;
}

function disp_shokugyou($code, $def = '<br>') {
	if ($code != '') {
		$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd IN ($code) ORDER BY sg_order";
		disp_master($sql);
	} else
		echo $def;
}

function disp_gyoushu($code, $def = '<br>') {
	if ($code != '') {
		$sql = "SELECT gs_gyoushu_name FROM m_gyoushu WHERE gs_gyoushu_cd IN ($code) ORDER BY gs_order";
		disp_master($sql);
	} else
		echo $def;
}

function disp_shokushu($code, $def = '<br>') {
	if ($code != '') {
		$sql = "SELECT ss_shokushu_name FROM m_shokushu WHERE ss_shokushu_cd IN ($code) ORDER BY ss_order";
		disp_master($sql);
	} else
		echo $def;
}

function disp_conveni($code, $def = '<br>') {
	if ($code != '') {
		$sql = "SELECT cv_name FROM m_conveni WHERE cv_conveni_cd IN ($code) ORDER BY cv_order";
		disp_master($sql);
	} else
		echo $def;
}

function disp_super($code, $def = '<br>') {
	if ($code != '') {
		$sql = "SELECT sp_name FROM m_super WHERE sp_super_cd IN ($code) ORDER BY sp_order";
		disp_master($sql);
	} else
		echo $def;
}

function disp_genre($code, $def = '<br>') {
	if ($code != '') {
		$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($code) ORDER BY kg_order";
		disp_master($sql);
	} else
		echo $def;
}

function disp_ma_profile($profile_id, $code, $def = '<br>') {
	if ($code != '') {
		$sql = "SELECT pfs_select_text FROM t_profile_sel WHERE pfs_profile_id=$profile_id AND pfs_profile_cd IN ($code) ORDER BY pfs_order";
		disp_master($sql);
	} else
		echo $def;
}

// チェックボックス表示処理
function checkbox_common($sql, $name, $code, $cols) {
	$ary = explode(',', $code);

	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	echo '<table border=0 width="100%">', "\n";
	$col = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%'><input type='checkbox' name='${name}[]' value='$fetch[0]'", in_array($fetch[0], $ary) ? ' checked' : '', "><span class='nor_text'>", htmlspecialchars($fetch[1]), "</span></td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}

function arry_check($value, $ary) {
	if (is_array($ary) && in_array($value, $ary))
		return "value='$value' checked";
	else
		return "value='$value'";
}
?>