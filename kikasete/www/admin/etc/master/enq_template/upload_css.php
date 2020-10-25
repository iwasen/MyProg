<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:CSSファイルアップロード
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', 'アンケートメールテンプレート', BACK_TOP);

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
	if (f.upload_file.value == "") {
		alert("CSSファイル名を入力してください。");
		f.upload_file.focus();
		return false;
	}
	return confirm("CSSファイルをアップロードします。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.upload_file.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="upload_css2.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■CSSファイルを入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">CSSファイルの設定</td>
		<td class="n1">
			<input type="file" name="upload_file" size=80>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="アップロード">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="job_id" value="<?=$job_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
