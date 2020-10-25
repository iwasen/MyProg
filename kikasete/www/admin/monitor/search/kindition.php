<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニター数検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/kindition.php");

//メイン処理
set_global('monitor', 'モニター管理', 'モニター数検索', BACK_TOP);

$search = new search_class;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? search_script() ?>
</head>
<body onload="show_reset()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="result.php" onsubmit="return check_search_data(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■検索条件</td>
	</tr>
<? search_condition($search) ?>
</table>

<br>
<input type="submit" value="　検索　">
<input type="button" value="リセット" onclick="document.form1.reset();show_reset()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
