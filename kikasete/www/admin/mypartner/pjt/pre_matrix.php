<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'マトリクスプレビュー', BACK_TOP);

// 選択肢取得
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
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<table border="0" cellspacing="2" cellpadding="2" width="100%">
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
