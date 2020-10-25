<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:継続リレーションメール投げかけテーマ部更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$vol_no = $_POST['vol_no'];
$car_category_cd = $_POST['car_category_cd'];
$theme = $_POST['theme'];
$body = trim_crlf($_POST['body']);

$table = 't_relation_theme';

// 投げかけテーマ部を新規登録または更新
$sql = "SELECT COUNT(*) FROM $table WHERE rlt_vol_no=$vol_no AND rlt_car_category_cd=$car_category_cd";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0)) {
	$rec['rlt_theme'] = sql_char($theme);
	$rec['rlt_contents'] = sql_char($body);
	db_update($table, $rec, "rlt_vol_no=$vol_no AND rlt_car_category_cd=$car_category_cd");
} else {
	$rec['rlt_vol_no'] = sql_number($vol_no);
	$rec['rlt_car_category_cd'] = sql_number($car_category_cd);
	$rec['rlt_theme'] = sql_char($theme);
	$rec['rlt_contents'] = sql_char($body);
	db_insert($table, $rec);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('マスタメンテナンス｜継続リレーションメール｜投げかけテーマ部') ?>

<div align="center">
<p class="msg">継続リレーションメールの投げかけテーマ部を登録しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='theme_list.php?vol_no=<?=$vol_no?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
