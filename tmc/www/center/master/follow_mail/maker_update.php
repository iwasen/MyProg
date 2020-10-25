<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:初期フォローメールメーカーこだわり情報更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("click_counter.php");

// 入力パラメータ
$vol_no = $_POST['vol_no'];
$car_model_cd = $_POST['car_model_cd'];
$body = trim_crlf($_POST['body']);

$table = 't_follow_maker';

// クリックカウンタ登録
regist_click_counter($vol_no, $body);

// メーカーこだわり情報を新規登録または更新
$sql = "SELECT COUNT(*) FROM $table WHERE flm_vol_no=$vol_no AND flm_car_model_cd=$car_model_cd";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0)) {
	$rec['flm_contents'] = sql_char($body);
	db_update($table, $rec, "flm_vol_no=$vol_no AND flm_car_model_cd=$car_model_cd");
} else {
	$rec['flm_vol_no'] = sql_number($vol_no);
	$rec['flm_car_model_cd'] = sql_number($car_model_cd);
	$rec['flm_contents'] = sql_char($body);
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

<? center_header('マスタメンテナンス｜初期フォローメール｜メーカーこだわり情報') ?>

<div align="center">
<p class="msg">初期フォローメールのメーカーこだわり情報を登録しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='maker_list.php?vol_no=<?=$vol_no?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
