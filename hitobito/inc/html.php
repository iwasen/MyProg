<?php
/**
 * ひとびと･net 共通ファイル
 *
 * html共通処理関数
 *
 *
 * @package
 * @author
 * @version
 */

// ■htmlタグ生成
function html_tag($tag, $attr_ary) {
	foreach ($attr_ary as $attr => $val) {
		if ($val === null)
			$tag .= " $attr";
		else
			$tag .= " $attr=\"$val\"";
	}

	return "<$tag>";
}

// ■inputタグ生成
function html_input_tag($attr_ary) {
	return html_tag('input', $attr_ary);
}

// ■<input type="text">タグ生成
function html_input_text($name, $value, $class = '', $size = '', $maxlength = '') {
	$attr_ary['type'] = 'text';
	$attr_ary['name'] = $name;
	$attr_ary['value'] = htmlspecialchars($value);

	if ($class != '')
		$attr_ary['class'] = $class;

	if ($size != '')
		$attr_ary['size'] = $size;

	if ($maxlength != '')
		$attr_ary['maxlength'] = $maxlength;

	return html_input_tag($attr_ary);
}

// ■<input type="checkbox">タグ生成
function html_input_checkbox($name, $value, $checked = '', $style = '') {
	$attr_ary['type'] = 'checkbox';
	$attr_ary['name'] = $name;
	$attr_ary['value'] = htmlspecialchars($value);

	if ($checked != '')
		$attr_ary['checked'] = null;

	if ($style != '')
		$attr_ary['style'] = $style;

	return html_input_tag($attr_ary);
}

// ■<input type="radio">タグ生成
function html_input_radio($name, $value, $checked = '', $style = '') {
	$attr_ary['type'] = 'radio';
	$attr_ary['name'] = $name;
	$attr_ary['value'] = htmlspecialchars($value);

	if ($checked == $value)
		$attr_ary['checked'] = null;

	if ($style != '')
		$attr_ary['style'] = $style;

	return html_input_tag($attr_ary);
}

// ■<input type="file">タグ生成
function html_input_file($name, $size = '') {
	$attr_ary['type'] = 'file';
	$attr_ary['name'] = $name;

	if ($size != '')
		$attr_ary['size'] = $size;

	return html_input_tag($attr_ary);
}

// ■<input type="button">タグ生成
function html_input_button($value, $onclick, $style = '') {
	$attr_ary['type'] = 'button';
	$attr_ary['value'] = htmlspecialchars($value);
	$attr_ary['onclick'] = $onclick;

	if ($style != '')
		$attr_ary['style'] = $style;

	return html_input_tag($attr_ary);
}

// ■<input type="password">タグ生成
function html_input_password($name, $value, $size = '', $maxlength = '') {
	$attr_ary['type'] = 'password';
	$attr_ary['name'] = $name;
	$attr_ary['value'] = htmlspecialchars($value);

	if ($size != '')
		$attr_ary['size'] = $size;

	if ($maxlength != '')
		$attr_ary['maxlength'] = $maxlength;

	return html_input_tag($attr_ary);
}

// ■<textarea>タグ生成
function html_textarea($name, $cols, $rows, $text = '') {
	$attr_ary['name'] = $name;
	$attr_ary['cols'] = $cols;
	$attr_ary['rows'] = $rows;

	return html_tag('textarea', $attr_ary) . htmlspecialchars($text) . '</textarea>';
}

// ■<select>タグ生成
function html_select($name, $option, $size = 1, $multi = '', $onchange = '') {
	$attr_ary['name'] = $name;
	$attr_ary['size'] = $size;

	if ($multi != '')
		$attr_ary[$multi] = null;

	if ($onchange != '')
		$attr_ary['onchange'] = $onchange;

	return html_tag('select', $attr_ary) . $option . '</select>';
}

// ■<table>タグ生成
function html_table($ary) {
	$html[] = '<table>';
	foreach ($ary as $row) {
		$html[] = '<tr>';
		foreach ($row as $data)
			$html[] = '<td valign="top">' . $data . '</td>';
		$html[] = '</tr>';
	}
	$html[] = '</table>';

	return join("\n", $html);
}
?>