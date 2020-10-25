<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:トップメニュー画面
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

set_global('きかせて・net エージェントトップページ', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function mr_detail(mr_id, top) {
  var win;
  win = window.open(top + "/sub/mr_detail.php?mr_id=" + mr_id, "mr_detail", "scrollbars=yes,resizable=yes,width=500,height=510");
  win.focus();
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<div align="center">
<table border=0 cellpadding=8 cellspacing=0 width="80%">
	<tr>
		<td>
<?
switch ($GLOBALS['sub_menu']) {
case "marketer":
?>
			<table border=0 cellpadding=8 cellspacing=0 width="90%">
				<tr>
					<td><b>＜担当マーケターメニュー＞</b></td>
				</tr>
			</table>
			<table border=1 cellpadding=8 cellspacing=0 width=70%>
				<tr>
					<td class="lt"><nobr>■担当マーケター一覧表示</nobr></td>
					<td align="center" width=20%><input type="button" value="実行" onclick="location.href='<?=$top?>/marketer/list/list.php'"></td>
				</tr>
				<tr>
					<td class="lt"><nobr>■マーケターからのメッセージ表示・履歴の閲覧・削除</nobr></td>
					<td align="center"><input type="button" value="実行" onclick="location.href='<?=$top?>/marketer/getmsg/list.php'"></td>
				</tr>
				<tr>
					<td class="lt"><nobr>■マーケターからへのメッセージ送信・履歴の閲覧・削除</nobr></td>
					<td align="center"><input type="button" value="実行" onclick="location.href='<?=$top?>/marketer/sendmsg/list.php'"></td>
				</tr>
			</table>
<?
	break;
case "proxy":
?>
			<table border=0 cellpadding=8 cellspacing=0 width="90%">
				<tr>
					<td><b>＜代理実施メニュー＞</b></td>
				</tr>
			</table>
			<table border=1 cellpadding=8 cellspacing=0 width=70%>
				<tr>
					<td class="lt"><nobr>■Myアンケート代理実施</nobr></td>
					<td align="center" width=25%><input type="button" value="実行" onclick="location.href='<?=$top?>/construction.php'"></td>
				</tr>
				<tr>
					<td class="lt"><nobr>■Myモニター代理実施</nobr></td>
					<td align="center"><input type="button" value="実行" onclick="location.href='<?=$top?>/construction.php'"></td>
				</tr>
				<tr>
					<td class="lt"><nobr>■Myサーチ代理実施</nobr></td>
					<td align="center"><input type="button" value="実行" onclick="location.href='<?=$top?>/construction.php'"></td>
				</tr>
			</table>
<?
	break;
case "project":
?>
			<table border=0 cellpadding=8 cellspacing=0 width="90%">
				<tr>
					<td><b>＜プロジェクトメニュー＞</b></td>
				</tr>
			</table>
			<table border=1 cellpadding=8 cellspacing=0 width=70%>
				<tr>
					<td class="lt"><nobr>■プロジェクト一覧表示</nobr></td>
					<td align="center" width=20%><input type="button" value="実行" onclick="location.href='<?=$top?>/project/list.php'"></td>
				</tr>
				<tr>
					<td class="lt"><nobr>■新規プロジェクトの作成</nobr></td>
					<td align="center"><input type="button" value="実行" onclick="location.href='<?=$top?>/construction.php'"></td>
				</tr>
				<tr>
					<td class="lt"><nobr>■マスタの作成・修正</nobr></td>
					<td align="center"><input type="button" value="実行" onclick="location.href='<?=$top?>/construction.php'"></td>
				</tr>
				<tr>
					<td class="lt"><nobr>■プロジェクトの実行ログ</nobr></td>
					<td align="center"><input type="button" value="実行" onclick="location.href='<?=$top?>/construction.php'"></td>
				</tr>
			</table>
<?
	break;
}
?>
		</td>
	</tr>
</table>
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
