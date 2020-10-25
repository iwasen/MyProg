<?
// 選択肢謫ｾ
function get_sel($text) {
	$ary = array();
	$ary2 = explode("\n", $text);
	$i = 1;
	foreach ($ary2 as $sel) {
		$sel = trim($sel);
		if ($sel != '')
			$ary[$i++] = $sel;
	}
	return $ary;
}

$hyousoku_ary = get_sel($hyousoku);
$hyoutou_ary = get_sel($hyoutou);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>マトリクスプレビュー</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<table border="0" cellspacing="2" cellpadding="2" bgcolor="#ffffff" width="100%">
	<tr bgcolor="#ddffee">
		<td>&nbsp;</td>
<?
foreach ($hyoutou_ary as $hno => $hyoutou) {
?>
		<td><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyoutou)?></td>
<?
}
?>
	</tr>
<?
			foreach ($hyousoku_ary as $hno => $hyousoku) {
?>
	<tr bgcolor="<?=$hno % 2 ? '#ddeeff' : '#ddffee'?>">
		<td><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyousoku)?></td>
<?
				foreach ($hyoutou_ary as $hno => $hyoutou) {
?>
		<td></td>
<?
				}
?>
	</tr>
<?
			}
?>
</table>
</body>
</html>