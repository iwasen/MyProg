<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:事務局用リスト表示関数
'******************************************************/

// ORDER BY句生成
function order_by($def_col, $def_dir) {
	global $sort_col, $sort_dir;

	if ($sort_col == '')
		$sort_col = $def_col;

	if ($sort_dir == '')
		$sort_dir = $def_dir;

	$order = func_get_arg($sort_col + 1);
	$ary = explode(';', $order);
	if ($sort_dir == 1)
		$ary[0] .= ' desc';
	$order = join(',', $ary);

	return "ORDER BY $order";
}

// ソートリストヘッダ
function sort_header($column, $text, $rowspan = 1) {
	global $sort_col, $sort_dir;

	if ($rowspan > 1)
		$rowspan = " rowspan=$rowspan";
	else
		$rowspan = '';

	if ($column) {
		if ($column == $sort_col) {
			if ($sort_dir == 0) {
				$bgcolor = ' bgcolor="skyblue"';
				$dir = 1;
				$title = "クリックすると{$text}で降順にソートします";
			} else {
				$bgcolor = ' bgcolor="pink"';
				$dir = 0;
				$title = "クリックすると{$text}で昇順にソートします";
			}
		} else {
			$dir = 0;
			$title = "クリックすると{$text}で昇順にソートします";
		}
		echo "<th$rowspan$bgcolor onclick='sort_list($column,$dir)' style='cursor:hand' title='$title'>$text</th>\n";
	} else
		echo "<th$rowspan>$text</th>\n";
}

// 表示行数
function disp_limit($def = 50) {
	global $displine, $page;

	if ($displine == '')
		$displine = $def;

	if ($displine != 0) {
		$limit = "LIMIT $displine";
		if ($page)
			$limit .= ' OFFSET ' . $displine * $page;
	}

	return $limit;
}

// セッション変数取得
function get_session_vars($pset, $prefix) {
	global $g_staff_id;

	$argnum = func_num_args();

	$session_active = "{$prefix}_active";

	for ($i = 2; $i < $argnum; $i++) {
		$global_var = func_get_arg($i);
		$session_var = "{$prefix}_$global_var";

		if (!$pset) {
			if ($_SESSION[$session_active])
				$value = $_SESSION[$session_var];
			else {
				switch ($global_var) {
				case 'mitaiou':
					$value = '1';
					break;
				case 'contents':
					$value = '1';
					break;
				default:
					$value = '';
				}
			}
		} else
			$value = $_REQUEST[$global_var];

		$GLOBALS[$global_var] = $value;
		$_SESSION[$session_var] = $value;
	}

	$_SESSION[$session_active] = true;
}

// 同一項目連結処理用クラス
class join_same_item {
	var $m_tbl;
	var $m_count;
	var $m_index;

	function prepare($result, $col) {
		$nrow = pg_num_rows($result);
		if ($nrow) {
			$fetch = pg_fetch_array($result, 0);

			$item = $fetch[$col];

			$this->m_count = 0;
			$this->m_index = 0;
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_array($result, $i);

				if ($item == $fetch[$col])
					$this->m_count++;
				else {
					$this->m_tbl[$this->m_index] = $this->m_count;
					$this->m_index++;
					$this->m_count = 1;
					$item = $fetch[$col];
				}
			}
			$this->m_tbl[$this->m_index] = $this->m_count;
		}
		$this->m_count = 0;
		$this->m_index = 0;
	}

	function get_row_count() {
		if ($this->m_count == 0) {
			$this->m_count = $this->m_tbl[$this->m_index];
			$this->m_index++;
			$count = $this->m_count;
		} else
			$coun = 0;

		$this->m_count--;

		return $count;
	}
}

function list_script() {
?>
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
//-->
</script>
<?
}
?>