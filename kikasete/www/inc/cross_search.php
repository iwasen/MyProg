<?
/******************************************************
' System :きかせて・net共通
' Content:クロスサーチ処理
'******************************************************/

function cross_search($cross1, $cross2, $where) {
	if ($cross1 == $cross2)
		return;

	cross_item($cross1, $item1, $select1, $join1);
	cross_item($cross2, $item2, $select2, $join2);

	$sql = "SELECT $select1,$select2,COUNT(*)"
			. " FROM t_monitor $join1 $join2"
			. " WHERE $where"
			. " GROUP BY $select1,$select2";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$table[$fetch[0]][$fetch[1]] = $fetch[2];
	}

	echo "<tr>\n";
	echo "<td bgcolor='#eeeeee'><br></td>\n";
	foreach ($item1 as $code1 => $name1)
		echo "<td align='center' bgcolor='#ffdddd'>$name1</td>\n";
	echo "</tr>\n";

	$n = 1;
	foreach ($item2 as $code2 => $name2) {
		$n = ++$n % 2;
		echo "<tr class='tc$n'>\n";
		echo "<td>$name2</td>\n";
		foreach ($item1 as $code1 => $name1)
			echo '<td align="right">', number_format($table[$code1][$code2]), "</td>\n";
		echo "</tr>\n";
	}
}

function cross_item($cross, &$item, &$select, &$join) {
	switch ($cross) {
	case 1:		// 性別
		$item[1] = decode_sex(1);
		$item[2] = decode_sex(2);
		$select = 'mn_sex';
		break;
	case 2:		// 年代別（10歳刻み）
		read_master($item, 'SELECT ge_age_cd,ge_age_text FROM m_age10 ORDER BY ge_age_cd');
		$select = 'ge_age_cd';
		$join = "JOIN m_age10 ON DATE_PART('Y',AGE(mn_birthday)) BETWEEN ge_age_from AND ge_age_to";
		break;
	case 3:		// 年代別（5歳刻み）
		read_master($item, 'SELECT ae_age_cd,ae_age_text FROM m_age ORDER BY ae_age_cd');
		$select = 'ae_age_cd';
		$join = "JOIN m_age ON DATE_PART('Y',AGE(mn_birthday)) BETWEEN ae_age_from AND ae_age_to";
		break;
	case 4:		// 未既婚別
		$item[1] = decode_mikikon(1);
		$item[2] = decode_mikikon(2);
		$item[3] = decode_mikikon(3);
		$select = 'mn_mikikon';
		break;
	case 5:		// 職業別
		read_master($item, 'SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order');
		$select = 'mn_shokugyou_cd';
		break;
	case 6:		// 業種別
		read_master($item, 'SELECT gs_gyoushu_cd,gs_gyoushu_name FROM m_gyoushu WHERE gs_type_cd=1 ORDER BY gs_order');
		$select = 'mn_gyoushu_cd';
		break;
	case 7:		// 職種別
		read_master($item, 'SELECT ss_shokushu_cd,ss_shokushu_name FROM m_shokushu ORDER BY ss_order');
		$select = 'mn_shokushu_cd';
		break;
	case 8:		// 都道府県別
		read_master($item, 'SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd');
		$select = 'mn_jitaku_area';
		break;
	case 9:		// エリア別
		read_master($item, 'SELECT aa_area2_cd,aa_area2_name FROM m_area2 ORDER BY aa_area2_cd');
		$select = 'ar_area2_cd';
		$join = 'JOIN m_area ON ar_area_cd=mn_jitaku_area';
		break;
	case 10:	// コンビニ別
		read_master($item, 'SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order');
		$select = 'dc_conveni_cd';
		$join = 'JOIN t_conveni ON dc_monitor_id=mn_monitor_id';
		break;
	case 11:	// スーパー別
		read_master($item, 'SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order');
		$select = 'ds_super_cd';
		$join = 'JOIN t_super ON ds_monitor_id=mn_monitor_id';
		break;
	case 12:	// 興味ジャンル別
		read_master($item, 'SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order');
		$select = 'dk_genre_cd';
		$join = 'JOIN t_genre ON dk_monitor_id=mn_monitor_id';
		break;
	default:	// 指定無し
		$item[1] = '件数';
		$select = '1';
		break;
	}
}

function read_master(&$item, $sql) {
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$item[$fetch[0]] = $fetch[1];
	}
}
?>