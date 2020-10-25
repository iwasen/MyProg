<?php
/**
 * ひとびと･net 共通ファイル
 *
 * グラフ表示クラス
 *
 *
 * @package
 * @author
 * @version
 */

class graph_class {
	protected $year;
	protected $month;
	protected $data_ary = array();
	protected $row_ary = array();
	protected $col_ary = array();
	protected $bar_row_ary = array();
	protected $row_bar_ary = array();
	protected $color_ary = array(0 => 'black', 1 => 'royalblue', 2 => 'darkmagenta');
	protected $bg_color = '#eeffee';
	protected $max_data = 0;
	protected $height = 300;
	protected $bar_width = 10;
	protected $header_ary = array();
	protected $disp_ary = array();

	// ■コンストラクタ
	public function __construct($year, $month) {
		$this->year = $year;
		$this->month = $month;
	}

	// ■縦軸の名称を設定
	public function set_row($row, $title, $bar = 0) {
		// 縦軸名称保存
		$this->row_ary[$row] = $title;

		// グラフ対象行位置
		$this->row_bar_ary[$row] = $bar;
		if ($bar != 0)
			$this->bar_row_ary[$bar] = $row;
	}

	// ■データをセット
	public function set_data($row, $col, $data) {
		// データ保存
		$this->data_ary[$row][$col] = $data;

		// 最大値取得
		$this->max_data = max($this->max_data, $data);
	}

	// ■凡例表示
	public function disp_legend() {
?>
<table border=0 cellspacing=0 cellpadding=0>
  <tr>
<?php
		foreach ($this->bar_row_ary as $bar => $row) {
?>
    <td><font size="-1"><?=$this->row_ary[$row]?>：</font></td>
    <td>
      <table width=20 height=10 border=1 cellspacing=0 cellpadding=0 bgcolor="<?=$this->color_ary[$bar]?>" frame="box">
        <tr>
          <td></td>
        </tr>
      </table>
    </td>
		<td width=20></td>
<?php
		}
?>
  </tr>
</table>
<?php
	}

	// ■グラフ表示
	public function disp_graph() {
		// スケール調整
		if ($this->max_data > 0) {
			$div = pow(10, floor(log10($this->max_data)));
			if ($div >= 10) {
				if ($this->max_data < $div * 2)
					$div /= 5;
				elseif ($this->max_data < $div * 5)
					$div /= 2;
			}
		} else
			$div = 1;
?>
	<tr>
		<td valign="top">
			<table width="100%" border=0 cellspacing=0 cellpadding=0>
<?php
		for ($i = 0; $i < 10; $i++) {
?>
				<tr><td align='right' valign='top' height=30 style="padding-right:4"><?=(10 - $i) * $div?></td></tr>
<?php
}
?>
			</table>
		</td>
		<td width="100%" valign="top">
			<table height=7 border=0 cellspacing=0 cellpadding=0 frame="box"><tr><td></td></tr></table>
			<table width="100%" border=0 cellspacing=0 cellpadding=0 bgcolor="<?=$this->bg_color?>" style="border:1 solid black">
				<tr>
					<td width=6>
						<table width="100%" border=0 cellspacing=0 cellpadding=0 frame="box">
<?php
		for ($i = 0; $i < 10; $i++) {
?>
							<tr><td height=30 style="border-top:1 solid gray"></td></tr>
<?php
		}
?>
						</table>
					</td>
					<td>
						<table width="100%" border=0 cellspacing=0 cellpadding=0>
							<tr>
<?php
		foreach (array_keys($this->col_ary) as $col) {
?>
								<td width="3%" height=<?=$this->height?> align="center" valign="bottom">
									<table border=0 cellspacing=0 cellpadding=0>
										<tr>
<?php
			foreach ($this->bar_row_ary as $bar => $row) {
?>
											<td valign="bottom">
<?php
				if ($this->data_ary[$row][$col] > 0 && $this->disp_ary[$col]) {
?>
												<table width=<?=$this->bar_width?> height=<?=floor($this->data_ary[$row][$col] / ($div * 10) * $this->height)?> border=1 cellspacing=0 cellpadding=0 frame="box" bgcolor="<?=$this->color_ary[$bar]?>"><tr><td style="border-bottom:0"></td></tr></table>
<?php
				}
?>
											</td>
<?php
			}
?>
										</tr>
									</table>
								</td>
<?php
		}
?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%" border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td width=6></td>
					<td>
						<table width="100%" border=0 cellspacing=0 cellpadding=0>
							<tr>
<?php
		foreach ($this->col_ary as $col_name) {
?>
								<td width="3%" align="center" height=30><?=$col_name?></td>
<?php
		}
?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellspacing=0 cellpadding=1>
<?php
		foreach ($this->row_ary as $row_name) {
?>
				<tr>
					<td align="right">
						<nobr><font size="-1"><?=$row_name?></font></nobr>
					</td>
				</tr>
<?php
		}
?>
			</table>
		</td>
		<td>
			<table width="100%" border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td width=6></td>
					<td>
						<table width="100%" border=1 cellspacing=0 cellpadding=0 frame="box">
<?php
		foreach (array_keys($this->row_ary) as $row) {
?>
							<tr>
<?php
			foreach (array_keys($this->col_ary) as $col) {
				$bar = $this->row_bar_ary[$row];
				if ($bar != 0) {
					$b1 = '<b>';
					$b2 = '</b>';
				} else {
					$b1 = '';
					$b2 = '';
				}
?>
								<td width="3%" align="center">
<?php
				if ($this->disp_ary[$col]) {
?>
									<font size="-1" color="<?=$this->color_ary[$bar]?>"><?=$b1?><?=(int)$this->data_ary[$row][$col]?><?=$b2?></font>
<?php
				} else {
?>
									-
<?php
				}
?>
								</td>
<?php
			}
?>
							</tr>
<?php
		}
?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	}

