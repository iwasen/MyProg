<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:継続リレーションメール挨拶文変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$vol_no = $_GET['vol_no'];

// 挨拶文一覧取得
$sql = "SELECT rlg_contents FROM t_relation_greeting WHERE rlg_vol_no=$vol_no";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.body.value == "") {
		alert("挨拶文を入力してください。");
		f.body.focus();
		return false;
	}
	return confirm("挨拶文を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.body.focus()">

<? center_header('マスタメンテナンス｜継続リレーションメール｜挨拶文') ?>

<div align="center">
<form method="post" name="form1" action="greeting_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■継続リレーションメールの挨拶文を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">VOL</td>
		<td class="n1"><input type="hidden" name="vol_no" <?=value($vol_no)?>><?=$vol_no?></td>
	</tr>
	<tr>
		<td class="m1">挨拶文</td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=30><?=htmlspecialchars($fetch->rlg_contents)?></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
</form>
</div>

<? center_footer() ?>

</body>
</html>
