<?
/******************************************************
' System :fletteスタッフページ
' Content:選択肢表示処理
'******************************************************/

// <option>タグ出力
function option_tag($value, $selected, $display) {
	echo '<option ', value_selected($value, $selected), '>', $display, '</option>', "\n";
}

// 時間選択
function select_hour($selected) {
	option_tag('', $selected, '--');
	for ($i = 0; $i < 24; $i++) {
		$s = sprintf('%02d', $i);
		option_tag($s, $selected, $s);
	}
}

// 分選択
function select_minute($selected) {
	option_tag('', $selected, '--');
	for ($i = 0; $i < 60; $i += 5) {
		$s = sprintf('%02d', $i);
		option_tag($s, $selected, $s);
	}
}

// スタッフ権限
function select_staff_auth($selected) {
	if ($selected == '')
		option_tag('', $selected, '選択してください');

	option_tag('1', $selected, '管理者スタッフ');
	option_tag('2', $selected, '店舗スタッフ');
}

// スタッフステータス
function select_staff_status($selected) {
	option_tag('1', $selected, '有効');
	option_tag('9', $selected, '無効');
}
?>