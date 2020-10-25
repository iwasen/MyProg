<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:エコ診断用共通関数
'******************************************************/

// 逆効果時のアラートメッセージ
define('SIM_ALERT', 'ちょっと増えちゃうみたい。逆効果だゾウ<br><br>');

// ラジオボタン表示（配列）
function sim_radio_ary($name, $ary, $default, $eco = '', $prefix = '', $postfix = ' ') {
	if ($eco != '')
		$on = ' onclick="select_list(' . "'$eco'" . ', value)"';

	foreach ($ary as $value => $text)
		echo $prefix, '<nobr>', '<input type="radio" name="', $name, '" ', value_checked($value, $default), $on, '>',  $text, $postfix, '</nobr>';
}

// プルダウン選択表示（数字）
function sim_select_no($name, $min, $max, $default, $eco = '', $prefix = '', $postfix = '', $step = 1) {
	if ($eco != '')
		$on = ' onchange="select_list(' . "'$eco'" . ', value)"';

	echo "<select name='$name'$on>";
	for ($i = $min; $i <= $max; $i += $step)
		echo '<option ', value_selected($i, $default), '>', $prefix, $i, $postfix, '</option>';
	echo '</select>';
}

// プルダウン選択表示（配列）
function sim_select_ary($name, &$ary, $default, $eco = '', $on = '') {
	if ($eco != '')
		$on = ' onchange="select_list(' . "'$eco'" . ', value)"';

	echo "<select name='$name'$on>";
	foreach ($ary as $value => $text)
		echo '<option ', value_selected($value, $default), '>', $text, '</option>';
	echo '</select>';
}

// 数字表示
function sim_number($num) {
	if ($num >= 0)
		echo number_format($num);
	else
		echo '<font color="red">', number_format($num), '</font>';
}

// 湯沸し方法名称取得
function yuwakashi_houhou_name($key = null) {
	$ary = array(
		1 => '電気ポット',
		2 => 'やかん（ガス）',
		3 => 'やかん（IH）'
	);
	return $key == null ? $ary : $ary[$key];
}

// 保温方法名称取得
function hoon_houhou_name($key = null) {
	$ary = array(
		1 => '電気ポット',
		2 => '魔法瓶'
	);
	return $key == null ? $ary : $ary[$key];
}

// 保温時間名称配列
function hoon_jikan_name($key = null) {
	$ary = array(
		0  => '保温しない',
		24 => '一日中',
		16 => '就寝時以外',
		8  => '朝と晩',
		4  => '晩のみ'
	);
	return $key == null ? $ary : $ary[$key];
}

// 乾燥機種類名称取得
function kansou_shurui_name($key = null) {
	$ary = array(
		1 => '電気式衣類乾燥機',
		2 => 'ガス式衣類乾燥機',
		3 => '電気式洗濯乾燥機',
		4 => '所有なし'
	);
	return $key == null ? $ary : $ary[$key];
}

// 乾燥方法名称取得
function kansou_houhou_name($key = null) {
	$ary = array(
		1 => '乾燥機は使用しない',
		2 => '乾燥機で全乾燥（洗濯物の一部のみも含む）',
		3 => '乾燥機でやや乾燥させた後干す',
		4 => '干した後、生乾きのものは乾燥機を使う'
	);
	return $key == null ? $ary : $ary[$key];
}

// 冷房種類名称取得
function reibou_shurui_name($key = null) {
	$ary = array(
		1 => '普及型',
		2 => '省エネ型'
	);
	return $key == null ? $ary : $ary[$key];
}

// 暖房種類名称取得
function danbou_shurui_name($key = null) {
	$ary = array(
		1 => 'エアコン普及型',
		2 => 'エアコン省エネ型',
		3 => 'ガスファンヒーター',
		4 => '石油ファンヒーター',
		5 => 'ガス温水式床暖房',
		6 => 'ホットカーペット',
		7 => 'こたつ',
		8 => '補助暖房なし'
	);
	return $key == null ? $ary : $ary[$key];
}

// 照明場所名称取得
function shoumei_basho_name($key = null) {
	$ary = array(
		1 => 'LD',
		2 => '台所',
		3 => '和室',
		4 => '廊下',
		5 => '玄関ホール'
	);
	return $key == null ? $ary : $ary[$key];
}

// 残り湯の使用方法名称取得
function nokoriyu_name($key = null) {
	$ary = array(
		1 => '使用しない',
		2 => '洗いのみ使用',
		3 => '洗いとすすぎ1回使用 '
	);
	return $key == null ? $ary : $ary[$key];
}
?>