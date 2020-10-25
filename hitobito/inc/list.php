<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �����ڡ���ɽ�����饹
 *
 *
 * @package
 * @author
 * @version
 */

include_once('html.php');

class list_class {
	protected $class;
	protected $id;
	protected $header_ary = array();
	protected $colinfo_ary = array();
	protected $colspan_ary = array();
	protected $search_ary = array();
	protected $list_ary = array();
	protected $sort_col;
	protected $sort_dir;
	protected $col_num = 0;
	protected $col = 0;
	protected $row = 0;
	protected $page = 0;
	protected $pageline = 50;
	protected $header_row = 0;
	protected $group_on = false;

	// �����󥹥ȥ饯��
	public function __construct($class = '', $id = '') {
		$this->class = $class;
		$this->id = $id;
	}

	// ���إå�����
	public function set_header($text, $align = 'left', $sort_col = '', $group_flag = false, $csv_flag = true) {
		// �����������¸
		$this->col_num = count($this->colinfo_ary);
		$colinfo = &$this->colinfo_ary[$this->col_num];
		$colinfo->text = $text;
		$colinfo->align = $align;
		$colinfo->sort_col = $sort_col;
		$colinfo->group_flag = $group_flag;
		$colinfo->csv_flag = $csv_flag;

		// ���롼�ײ���̵ͭ����
		if ($group_flag)
			$this->group_on = true;

		// �إå��������¸
		$i = count($this->header_ary[$this->header_row]);
		$header = &$this->header_ary[$this->header_row][$i];
		$header->text = $text;
		$header->colspan = 1;
		$header->sort_col = $sort_col;
		$header->col = $this->col_num;

		// ʣ���ԥإå��ν���
		if ($this->header_row > 0) {
			$this->colspan_ary[$this->header_row]--;
			while ($this->header_row > 0 && $this->colspan_ary[$this->header_row] <= 0)
				$this->header_row--;
		}
	}

	// �����롼�ץإå�(colspan)����
	public function set_header_group($text, $colspan) {
		// �إå��������¸
		$i = count($this->header_ary[$this->header_row]);
		$header = &$this->header_ary[$this->header_row][$i];
		$header->text = $text;
		$header->sort_col = '';
		$header->atr = '';
		$header->colspan = $colspan;

		// ʣ���ԥإå��ν���
		if ($this->header_row > 0)
			$this->colspan_ary[$this->header_row] -= $colspan;
		$this->colspan_ary[++$this->header_row] = $colspan;
	}

	// ��text�������������
	public function set_search_text($name, $value, $class = '', $size = '', $maxlength = '') {
		$search = &$this->search_ary[$this->col_num];
		$search->value = $value;
		$search->tag = html_input_text($name, $value, $class, $size, $maxlength);
		$search->name = $name;
		$search->class = $class;
	}

	// ��select�������������
	public function set_search_select($name, $option, $text = '') {
		$search = &$this->search_ary[$this->col_num];
		$search->tag = html_select($name, $option) . $text;
		$search->name = $name;
		$search->class = 'select';
	}

	// ���ꥹ�ȥǡ�����HTML������
	public function set_html($html) {
		// �ꥹ�ȥǡ�����¸
		$list = &$this->list_ary[$this->row][$this->col];
		$list->text = $html;
		$list->link = '';
		$list->title = '';
		$list->escape = false;

		// �󡦹԰��ֹ���
		if (++$this->col > $this->col_num) {
			$this->col = 0;
			$this->row++;
		}
	}

	// ���ꥹ�ȥǡ����ʥƥ����ȡ�����
	public function set_data($text, $link = '', $title = '', $target = '_self') {
		// �ꥹ�ȥǡ�����¸
		$list = &$this->list_ary[$this->row][$this->col];
		$list->text = $text;
		$list->link = $link;
		$list->title = $title;
		$list->target = $target;
		$list->escape = true;

		// �󡦹԰��ֹ���
		if (++$this->col > $this->col_num) {
			$this->col = 0;
			$this->row++;
		}
	}

