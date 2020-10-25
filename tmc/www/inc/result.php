<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:引継ぎ情報関連
'******************************************************/

// その他関心のある車
function select_sonota_kanshin($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, 'ある');
	option_tag('2', $selected, 'ない');
	option_tag('3', $selected, '不明');
}

// 関心ありメーカー
function select_kanshin_maker($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, 'トヨタ');
	option_tag('2', $selected, '日産');
	option_tag('3', $selected, 'ホンダ');
	option_tag('4', $selected, '三菱');
	option_tag('5', $selected, 'マツダ');
	option_tag('6', $selected, 'スバル');
	option_tag('7', $selected, 'イスズ');
	option_tag('8', $selected, 'ダイハツ');
	option_tag('9', $selected, 'スズキ');
	option_tag('0', $selected, 'その他');
}

// 次週イベント誘致
function select_jishuu_enent($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, 'スタッフフォロー許諾');
	option_tag('2', $selected, '案内送付のみ許諾');
	option_tag('3', $selected, '案内不要');
	option_tag('4', $selected, '関心無し');
}

// 情報提供の継続
function select_teikyou_keizoku($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, '許諾');
	option_tag('2', $selected, '希望せず');
}

// お店への要望
function select_mise_youbou($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, 'あり');
	option_tag('2', $selected, 'なし');
}

// 決着区分
function select_kecchaku($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('3', $selected, 'リスト不備');
	option_tag('6', $selected, 'Ｍａｉｌ');
}

// ランク
function select_rank($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('S', $selected, 'S：商談ができる');
	option_tag('A', $selected, 'A：商談につながる');
	option_tag('B', $selected, 'B：取引につながる');
	option_tag('D', $selected, 'D：ゆるやかな継続フォロー了承');
	option_tag('E', $selected, 'E：フォロー拒否');
	option_tag('J', $selected, 'J：自社客');
	option_tag('M', $selected, 'M：フォロー停止');
}

// 購入状況
function select_kounyuu_joukyou($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, '自社');
	option_tag('2', $selected, 'トヨタ他チャンネル');
	option_tag('3', $selected, '他メーカー');
}
?>