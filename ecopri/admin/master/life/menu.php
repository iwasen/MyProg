<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:マスタメンテナンス｜住居関連マスタ
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('master', 'マスタ管理', '住居関連マスタ', BACK_TOP);
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
				<tr><th colspan=3 align="left" style="color=white;background-color=#4682B4">■住居関連マスタメンテナンスメニュー</th></tr>
			  <tr>
			    <td width="33%"><a href="family_ctg/list.php">家族カテゴリマスタ</a></td>
			    <td width="33%"><a href="family_rel/list.php">続柄マスタ</a></td>
			    <td width="33%"><a href="keitai/list.php">住居形態マスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="sozai/list.php">住居素材マスタ</a></td>
			    <td><a href="space/list.php">住居面積マスタ</a></td>
			    <td><a href="room/list.php">間取りマスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="chikunen/list.php">建設年次マスタ</a></td>
			    <td><a href="boiler/list.php">給湯器タイプマスタ</a></td>
			    <td><a href="gas_kind/list.php">ガス種マスタ</a></td>
			  <tr>
			    <td><a href="gas_type/list.php">ガスタイプマスタ</a></td>
			    <td></td>
			    <td></td>
			  </tr>
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