	// ��������WHERE�����
	public function get_where($where = '') {
		$where_ary = array();

		if ($where != '')
			$where_ary[] = $where;

		foreach ($this->search_ary as $col => $search) {
			$data = $_REQUEST[$search->name];
			if ($data != '') {
				list($col_name) = explode(',', $this->colinfo_ary[$col]->sort_col);
				switch ($search->class) {
				case 'number':
					$where_ary[] = "$col_name = " . sql_number($data);
					break;
				case 'select':
					if (preg_match('/_date$/', $col_name) && strlen($data) == 6)
						$where_ary[] = "date_trunc('month', $col_name) = '" . vsprintf('%d/%d/1', sscanf($data, '%4d%2d')) . "'";
					elseif (preg_match('/^([0-9]+)-$/', $data, $regs))
						$where_ary[] = "$col_name >= " . sql_number($regs[1]);
					elseif ($search->name == 'ng_word')
						$where_ary[] = ($data == 1) ? "($col_name IS NULL OR $col_name = '')" : "($col_name IS NOT NULL AND $col_name <> '')";
					elseif (is_numeric($data))
						$where_ary[] = "COALESCE($col_name,0) = " . sql_number($data);
					else
						$where_ary[] = "$col_name = " . sql_char($data);
					break;
				default:
					$where_ary[] = "$col_name LIKE " . sql_char("%{$data}%");
					break;
				}
			}
		}

		if (count($where_ary) == 0)
			return '';
		else
			return " WHERE " . join(' AND ', $where_ary);
	}

	// ��ɽ���������
	public function get_order_by($def_col, $def_dir) {
		// �����ȥ�������
		if (isset($_REQUEST['sort_col']))
			$this->sort_col = $_REQUEST['sort_col'];
		else
			$this->sort_col = $def_col;

		// ��������������
		if (isset($_REQUEST['sort_dir']))
			$this->sort_dir = $_REQUEST['sort_dir'];
		else
			$this->sort_dir = $def_dir;

		// order by�����
		$order = $this->colinfo_ary[$this->sort_col]->sort_col;
		if ($this->sort_dir == 1) {
			$ary = explode(',', $order);
			$ary[0] .= ' DESC';
			$order = join(',', $ary);
		}

		return " ORDER BY " . $order;
	}

	// ��ɽ���Կ�
	public function get_limit_offset() {
		if (isset($_REQUEST['pageline']))
			$this->pageline = $_REQUEST['pageline'];
		else
			$this->pageline = 50;

		if (isset($_REQUEST['page']))
			$this->page = $_REQUEST['page'];

		if ($this->pageline != 0) {
			$limit = " LIMIT $this->pageline";
			if ($this->page)
				$limit .= ' OFFSET ' . $this->pageline * $this->page;
		}

		return $limit;
	}

	// ���ꥹ������ɽ��
	public function disp_list($width = '100%') {
		$class = $this->class != '' ? " class='$this->class'" : '';
		$id = $this->id != '' ? " id='$this->id'" : '';
?>
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="<?=$width?>"<?=$class?><?=$id?>>
<?php
		$this->disp_header();
		$this->disp_search();
		$this->disp_data();
?>
</table>
<?php
	}

	// ���إå�ɽ��
	public function disp_header() {
		$row_num = count($this->header_ary);

		// �ԥ롼��
		foreach ($this->header_ary as $row => $header_tmp) {
?>
	<tr class="tch">
<?php
			// �����롼��
			foreach ($header_tmp as $header) {
				$tmp = $row_num - $row;
				$rowspan = ($header->colspan == 1 && $tmp > 1) ? " rowspan=$tmp" : '';

				$colspan = $header->colspan > 1 ? " colspan=$header->colspan" : '';

				if ($header->sort_col != '') {
					if ($header->col == $this->sort_col) {
						if ($this->sort_dir == 0) {
							$bgcolor = ' bgcolor="cornflowerblue"';
							$dir = 1;
							$title = "����å������{$header->text}�ǹ߽�˥����Ȥ��ޤ�";
						} else {
							$bgcolor = ' bgcolor="plum"';
							$dir = 0;
							$title = "����å������{$header->text}�Ǿ���˥����Ȥ��ޤ�";
						}
					} else {
						$bgcolor = '';
						$dir = 0;
						$title = "����å������{$header->text}�Ǿ���˥����Ȥ��ޤ�";
					}
?>
		<th<?=$colspan?><?=$rowspan?><?=$bgcolor?> onclick="sort_list(<?=$header->col?>,<?=$dir?>)" style="cursor:hand" title="<?=$title?>"><?=$header->text?></th>
<?php
				} else {
?>
		<th<?=$colspan?><?=$rowspan?>><?=$header->text?></th>
<?php
				}
			}
?>
	</tr>
<?php
		}
	}

	// ���������ɽ��
	public function disp_search() {
		if (count($this->search_ary)) {
?>
	<tr class="tch">
<?php
			for ($i = 0; $i <= $this->col_num; $i++) {
?>
		<td align="center"><?=$this->search_ary[$i]->tag?></td>
<?php
			}
?>
	</tr>
<?php
		}
	}

