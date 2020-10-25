<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:トップメニュー画面
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('工事中', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellpadding=8 cellspacing=0 width="100%">
	<tr>
		<td align="center"><IMG src="<?=$top?>/images/koujityu.gif"></td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
