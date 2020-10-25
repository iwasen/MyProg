<?
//=== 興味ジャンル選択肢出力 ===
function WriteKyoumiGenre($category, $kyoumi_genre) {
	$sql = "SELECT * FROM T_KYOUMI_GENRE WHERE KG_category='$category' AND KG_valid_flag='1' ORDER BY KG_item_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	$count = 0;
	while (!$ds->EOF()) {
		if ($count % 2 == 0)
			$bgcolor = ' bgcolor="#fff6ea"';
		else
			$bgcolor = '';

		$index = $ds->GetValue('KG_index');
		$name = "kyoumi$index";
		$checked0 = RadioChecked($kyoumi_genre{$index - 1}, '0');
		$checked1 = RadioChecked($kyoumi_genre{$index - 1}, '1');
		$checked2 = RadioChecked($kyoumi_genre{$index - 1}, '2');

		Writeln("<tr$bgcolor>");
		Writeln("<td width=200><font size=2>" . $ds->GetValue('KG_item_text') . "</font></td>");
		Writeln("<td width=30 align='center' valign='middle'>");
		Writeln("<center>");
		Writeln("<input type='radio' name='$name' value='2'$checked2></center>");
		Writeln("</td>");
		Writeln("<td width=30 align='center' valign='middle'>");
		Writeln("<center>");
		Writeln("<b><input type='radio' name='$name' value='1'$checked1></b></center>");
		Writeln("</td>");
		Writeln("<td width=30 align='center' valign='middle'>");
		Writeln("<center>");
		Writeln("<input type='radio' name='$name' value='0'$checked0></center>");
		Writeln("</td>");
		Writeln("</tr>");

		$count++;
		$ds->MoveNext();
	}
}
?>