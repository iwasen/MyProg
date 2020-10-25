<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:初期フォローメール挨拶文更新処理
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
$sales_channel_cd = $_POST['sales_channel_cd'];
$body = trim_crlf($_POST['body']);

$table = 't_follow_greeting';

// クリックカウンタ登録
regist_click_counter($vol_no, $body);

// 挨拶文を新規登録または更新
$sql = "SELECT COUNT(*) FROM $table WHERE flg_vol_no=$vol_no AND flg_sales_channel_cd=$sales_channel_cd";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0)) {
	$rec['flg_contents'] = sql_char($body);
	db_update($table, $rec, "flg_vol_no=$vol_no AND flg_sales_channel_cd=$sales_channel_cd");
} else {
	$rec['flg_vol_no'] = sql_number($vol_no);
	$rec['flg_sales_channel_cd'] = sql_number($sales_channel_cd);
	$rec['flg_contents'] = sql_char($body);
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

<? center_header('マスタメンテナンス｜初期フォローメール｜挨拶文') ?>

<div align="center">
<p class="msg">初期フォローメールの挨拶文を登録しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='greeting_list.php?vol_no=<?=$vol_no?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
