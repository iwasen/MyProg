<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:マスタメンテナンス｜地域マスタ
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('master', 'マスタ管理', '地域マスタ', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>

<div align="center">
<table border=0 cellpadding=8 cellspacing=0 width="100%">
	<tr>
		<td>
			<table width="90%" align="center" <?=LIST_TABLE?>>
				<tr><th colspan=3 align="left" style="color=white;background-color=#4682B4">■地域マスタメンテナンスメニュー</th></tr>
			  <tr>
			    <td width="33%"><a href="area_class/list.php">地域区分マスタ</a></td>
			    <td width="33%"><a href="todoufuken/list.php">都道府県マスタ</a></td>
			    <td width="33%"><a href="city/list.php">都市名マスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href=""></a><br></td>
			    <td><a href=""></a><br></td>
			    <td><a href=""></a><br></td>
			  </tr>
			</table>
		</td>
	</tr>
</table>
<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
</div>

<? page_footer() ?>

</body>
</html>
