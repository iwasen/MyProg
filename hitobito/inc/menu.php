<?php
/**
 * ひとびと･net 共通ファイル
 *
 * メニュー表示クラス
 *
 *
 * @package
 * @author
 * @version
 */

// メニュー表示クラス
class menu_class {
	protected $category = 0;
	protected $menu_ary = array();

	// ■コンストラクタ
	public function __construct() {
	}

	// ■カテゴリ設定
	public function set_category($text, $class = '') {
		$this->category = count($this->menu_ary);

		$menu = &$this->menu_ary[$this->category];
		$menu->text = $text;
		$menu->class = $class;
		$menu->menu = array();
	}

	// ■メニュー項目設定
	public function set_menu($text, $url) {
		$index = count($this->menu_ary[$this->category]->menu);

		$menu = &$this->menu_ary[$this->category]->menu[$index];
		$menu->text = $text;
		$menu->url = $url;
	}

	// ■メニュー表示
	public function disp_menu() {
		$cols = 3;
?>
<table align="center" border=0 cellpadding=8 cellspacing=0 width="90%">
<?php
		foreach ($this->menu_ary as $category) {
?>
	<tr>
		<td>
      <table width="100%" border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box">
        <tr>
          <th class="head" colspan=3 align="left"><?=$category->text?></th>
        </tr>
<?php
			$i = 0;
			foreach ($category->menu as $menu) {
				if ($i % $cols == 0) {
?>
        <tr>
<?php
				}

				if ($menu->url != '') {
?>
          <td width="33%"><a href="<?=$menu->url?>"><?=$menu->text?></a></td>
<?php
				} else {
?>
          <td width="33%"><?=$menu->text?></td>
<?php
				}

				if (++$i % $cols == 0) {
?>
        </tr>
<?php
				}
			}

			if ($i % $cols) {
				while ($i % $cols) {
?>
					<td width="33%"><br></td>
<?php
					$i++;
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