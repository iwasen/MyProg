<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Proアンケート料金表マスタ新規登録画面
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// メイン処理
set_global('proenquete', 'Ｐｒｏアンケート管理｜マスタメンテナンス', '料金表管理', BACK_TOP);

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
	if (f.import_file.value == "") {
		alert("ファイル名を入力してください。");
		f.import_file.focus();
		return false;
	}
	return confirm("ファインディング料金表をインポートします。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.import_file.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■ファインディング料金表のCSVファイル名を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ファイル名<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="file" name="import_file" size=70>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action" value="import">
</form>
</div>

<? page_footer() ?>
</body>
</html>
