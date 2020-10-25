<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・対象人数検索結果
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', '対象人数検索結果', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<div align="center">
<table width=200 border=1>
	<tr>
		<td class="m2" align="center">対象人数検索結果</td>
	</tr>
	<tr>
		<td class="n1" align="center"><?=number_format($n)?>人</td>
	</tr>
</table>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</div>
</body>
</html>
