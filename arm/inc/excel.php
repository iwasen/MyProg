<?
/******************************************************
* system  : xxx リスク診断
* title   : エクセル互換関数
* content : エクセル内で使用される関数の類似処理
* version : 1.0
* author  : aizawa
*******************************************************/

// ■IF関数
function VB_IF($condition, $true_value, $false_value) {
	return $condition ? $true_value : $false_value;
}

// ■YEAR関数
function VB_YEAR($date) {
	if ($date != '')
		list($year) = explode('/', $date);
	else
		$year = '';
	return $year;
}

// ■MONTH関数
function VB_MONTH($date) {
	if ($date != '')
		list(, $month) = explode('/', $date);
	else
		$month = '';
	return $month;
}

// ■DAY関数
function VB_DAY($date) {
	if ($date != '')
		list(, , $day) = explode('/', $date);
	else
		$day = '';
	return $day;
}

// ■TODAY関数
$g_today = date('Y/m/d');
function VB_TODAY() {
	global $g_today;
	return $g_today;
}

// ■PV関数
function VB_PV($rate, $nper, $pmt, $fv, $type) {
	return -($pmt * (1 + $rate * $type) * ((pow(1 + $rate, $nper) - 1) / $rate) + $fv) / pow(1 + $rate, $nper);
}

// ■AND関数
function VB_AND() {
	$args = func_get_args();
	$bool = true;
	foreach ($args as $arg)
		$bool = $bool && $arg;
	return $bool;
}

// ■OR関数
function VB_OR() {
	$args = func_get_args();
	$bool = false;
	foreach ($args as $arg)
		$bool = $bool || $arg;
	return $bool;
}

// ■VLOOKUP関数
function VB_VLOOKUP($s, $find, $range, $index, $type, $val = null) {
	global $g_sheets;

	$c = &$g_sheets[$s];

	list($start, $end) = explode(':', $range);
	cell_to_rowcol($start, $start_row, $start_col);
	cell_to_rowcol($end, $end_row, $end_col);

	for ($row = $start_row; $row <= $end_row; $row++) {
		if ($find == $c[rowcol_to_cell($row, $start_col)])
			break;

		if ($type && $find < $c[rowcol_to_cell($row, $start_col)]) {
			$row--;
			break;
		}
	}

	if ($type && $row > $end_row)
		$row--;

	if ($row >= $start_row && $row <= $end_row) {
		if (isset($c[rowcol_to_cell($row, $start_col + 1)]) && is_array($c[rowcol_to_cell($row, $start_col + 1)]))
			$val = $c[rowcol_to_cell($row, $start_col + 1)][$index - 1];
		else
			$val = $c[rowcol_to_cell($row, $start_col + $index - 1)];
	}

	return $val;
}

// ■COUNTIF関数
function VB_COUNTIF(&$ary, $condition) {
	$cnt = 0;
	if (is_array($ary)) {
		foreach ($ary as $val) {
			if ($val != '')
				eval('$cnt += (' . $val . $condition . ') ? 1 : 0;');
		}
	}
	return $cnt;
}

// ■SUM関数
function VB_SUM($s) {
	global $g_sheets;

	$c = &$g_sheets[$s];

	$args = func_get_args();
	array_shift($args);
	$cells = get_range($args);

	$sum = 0;
	foreach ($cells as $cell) {
		if (isset($c[$cell])) {
			if (is_numeric($cell))
				$sum += $cell;
			else {
				if (is_array($c[$cell]))
					$sum += array_sum($c[$cell]);
				else
					$sum += $c[$cell];
			}
		}
	}

	return $sum;
}

// ■SUM関数
function VB_SUM2($s, $index) {
	global $g_sheets;

	$c = &$g_sheets[$s];

	$args = func_get_args();
	array_shift($args);
	array_shift($args);
	$cells = get_range($args);

	$sum = 0;
	foreach ($cells as $cell)
		$sum += $c[$cell][$index];

	return $sum;
}

