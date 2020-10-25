<?
/******************************************************
' System :�������āEnet�����ǁE�G�[�W�F���g�p�y�[�W
' Content:My�p�[�g�i�[�E
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//���C������
set_global2('mypartner', 'My�p�[�g�i�[�Ǘ�', '�}�g���N�X�v���r���[', BACK_TOP);

// �I�����擾
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
		<td><?=mb_convert_kana($hno, 'N')?>�D<?=htmlspecialchars($hyoutou)?></td>
<?
}
?>
	</tr>
<?
foreach ($hyousoku_ary as $hno => $hyousoku) {
?>
	<tr bgcolor="<?=$hno % 2 ? '#ddeeff' : '#ddffee'?>">
		<td><?=mb_convert_kana($hno, 'N')?>�D<?=htmlspecialchars($hyousoku)?></td>
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
