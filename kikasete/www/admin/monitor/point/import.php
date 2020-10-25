<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ポイント入力画面
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/point.php");

set_global('monitor', 'モニター管理', 'ポイント入力', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (f.point_file.value == '') {
		alert("ポイント付与ファイルを指定してください。");
		f.point_file.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="import_point.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td colspan=2>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0">■ポイント付与ファイルを指定してください</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1" width="22%">ポイント付与ファイル</td>
		<td class="n1">
			<input type="file" name="point_file" size=60>
		</td>
	</tr>
  <tr>
    <td class="m1" width="22%">ポイントタイプ</td>
    <td class="n1">
    	<span class="small">
    	<input type="radio" value="<?=PT_KIRIN?>" name="point_type" checked>キリン
    	<input type="radio" value="<?=PT_JIMUKYOKU?>" name="point_type">事務局付与
    	<input type="radio" value="<?=PT_ENQUETE?>" name="point_type">アンケートポイント
    	</span>
    </td>
  </tr>
	<tr>
		<td class="m1" width="22%">退会者へのポイント付与</td>
		<td class="n1">
			<span class="small">
				<input type="radio" name="taikai_flag" value="1" checked>退会者にはポイントを付与しない（エラー扱い）<br>
				<input type="radio" name="taikai_flag" value="2">退会者にもポイントを付与する
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="22%">エラー時の処理</td>
		<td class="n1">
			<span class="small">
				<input type="radio" name="error_flag" value="1" checked>エラーがあってもポイント付与を行う<br>
				<input type="radio" name="error_flag" value="2">エラーが１件でもあればポイント付与を行わない
			</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="pset" value="1">
<input type="submit" value="アップロード">
<input type="button" value="　戻る　" onclick="location.href='menu.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