// ■SUM関数
function VB_SUM3($s, $range1, $range2, $index1 = false, $index2 = false) {
	global $g_sheets;

	$c = &$g_sheets[$s];

	list($start1, $end1) = explode(':', $range1);
	list($start2, $end2) = explode(':', $range2);

	cell_to_rowcol($start1, $start1_row, $start1_col);
	cell_to_rowcol($end1, $end1_row, $end1_col);
	cell_to_rowcol($start2, $start2_row, $start2_col);
	cell_to_rowcol($end2, $end2_row, $end2_col);

	$sum = 0;
	for ($row = $start1_row; $row <= $end1_row; $row++) {
		for ($col = $start1_col; $col <= $end1_col; $col++) {
			$p1 = rowcol_to_cell($row, $col);
			$p2 = rowcol_to_cell($row - $start1_row + $start2_row, $col - $start1_col + $start2_col);
			$sum += ($index1 === false ? $c[$p1] : $c[$p1][$index1]) * ($index2 === false ? $c[$p2] : $c[$p2][$index2]);
		}
	}

	return $sum;
}

// ■CHOOSE関数
function VB_CHOOSE($index) {
	return func_get_arg($index);
}

// ■MIN関数
function VB_MIN($s) {
	global $g_sheets;

	$c = &$g_sheets[$s];

	$args = func_get_args();
	array_shift($args);
	$cells = get_range($args);

	$first = true;
	$min = 0;
	foreach ($cells as $cell) {
		if (is_array($c[$cell])) {
			foreach ($c[$cell] as $val) {
				if (is_numeric($val)) {
					if ($first) {
						$min = $val;
						$first = false;
					} elseif ($val < $min)
						$min = $val;
				}
			}
		} else {
			$val = $c[$cell];
			if (is_numeric($val)) {
				if ($first) {
					$min = $val;
					$first = false;
				} elseif ($val < $min)
					$min = $val;
			}
		}
	}

	return $min;
}

// ■MAX関数
function VB_MAX($s) {
	global $g_sheets;

	$c = &$g_sheets[$s];

	$args = func_get_args();
	array_shift($args);
	$cells = get_range($args);

	$first = true;
	$max = 0;
	foreach ($cells as $cell) {
		if (is_array($c[$cell])) {
			foreach ($c[$cell] as $val) {
				if (is_numeric($val)) {
					if ($first) {
						$max = $val;
						$first = false;
					} elseif ($val > $max)
						$max = $val;
				}
			}
		} else {
			$val = $c[$cell];
			if (is_numeric($val)) {
				if ($first) {
					$max = $val;
					$first = false;
				} elseif ($val > $max)
					$max = $val;
			}
		}
	}

	return $max;
}

// ■OFFSET関数
function VB_OFFSET($base, $row_offset, $col_offset, $height, $width) {
	cell_to_rowcol($base, $base_row, $base_col);

	$start_row = $base_row + $row_offset;
	$start_col = $base_col + $col_offset;

	$end_row = $start_row + $height - 1;
	$end_col = $start_col + $width - 1;

	return rowcol_to_cell($start_row, $start_col) . ':' . rowcol_to_cell($end_row, $end_col);
}

// ■セル名を行・列番号に変換
function cell_to_rowcol($cell, &$row, &$col) {
	if ($cell{1} >= 'A') {
		$col = (ord($cell{0}) - 64) * 26 + (ord($cell{1}) - 64);
		$row = substr($cell, 2);
	} else {
		$col = ord($cell{0}) - 64;
		$row = substr($cell, 1);
	}
}

// ■行・列番号をセル名に変換
function rowcol_to_cell($row, $col) {
	$col--;
	$cell = chr($col % 26 + 65);
	if ($col >= 26)
		$cell = chr((int)($col / 26) + 64) . $cell;
	$cell .= $row;
	return $cell;
}

// ■次の行のセル名を取得
function next_row_cell($cell, $step = 1) {
	cell_to_rowcol($cell, $row, $col);
	return rowcol_to_cell($row + $step, $col);

}

// ■次の列のセル名を取得
function next_col_cell($cell, $step = 1) {
	cell_to_rowcol($cell, $row, $col);
	return rowcol_to_cell($row, $col + $step);
}

// ■セルの範囲を分解
function get_range($ranges) {
	$cells = array();
	if (is_array($ranges)) {
		foreach ($ranges as $range) {
			if (strpos($range, ':')) {
				list($start_cell, $end_cell) = explode(':', $range);
				cell_to_rowcol($start_cell, $start_row, $start_col);
				cell_to_rowcol($end_cell, $end_row, $end_col);

				for ($row = $start_row; $row <= $end_row; $row++) {
					for ($col = $start_col; $col <= $end_col; $col++)
						$cells[] = rowcol_to_cell($row, $col);
				}
			} else
				$cells[] = $range;
		}
	}

	return $cells;
}

// ■カレントシート配列設定
function set_current_sheets(&$sheets) {
	$GLOBALS['g_sheets'] = &$sheets;
}
?>