<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �Խ��ڡ���ɽ�����饹
 *
 *
 * @package
 * @author
 * @version
 */

include_once('html.php');

class edit_class {
	protected $titie;
	protected $command;
	protected $item_ary = array();
	protected $item_cnt = 0;
	protected $js_ary = array();
	protected $check_ary = array();

	// �����󥹥ȥ饯��
	public function __construct($title = '', $type = '') {
		if ($type == 'html')
			$this->title = $title;
		elseif ($title)
			$this->title = '��' . $title;
	}

	// �������ȥ�α�ü��html�ɲ�
	public function set_command($command) {
		$this->command = $command;
	}

	// ����������
	public function set_item($item_name, $must = false) {
		$this->item_cnt = count($this->item_ary);

		$item = &$this->item_ary[$this->item_cnt];
		$item->item_name = $item_name;
		$item->must = $must;
		$item->format = $format;
		$item->data_ary = array();
	}

	// ���ե����ޥåȻ���
	public function set_format($format) {
		$item = &$this->item_ary[$this->item_cnt];
		$item->format = $format;
	}

	// ��html����
	public function set_html($html) {
		$item = &$this->item_ary[$this->item_cnt];
		$item->data_ary[] = $html;
	}

	// ��ʸ���ǡ�������
	public function set_string($text) {
		$this->set_html(htmlspecialchars($text));
	}

	// ��<input type="text">����
	public function set_text($name, $value, $class = '', $size = '', $maxlength = '') {
		$this->set_html(html_input_text($name, $value, $class, $size, $maxlength));
		$this->set_js($name, 0, $class);
	}

	// ��<input type="checkbox">����
	public function set_checkbox($name, $value, $checked = '', $text = '') {
		$this->set_html(html_input_checkbox($name, $value, $checked) . $text);
		$this->set_js($name, 1);
	}

	// ��<input type="radio">����
	public function set_radio($name, $value, $checked = '', $text = '') {
		$this->set_html(html_input_radio($name, $value, $checked) . $text);
		$this->set_js($name, 1);
	}

	// ��<input type="file">����
	public function set_file($name, $size = '', $text = '') {
		$this->set_html(html_input_file($name, $size) . $text);
		$this->set_js($name, 0);
	}

	// ��<input type="button">����
	public function set_button($value, $onclick) {
		$this->set_html(html_input_button($value, $onclick));
	}

	// ��<input type="password">����
	public function set_password($name, $value, $size = '', $maxlength = '') {
		$this->set_html(html_input_password($name, $value, $size, $maxlength));
		$this->set_js($name, 0);
	}

	// ��<textarea>����
	public function set_textarea($name, $cols, $rows, $text = '') {
		$this->set_html(html_textarea($name, $cols, $rows, $text));
		$this->set_js($name, 0);
	}

	// ��<select>����
	public function set_select($name, $option, $text = '', $size = 1, $multi = '', $onchange = '') {
		$this->set_html(html_select($name, $option, $size, $multi, $onchange) . $text);
		$this->set_js($name, 2);
	}

	// ���������
	public function set_note($note) {
		$item = &$this->item_ary[$this->item_cnt];
		$item->note = $note;
	}

	// ��<br>����
	public function set_br() {
		$item = &$this->item_ary[$this->item_cnt];
		$item->data_ary[] = '<br>';
	}

	// ���Խ�����ɽ��
	public function disp_edit($table_width = '85%', $td_width = '25%') {
		$td_width = $td_width != '' ? " width=\"$td_width\"" : '';
?>
<table border=0 cellspacing=2 cellpadding=3 width="<?=$table_width?>">
<?php
		if ($this->title != '') {
			if ($this->command != '') {
?>
  <tr>
    <td nowrap>��<?=$this->title?></td>
		<td align="right" nowrap><?=$this->command?></td>
  </tr>
<?php
			} else {
?>
  <tr>
    <td nowrap colspan=2><?=$this->title?></td>
  </tr>
<?php
			}
		}

		foreach ($this->item_ary as $item) {
			if (is_object($item->item_name)) {
?>
  <tr>
    <td colspan=2 style="padding:0px"><?php $item->item_name->disp_list()?></td>
  </tr>
<?php
			} else {
				$must = $item->must ? '<sup><font color="green">��ɬ��</font></sup>' : '';
				$data = $item->format != '' ? vsprintf($item->format, $item->data_ary) : join(' ', $item->data_ary);
				$note = $item->note != '' ? " <span class='note'>$item->note</span>" : '';
?>
  <tr>
    <td class="face"<?=$td_width?>><?=$item->item_name?><?=$must?></td>
    <td class="bg3"><?=$data?><?=$note?></td>
  </tr>
<?php
			}
		}
?>
</table>
<?php
	}

	// �����ϥ����å���JavaScript����
	protected function set_js($name, $type, $class = '') {
		$item = &$this->item_ary[$this->item_cnt];

		// ɬ�ܹ������ϥ����å�
		if ($item->must) {
			switch ($type) {
			case 0:		// text
				$this->js_ary[] = "if (!check_input(f[\"$name\"], '$item->item_name')) return false;";
				break;
			case 1:		// radio, checkbox
				if (!isset($this->check_ary[$name])) {
					$this->js_ary[] = "if (!check_checked(f[\"$name\"], '$item->item_name')) return false;";
					$this->check_ary[$name] = true;
				}
				break;
			case 2:		// select
				$this->js_ary[] = "if (!check_select(f[\"$name\"], '$item->item_name')) return false;";
				break;
			}
		}

		// ���������å�
		if ($class == 'number')
			$this->js_ary[] = "if (!check_number(f[\"$name\"], '$item->item_name')) return false;";

		// �᡼�륢�ɥ쥹�����å�
		if ($class == 'mail')
			$this->js_ary[] = "if (!check_mail(f[\"$name\"], '$item->item_name')) return false;";

		// URL�����å�
		if ($class == 'url')
			$this->js_ary[] = "if (!check_url(f[\"$name\"], '$item->item_name')) return false;";

		// ���ե����å�
		if (preg_match('/^(.+_date)_y$/', $name, $regs))
			$this->js_ary[] = "if (!check_date(f.{$regs[1]}_y, f.{$regs[1]}_m, f.{$regs[1]}_d, '$item->item_name')) return false;";
	}

	// �����ϥ����å���JavaScript����
	public function js_input_check() {
		edit_class::js_input_check_all($this);
	}

	// �������ϥ����å���JavaScript����
	public static function js_input_check_all() {
		global $top;

		$argc = func_num_args();
?>
<script type="text/javascript" language="JavaScript" src="<?=$top?>/js/input_check.js"></script>
<script type="text/javascript">
<!--
function input_check(f) {
<?php
		for ($i = 0; $i < $argc; $i++) {
			$arg = func_get_arg($i);
			foreach ($arg->js_ary as $js)
				echo "\t$js\n";
		}
?>
	return true;
}
//-->
</script>
<?php
	}

	// ���������ݡ��ȥǡ�������
	public function get_export_data($header_flag) {
		$ary = array();

		// �ǡ���
		foreach ($this->item_ary as $item) {
			$ary2 = array();
			$ary2[] = $item->item_name;
			foreach ($item->data_ary as $data)
				$ary2[] = $data;
			$ary[] = $ary2;
		}

		return $ary;
	}
}
?>