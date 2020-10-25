<?php
/**
 * ひとびと･net 共通ファイル
 *
 * プレビュー表示クラス
 *
 *
 * @package
 * @author
 * @version
 */

class view_class {
	protected $titie;
	protected $item_ary = array();
	protected $item_cnt = 0;

	// ■コンストラクタ
	public function __construct($title = '') {
		$this->title = $title;
	}

	// ■グループ設定
	public function set_group($group_name, $edit_url = '', $class = '', $checked = false, $anchor = '') {
		$this->item_cnt = count($this->item_ary);

		$item = &$this->item_ary[$this->item_cnt];
		$item->group_name = $group_name;
		$item->edit_url = $edit_url;
		$item->class = $class != '' ? $class : 'head';
		$item->checked = $checked;
		$item->anchor = $anchor;
		$item->data_ary = array();
	}

	// ■表示データ設定（テキスト）
	public function set_data($item_name, $data, $type = 'text') {
		$item = &$this->item_ary[$this->item_cnt];
		$i = count($item->data_ary);
		$item->data_ary[$i]->item_name = $item_name;
		$item->data_ary[$i]->data = $data;
		$item->data_ary[$i]->type = $type;
	}

	// ■表示データ設定（html）
	public function set_html($item_name, $html) {
		$this->set_data($item_name, $html, 'html');
	}

	// ■表示データ設定（コールバック関数）
	public function set_func($item_name, $func) {
		$this->set_data($item_name, $func, 'func');
	}

	// ■プレビュー画面表示
	public function disp_view($table_width = '85%', $td_width = '25%') {
		$td_width = $td_width != '' ? " width=\"$td_width\"" : '';
?>
<script type="text/javascript">
<!--
function set_view_display() {
<?php
		foreach (array_keys($this->item_ary) as $i) {
?>
	document.getElementById("_view<?=$i?>").style.display = document.getElementById("_check<?=$i?>").checked ? "" : "none";
<?php
		}
?>
}
//-->
</script>
<table border=0 cellspacing=1 cellpadding=1 width="<?=$table_width?>">
<?php
		if ($this->title != '') {
?>
  <tr>
    <td colspan=2>■<?=$this->title?></td>
  </tr>
<?php
		}

		foreach ($this->item_ary as $i => $item) {
?>
	<tr>
		<td class="m1" colspan=2>
<?php
			if ($item->anchor != '') {
?>
			<a name="<?=$item->anchor?>"></a>
<?php
			}
?>
			<table border=0 cellspacing=0 cellpadding=3 width="100%">
				<tr>
					<td class="<?=$item->class?>"><input type="checkbox" id="_check<?=$i?>" name="check<?=$i?>" value="1" onclick="set_view_display()" <?=checked($item->checked)?>><?=htmlspecialchars($item->group_name)?></td>
<?php
			if ($item->edit_url != '') {
?>
					<td align="right" class="<?=$item->class?>"><input type="button" value="　修正　" onclick="location.href='<?=$item->edit_url?>'"></td>
<?php
			}
?>
				</tr>
			</table>
		</td>
	</tr>
	<tr id="_view<?=$i?>" style="display:'none'">
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width="100%" frame="box">
<?php
			foreach ($item->data_ary as $data) {
?>
				<tr>
					<td class="face"<?=$td_width?>><?=$data->item_name?></td>
<?php
				switch ($data->type) {
				case 'text':
?>
					<td class="chart"><tt><?=nl2br(htmlspecialchars($data->data))?></tt></td>
<?php
					break;
				case 'html':
?>
					<td class="chart"><?=$data->data?></td>
<?php
					break;
				case 'func':
					$func = $data->data;
?>
					<td class="chart"><?php $func() ?></td>
<?php
					break;
				}
?>
				</tr>
<?php
			}
?>
			</table>
		</td>
	</tr>
<?php
		}
?>
</table>
<?php
	}
}
?>