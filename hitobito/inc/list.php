<?php
/**
 * ひとびと･net 共通ファイル
 *
 * 一覧ページ表示クラス
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

	// ■コンストラクタ
	public function __construct($class = '', $id = '') {
		$this->class = $class;
		$this->id = $id;
	}

	// ■ヘッダ設定
	public function set_header($text, $align = 'left', $sort_col = '', $group_flag = false, $csv_flag = true) {
		// カラム情報を保存
		$this->col_num = count($this->colinfo_ary);
		$colinfo = &$this->colinfo_ary[$this->col_num];
		$colinfo->text = $text;
		$colinfo->align = $align;
		$colinfo->sort_col = $sort_col;
		$colinfo->group_flag = $group_flag;
		$colinfo->csv_flag = $csv_flag;

		// グループ化の有無設定
		if ($group_flag)
			$this->group_on = true;

		// ヘッダ情報を保存
		$i = count($this->header_ary[$this->header_row]);
		$header = &$this->header_ary[$this->header_row][$i];
		$header->text = $text;
		$header->colspan = 1;
		$header->sort_col = $sort_col;
		$header->col = $this->col_num;

		// 複数行ヘッダの処理
		if ($this->header_row > 0) {
			$this->colspan_ary[$this->header_row]--;
			while ($this->header_row > 0 && $this->colspan_ary[$this->header_row] <= 0)
				$this->header_row--;
		}
	}

	// ■グループヘッダ(colspan)設定
	public function set_header_group($text, $colspan) {
		// ヘッダ情報を保存
		$i = count($this->header_ary[$this->header_row]);
		$header = &$this->header_ary[$this->header_row][$i];
		$header->text = $text;
		$header->sort_col = '';
		$header->atr = '';
		$header->colspan = $colspan;

		// 複数行ヘッダの処理
		if ($this->header_row > 0)
			$this->colspan_ary[$this->header_row] -= $colspan;
		$this->colspan_ary[++$this->header_row] = $colspan;
	}

	// ■text型検索条件設定
	public function set_search_text($name, $value, $class = '', $size = '', $maxlength = '') {
		$search = &$this->search_ary[$this->col_num];
		$search->value = $value;
		$search->tag = html_input_text($name, $value, $class, $size, $maxlength);
		$search->name = $name;
		$search->class = $class;
	}

	// ■select型検索条件設定
	public function set_search_select($name, $option, $text = '') {
		$search = &$this->search_ary[$this->col_num];
		$search->tag = html_select($name, $option) . $text;
		$search->name = $name;
		$search->class = 'select';
	}

	// ■リストデータ（HTML）設定
	public function set_html($html) {
		// リストデータ保存
		$list = &$this->list_ary[$this->row][$this->col];
		$list->text = $html;
		$list->link = '';
		$list->title = '';
		$list->escape = false;

		// 列・行位置更新
		if (++$this->col > $this->col_num) {
			$this->col = 0;
			$this->row++;
		}
	}

	// ■リストデータ（テキスト）設定
	public function set_data($text, $link = '', $title = '', $target = '_self') {
		// リストデータ保存
		$list = &$this->list_ary[$this->row][$this->col];
		$list->text = $text;
		$list->link = $link;
		$list->title = $title;
		$list->target = $target;
		$list->escape = true;

		// 列・行位置更新
		if (++$this->col > $this->col_num) {
			$this->col = 0;
			$this->row++;
		}
	}

	// ■検索用WHERE句取得
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

	// ■表示順序取得
	public function get_order_by($def_col, $def_dir) {
		// ソートカラム取得
		if (isset($_REQUEST['sort_col']))
			$this->sort_col = $_REQUEST['sort_col'];
		else
			$this->sort_col = $def_col;

		// ソート方向取得
		if (isset($_REQUEST['sort_dir']))
			$this->sort_dir = $_REQUEST['sort_dir'];
		else
			$this->sort_dir = $def_dir;

		// order by句取得
		$order = $this->colinfo_ary[$this->sort_col]->sort_col;
		if ($this->sort_dir == 1) {
			$ary = explode(',', $order);
			$ary[0] .= ' DESC';
			$order = join(',', $ary);
		}

		return " ORDER BY " . $order;
	}

	// ■表示行数
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

	// ■リスト全体表示
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

	// ■ヘッダ表示
	public function disp_header() {
		$row_num = count($this->header_ary);

		// 行ループ
		foreach ($this->header_ary as $row => $header_tmp) {
?>
	<tr class="tch">
<?php
			// カラムループ
			foreach ($header_tmp as $header) {
				$tmp = $row_num - $row;
				$rowspan = ($header->colspan == 1 && $tmp > 1) ? " rowspan=$tmp" : '';

				$colspan = $header->colspan > 1 ? " colspan=$header->colspan" : '';

				if ($header->sort_col != '') {
					if ($header->col == $this->sort_col) {
						if ($this->sort_dir == 0) {
							$bgcolor = ' bgcolor="cornflowerblue"';
							$dir = 1;
							$title = "クリックすると{$header->text}で降順にソートします";
						} else {
							$bgcolor = ' bgcolor="plum"';
							$dir = 0;
							$title = "クリックすると{$header->text}で昇順にソートします";
						}
					} else {
						$bgcolor = '';
						$dir = 0;
						$title = "クリックすると{$header->text}で昇順にソートします";
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

	// ■検索条件表示
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

	// ■リストデータ部表示
	public function disp_data() {
		$row_count = count($this->list_ary);
		$rowskip = 0;
		$rowcnt = 0;

		// 行ループ
		foreach (array_keys($this->list_ary) as $row) {
			// グループ化有りの場合、同一の行をカウントする
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
			// カラムループ
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

			// 同一行があった場合、それ以降スキップ
			if ($rowsame > 1)
				$rowskip = $rowsame;

			// スキップカウンタカウントダウン
			if ($rowskip > 0)
				$rowskip--;

			// 同一行でなければ行カウント
			if ($rowskip == 0)
				$rowcnt++;
?>
	</tr>
<?php
		}
	}

	// ■ソート用JavaScript出力
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
			alert(item + "は半角数字で入力してください。");
			c.focus();
			return false;
		}
	}
	return true;
}
function check_length(c, item) {
	if (c.value.length > 10) {
		alert(item + "の桁数が多すぎます。");
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

	// ■表示行数・ページ切替表示
	public function disp_page_line($nrow) {
?>
<nobr>表示行数<select name="pageline" onchange="submit()"><?php $this->select_pageline($this->pageline); ?></select>
<input type="button" value="前頁" onclick="page.value=<?=$this->page - 1?>;submit()"<?=disabled($this->page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$this->page + 1?>;submit()"<?=disabled($this->pageline == 0 || $nrow < $this->pageline)?>></nobr>
<?php
	}

	// ■表示行数選択肢
	protected function select_pageline($selected) {
		$ary = array(10, 20, 50, 100, 200, 500);
		echo '<option ', value_selected(0, $selected), ">- 全て -</option>\n";
		foreach ($ary as $line)
			echo '<option ', value_selected($line, $selected), ">$line</option>\n";
	}

	// ■エクスポートデータ取得
	public function get_export_data($header_flag) {
		$ary = array();

		// ヘッダ
		if ($header_flag) {
			$ary2 = array();
			foreach ($this->colinfo_ary as $colinfo) {
				if ($colinfo->csv_flag)
					$ary2[] = strip_tags($colinfo->text);
			}
			$ary[] = $ary2;
		}

		// データ
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