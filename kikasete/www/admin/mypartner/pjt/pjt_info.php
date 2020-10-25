<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・プロジェクト設計情報
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナープロジェクト設計情報', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">

</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■○○についての会議室</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='../index.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" value="1">
<input type="hidden" name="sort_dir" value="1">
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" width="35%">■目的</td>
		<td class="m0" align="right" width="65%"><input type="button" name="" value="修正"></td>
	</tr>
	<tr>
		<td class="n1" colspan=2>目的です。目的です。目的です。目的です。目的です。目的です。目的です。目的です。</td>
	</tr>
	<tr>
		<td class="m0">■参加条件</td>
		<td class="m0" align="right"><input type="button" name="" value="修正"></td>
	</tr>
	<tr>
		<td class="n1" colspan=2>なんらかの形でボランティアに携わっている人<br>
		６０歳以上の男性<br>
		会社を退職し、現在は正社員ではない方
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■担当者情報</td>
	</tr>
	<tr>
		<td class="m1">企業ID</td>
		<td class="n1"><a href="#" title="企業情報を表示します">22</a></td>
	</tr>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1">株式会社○○○○○○</td>
	</tr>
	<tr>
		<td class="m1">担当者ID</td>
		<td class="n1"><a href="#" title="担当者情報を表示します">999</a></td>
	</tr>
	<tr>
		<td class="m1">担当者名</td>
		<td class="n1">新橋夢美</td>
	</tr>
</table>
<br>
<div align="center"><input type="button" value="　戻る　" onclick="location.href='../index.php'"></div>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
