<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:画像ファイルアップロード
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// アップロード最大数
$upload_num = 4;

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
	var i;
	for (i = 0; i < <?=$upload_num?>; i++) {
		if (f['upload_file[]'][i].value != "")
			break;
	}
	if (i == <?=$upload_num?>) {
		alert("画像ファイル名を入力してください。");
		f['upload_file[]'][0].focus();
		return false;
	}
	return confirm("画像ファイルをアップロードします。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1['upload_file[]'][0].focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="upload_image2.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■画像ファイルを入力してください。</td>
	</tr>
<?
for ($i = 0; $i < $upload_num; $i++) {
?>
	<tr>
<?
	if ($i == 0) {
?>
		<td class="m1" width="20%" rowspan="<?=$upload_num?>">画像ファイルの設定<br>（<?=$upload_num?>個まで）</td>
<?
	}
?>
		<td class="n1">
			<input type="file" name="upload_file[]" size=80>
		</td>
	</tr>
<?
}
?>
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
