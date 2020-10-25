<?
//=== ���̤Υƥ�����ɽ�� ===
function SeibetsuText($seibetsu) {
	switch ($seibetsu) {
	case '0':
		return '����';
	case '1':
		return '����';
	default:
		return '';
	}
}

//=== ̤�����Υƥ�����ɽ�� ===
function MikikonText($mikikon) {
	switch ($mikikon) {
	case '0':
		return '�ȿ�';
	case '1':
		return '����';
	default:
		return '';
	}
}

//=== ���ȤΥƥ�����ɽ�� ===
function ShokugyouText($shokugyou) {
	$sql = "SELECT SG_shokugyou_name FROM T_SHOKUGYOU WHERE SG_shokugyou_code='$shokugyou'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF())
		$text = $ds->GetValue('SG_shokugyou_name');
	else
		$text = '';

	return $text;
}

//=== HTML�᡼�������˾ɽ�� ===
function HtmlMailText($html_mail) {
	switch ($html_mail) {
	case '0':
		return '��˾���ʤ�';
	case '1':
		return '��˾����';
	default:
		return '';
	}
}

//=== ��̣������ɽ�� ===
function WriteKyoumiGenre($category, $kyoumi_genre) {
	$sql = "SELECT KG_index,KG_item_text FROM T_KYOUMI_GENRE WHERE KG_category='$category' ORDER BY KG_item_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	$count = 0;
	while (!$ds->EOF()) {
		if ($count % 2 == 0)
			$bgcolor = ' bgcolor="#fff6ea"';
		else
			$bgcolor = '';

		$index = $ds->GetValue('KG_index');
		switch ($kyoumi_genre{$index - 1}) {
		case '0':
			$mark = '��';
			break;
		case '1':
			$mark = '��';
			break;
		case '2':
			$mark = '��';
			break;
		}

		Writeln("<tr$bgcolor>");
		Writeln("<td width=190><font size=2>" . $ds->GetValue('KG_item_text') . "</font></td>");
		Writeln("<td width=40 align='center' valign='middle'>$mark</td>");
		Writeln("</tr>");

		$count++;
		$ds->MoveNext();
	}
}

//=== �褯�Ԥ���Źɽ�� ===
function WriteYokuikuMise($category, $yokuiku_mise) {
	$sql = "SELECT YM_index,YM_item_text FROM T_YOKUIKU_MISE WHERE YM_category='$category' ORDER BY YM_item_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	while (!$ds->EOF()) {
		$index = $ds->GetValue('YM_index');

		if ($yokuiku_mise{$index - 1} == '1') {
			Writeln("<tr>");
			Writeln("<td align='left' valign='middle'><font size=2>" . $ds->GetValue('YM_item_text') . "</font></td>");
			Writeln("</tr>");
		}

		$ds->MoveNext();
	}
}

//=== ���ʤ��Ϥɤä���ɽ�� ===
function WriteDocchiHa($docchiha) {
	$sql = "SELECT * FROM T_DOCCHI_HA ORDER BY DH_item_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	while (!$ds->EOF()) {
		$index = $ds->GetValue('DH_index');
		switch ($docchiha{$index - 1}) {
		case '1':
			Writeln("<tr>");
			Writeln("<td><font size=2>" . $ds->GetValue('DH_item1_text') . "</font></td>");
			Writeln("</tr>");
			break;
		case '2':
			Writeln("<tr>");
			Writeln("<td><font size=2>" . $ds->GetValue('DH_item2_text') . "</font></td>");
			Writeln("</tr>");
			break;
		}

		$ds->MoveNext();
	}
}

//=== ���Τ餻�᡼�������˾ɽ�� ===
function OshiraseMailText($oshirase_mail) {
	switch ($oshirase_mail) {
	case '0':
		return '������';
		break;
	case '1':
		return '�Ϥ�';
		break;
	default:
		return '';
	}
}
?>