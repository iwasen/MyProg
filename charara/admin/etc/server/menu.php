<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:サーバ別応募状況
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('etc', 'その他管理', 'サーバ別応募状況', BACK_TOP);
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
				<tr><th colspan=3 align="left" style="color=white;background-color=darkseagreen">■サーバ別応募状況メニュー</th></tr>
			  <tr>
			    <td width="33%"><a href="creo/list.php">クレオ</a></td>
			    <td width="33%"><a href=""></a></td>
			    <td width="33%"><a href=""></a></td>
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
