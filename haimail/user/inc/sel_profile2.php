<?
//=== 年の選択肢出力 ===
function WriteYear($sei_nen) {
	$nen = date('Y') - 10;
	for ($i = 1900; $i <= $nen; $i++)
		Writeln("<option" . Selected(trim($sei_nen), $i) . ">$i</option>");
}

//=== 月の選択肢出力 ===
function WriteMonth($sei_getsu) {
	for ($i = 1; $i <= 12; $i++)
		Writeln("<option" . Selected(trim($sei_getsu), $i) . ">$i</option>");
}

//=== 日の選択肢出力 ===
function WriteDay($sei_hi) {
	for ($i = 1; $i <= 31; $i++)
		Writeln("<option" . Selected(trim($sei_hi), $i) . ">$i</option>");
}

//=== 職業の選択肢出力 ===
function WriteShokugyou($shokugyou) {
	$sql = "SELECT * FROM T_SHOKUGYOU ORDER BY SG_seq_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	while (!$ds->EOF()) {
		Writeln("<option value='" . $ds->GetValue('SG_shokugyou_code') . "'" . Selected($shokugyou, $ds->GetValue('SG_shokugyou_code')) . ">" . $ds->GetValue('SG_shokugyou_name') . "</option>");
		$ds->MoveNext();
	}
}

//=== よく行くお店選択肢出力 ===
function WriteYokuikuMise($category, $yokuiku_mise) {
	$sql = "SELECT * FROM T_YOKUIKU_MISE WHERE YM_category='$category' AND YM_valid_flag='1' ORDER BY YM_item_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	$count = 0;
	while (!$ds->EOF()) {
		if ($count % 2 == 0)
			$bgcolor = ' bgcolor="#fff6ea"';
		else
			$bgcolor = '';

		$index = $ds->GetValue('YM_index');
		$checked1 = Checked($yokuiku_mise{$index - 1});

		Writeln("<tr$bgcolor>");
		Writeln("<td width=22 align='center' valign='middle'><input type='checkbox' value='1' name='yokuiku_mise$index'$checked1> </td>");
		Writeln("<td align='left' valign='middle'><font size=2>" . $ds->GetValue('YM_item_text') . "</font></td>");
		Writeln("</tr>");

		$count++;
		$ds->MoveNext();
	}
}

//=== あなたはどっち派選択肢出力 ===
function WriteDocchiHa($docchiha) {
	$sql = "SELECT * FROM T_DOCCHI_HA WHERE DH_valid_flag='1' ORDER BY DH_item_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	$count = 0;
	while (!$ds->EOF()) {
		if ($count % 2 == 0)
			$bgcolor = ' bgcolor="#fff6ea"';
		else
			$bgcolor = '';

		$index = $ds->GetValue('DH_index');
		$name = "docchiha$index";
		$checked0 = RadioChecked($docchiha{$index - 1}, '0');
		$checked1 = RadioChecked($docchiha{$index - 1}, '1');
		$checked2 = RadioChecked($docchiha{$index - 1}, '2');

		Writeln("<tr" . bgcolor . ">");
		Writeln("<td width=166><font size=2><input type='radio' name='$name' value='1'$checked1>" . $ds->GetValue('DH_item1_text') . "</font></td>");
		Writeln("<td width=25>vs</td>");
		Writeln("<td width=155><font size=2><input type='radio' name='$name' value='2'$checked2>" . $ds->GetValue('DH_item2_text') . "</font></td>");
		Writeln("<td width=122><font size=2><input type='radio' name='$name' value='0'$checked0>どちらでもない</font></td>");
		Writeln("</tr>");

		$count++;
		$ds->MoveNext();
	}
}
?>