	// ���ꥹ�ȥǡ�����ɽ��
	public function disp_data() {
		$row_count = count($this->list_ary);
		$rowskip = 0;
		$rowcnt = 0;

		// �ԥ롼��
		foreach (array_keys($this->list_ary) as $row) {
			// ���롼�ײ�ͭ��ξ�硢Ʊ��ιԤ򥫥���Ȥ���
			$rowsame = 1;
			if ($this->group_on && $rowskip == 0) {
				for ($i = $row + 1; $i < $row_count; $i++) {
					$match = true;
					foreach ($this->colinfo_ary as $col => $colinfo) {
						if ($colinfo->group_flag) {
							if ($this->list_ary[$row][$col] != $this->list_ary[$i][$col]) {
								$match = false;
								break;
							}
						}
					}
					if ($match)
						$rowsame++;
					else
						break;
				}
			}
?>
	<tr class="tc<?=$rowcnt % 2?>">
<?php
			// �����롼��
			foreach ($this->colinfo_ary as $col => $colinfo) {
				$list = &$this->list_ary[$row][$col];

				$text = $list->text;
				if ($list->escape)
					$text = nl2br(htmlspecialchars($text));

				if ($list->link != '')
					$text = "<a href='$list->link' title='$list->title' target='$list->target'>$text</a>";

				$rowspan = ($colinfo->group_flag && $rowsame > 1) ? " rowspan=$rowsame" : '';

				if (!$colinfo->group_flag || $rowskip == 0) {
?>
		<td align="<?=$colinfo->align?>"<?=$rowspan?>><?=$text?></td>
<?php
				}
			}

			// Ʊ��Ԥ����ä���硢����ʹߥ����å�
			if ($rowsame > 1)
				$rowskip = $rowsame;

			// �����åץ����󥿥�����ȥ�����
			if ($rowskip > 0)
				$rowskip--;

			// Ʊ��ԤǤʤ���йԥ������
			if ($rowskip == 0)
				$rowcnt++;
?>
	</tr>
<?php
		}
	}

	// ����������JavaScript����
	public function list_form() {
?>
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.list;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function search_check() {
	var f = document.list;
<?
		foreach ($this->search_ary as $i => $search) {
			if ($search->class == 'number') {
?>
	if (!check_number2(f.<?=$search->name?>, "<?=$this->colinfo_ary[$i]->text?>"))
		return false;
	if (!check_length(f.<?=$search->name?>, "<?=$this->colinfo_ary[$i]->text?>"))
		return false;
<?
			}
		}
?>
	return true;
}
function check_number2(c, item) {
	if (c.value != "") {
		if (!c.value.match(/^[0-9]+(\.[0-9]*)?$/)) {
			alert(item + "��Ⱦ�ѿ��������Ϥ��Ƥ���������");
			c.focus();
			return false;
		}
	}
	return true;
}
function check_length(c, item) {
	if (c.value.length > 10) {
		alert(item + "�η����¿�����ޤ���");
		c.focus();
		return false;
	}
	return true;
}
//-->
</script>
<input type="hidden" name="sort_col" <?=value($this->sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($this->sort_dir)?>>
<input type="hidden" name="page" value=0>
<?php
	}

	// ��ɽ���Կ����ڡ�������ɽ��
	public function disp_page_line($nrow) {
?>
<nobr>ɽ���Կ�<select name="pageline" onchange="submit()"><?php $this->select_pageline($this->pageline); ?></select>
<input type="button" value="����" onclick="page.value=<?=$this->page - 1?>;submit()"<?=disabled($this->page == 0)?>><input type="button" value="����"onclick="page.value=<?=$this->page + 1?>;submit()"<?=disabled($this->pageline == 0 || $nrow < $this->pageline)?>></nobr>
<?php
	}

	// ��ɽ���Կ������
	protected function select_pageline($selected) {
		$ary = array(10, 20, 50, 100, 200, 500);
		echo '<option ', value_selected(0, $selected), ">- ���� -</option>\n";
		foreach ($ary as $line)
			echo '<option ', value_selected($line, $selected), ">$line</option>\n";
	}

	// ���������ݡ��ȥǡ�������
	public function get_export_data($header_flag) {
		$ary = array();

		// �إå�
		if ($header_flag) {
			$ary2 = array();
			foreach ($this->colinfo_ary as $colinfo) {
				if ($colinfo->csv_flag)
					$ary2[] = strip_tags($colinfo->text);
			}
			$ary[] = $ary2;
		}

		// �ǡ���
		foreach (array_keys($this->list_ary) as $row) {
			$ary2 = array();
			foreach ($this->colinfo_ary as $col => $colinfo) {
				if ($colinfo->csv_flag)
					$ary2[] = $this->list_ary[$row][$col]->text;
			}
			$ary[] = $ary2;
		}

		return $ary;
	}
}
?>