	// ■エクスポートデータ取得
	public function get_export_data($header_flag) {
		$ary = array();

		// ヘッダ
		if ($header_flag) {
			$ary2 = array();
			$ary2[] = '';
			foreach ($this->header_ary as $header)
				$ary2[] = $header;
			$ary[] = $ary2;
		}

		// データ
		foreach (array_keys($this->row_ary) as $row) {
			$ary2 = array();
			$ary2[] = $this->row_ary[$row];
			foreach (array_keys($this->col_ary) as $col)
				$ary2[] = $this->disp_ary[$col] ? (int)$this->data_ary[$row][$col] : '-';
			$ary[] = $ary2;
		}

		return $ary;
	}
}

// 月別グラフ
class monthly_graph_class extends graph_class {
	protected $link;
	protected $title;

	// ■コンストラクタ
	public function __construct($year, $link = '', $title = '') {
		parent::__construct($year, 1);
		$this->link = $link;
		$this->title = $title;

		// 横軸設定
		$this->set_cols();
	}

	// ■横軸設定
	protected function set_cols() {
		$now_ym = date('Ym');
		$y = $this->year;
		$m = 1;
		for ($i = 1; $i <= 24; $i++) {
			$ym = sprintf('%04d%02d', $y, $m);
			$y2 = $y - 2000;
			if ($y2 < 0)
				$y2 += 100;
			$this->col_ary[$ym] = "<font size='1'>" . sprintf('%02d/%d', $y2, $m) . '</font>';
			if ($this->link)
				$this->col_ary[$ym] = "<a href='$this->link&year=$y&month=$m' title='$this->title'>" . $this->col_ary[$ym] . '</a>';

			$this->disp_ary[$ym] = ($ym <= $now_ym);

			$this->header_ary[] = sprintf('%04d/%d', $y, $m);

			if (++$m > 12) {
				$y++;
				$m = 1;
			}
		}
	}
}

// 日別グラフ
class daily_graph_class extends graph_class {
	// ■コンストラクタ
	public function __construct($year, $month) {
		parent::__construct($year, $month);

		// 横軸設定
		$this->set_cols();
	}

	// ■横軸設定
	protected function set_cols() {
		$now_ymd = date('Ymd');

		// 最大値
		$max = date('t', mktime(0, 0, 0, $this->month, 1 , $this->year));

		$ary = array('<font color="red">日</font>', '月', '火', '水', '木', '金', '<font color="blue">土</font>');
		for ($i = 1; $i <= $max; $i++) {
			$this->col_ary[$i] = '<font size=2>' . $i . '<br>' . $ary[date('w', mktime(0, 0, 0, $this->month, $i , $this->year))] . '</font>';

			$this->disp_ary[$i] = (sprintf('%04d%02d%02d', $this->year, $this->month, $i) <= $now_ymd);

			$this->header_ary[] = $i;
		}
	}
}
?>