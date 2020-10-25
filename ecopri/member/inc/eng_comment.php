<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:エネルギー隊長の報告
'******************************************************/

function eng_comment($eng_type, $month, &$use, &$comp, &$rank) {
	// 未使用（rankが"08"）の時は表示しない
	if ($rank[$eng_type] == '08') {
		echo '<br>';
		return;
	}

	// 符号を取る
	$comp_abs = abs($comp[$eng_type]);

	// 増えた、減った
	if ($comp[$eng_type] > 0) {
		$up_down = '増えた';
		$color = 'red';
	} else {
		$up_down = '減った';
		$color = 'blue';
	}

	// エネルギー種別ごとのコメント表示
	switch ($eng_type) {
	case 'ele':
		$gobi1 = 'ジカ。';
		$gobi2 = 'ジカ。';
		$gobi3 = 'だったジカ！';
		$unit = 'kwh';
		break;
	case 'gas':
		$gobi1 = 'であります。';
		$gobi2 = 'であります。';
		$gobi3 = 'であります！！';
		$unit = 'm3';
		break;
	case 'wtr':
		$gobi1 = 'よ。';
		$gobi2 = 'わ！';
		$gobi3 = 'だったわよ！';
		$unit = 'm3';
		break;
	case 'oil':
		$gobi1 = 'ぞな。';
		$gobi2 = 'ぞな！';
		$gobi3 = 'だったぞな。';
		$unit = 'L';
		break;
	case 'gso':
		$gobi1 = 'ガー。';
		$gobi2 = 'ガー。';
		$gobi3 = 'だったガー。';
		$unit = 'L';
		break;
	case 'dst':
		$gobi1 = 'デスデス。';
		$gobi2 = 'デスデス！';
		$gobi3 = 'だったデスデス。';
		$unit = 'kg';
		break;
	}

	if ($comp_abs != 0)
		echo "{$month}月の使用量は&nbsp;{$use[$eng_type]}{$unit}&nbsp;{$gobi1}<br>前年と比べると&nbsp;<font color='$color'><b>{$comp_abs}{$unit}</b>&nbsp;{$up_down}</font>{$gobi2}<br>";
	else
		echo "前年と同じ{$gobi3}<br><br>";
}
?>