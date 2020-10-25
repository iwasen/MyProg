<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:�ꥹ��ɽ���ؿ�
'******************************************************/

// ORDER BY������
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

// �����ȥꥹ�ȥإå�
function sort_header($column, $text, $width = '') {
	global $sort_col, $sort_dir;

	if ($width != '')
		$width = " width=\"$width\"";

	if ($column) {
		if ($column == $sort_col) {
			if ($sort_dir == 0) {
				$bgcolor = ' bgcolor="skyblue"';
				$dir = 1;
				$title = "����å������{$text}�ǹ߽�˥����Ȥ��ޤ�";
			} else {
				$bgcolor = ' bgcolor="pink"';
				$dir = 0;
				$title = "����å������{$text}�Ǿ���˥����Ȥ��ޤ�";
			}
		} else {
			$dir = 0;
			$title = "����å������{$text}�Ǿ���˥����Ȥ��ޤ�";
		}
		echo "<th$width$bgcolor onclick='sort_list($column,$dir)' style='cursor:hand' title='$title'>$text</th>\n";
	} else
		echo "<th$width>$text</th>\n";
}

// ɽ���Կ�
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

// ���å�����ѿ�����
function get_session_vars($pset, $prefix) {
	global $g_staff_id;

	$argnum = func_num_args();

	$session_active = "{$prefix}_active";

	for ($i = 2; $i < $argnum; $i++) {
		$global_var = func_get_arg($i);
		$session_var = "{$prefix}_$global_var";

		if (!$pset) {
			if ($_SESSION[SCD][$session_active])
				$value = $_SESSION[SCD][$session_var];
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
		$_SESSION[SCD][$session_var] = $value;
	}

	$_SESSION[SCD][$session_active] = true;
}

// Ʊ�����Ϣ������ѥ��饹
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