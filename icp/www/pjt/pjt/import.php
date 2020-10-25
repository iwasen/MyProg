<?
/******************************************************
' System :ICPプロジェクトページ
' Content:プロジェクトインポートページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>　icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (f.import_file.value == "") {
		alert("インポートファイル名を入力してください。");
		f.import_file.focus();
		return false;
	}
	return true;
}
//-->
	</script>
</head>

<body>
<div align="center">

<? pjt_header('close') ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="ホーム">ホーム</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		プロジェクト情報
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		メンバーリスト
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		インポート
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 新規ジョブ -->

<!-- main -->
<form method="post" name="form1" action="import_chk.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="0" width="760" class="contents">
		<tr>
			<td>■プロジェクトメンバーリストにインポートするファイルを選んでください。</td>
		</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="20" width="760" class="contents">
		<tr>
			<td><input type="file" name="import_file" size="40" class="form"></td>
		</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="0" width="760" class="contents">
		<tr>
			<td align="center"><input type="submit" value="インポート" class="form"></td>
		</tr>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
</form>
<!-- main -->

<? pjt_footer() ?>

</div>
</body>

</html>
