<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Contentシステム情報更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// レコード存在チェック
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_system WHERE sys_key=%s",
			sql_char($_POST['key']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// レコード挿入処理
function rec_insert() {
	$rec['sys_key'] = sql_char($_POST['key']);
	$rec['sys_value'] = sql_char($_POST['value']);
	$rec['sys_caption'] = sql_char($_POST['caption']);
	db_insert('t_system', $rec);
}

// レコード更新処理
function rec_update() {
	$rec['sys_value'] = sql_char($_POST['value']);
	$rec['sys_caption'] = sql_char($_POST['caption']);
	db_update('t_system', $rec, 'sys_key=' . sql_char($_POST['key']));
}

// メイン処理
$ok = true;
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '入力された項目キーは既に登録されています。';
		$ok = false;
	} else {
		rec_insert();
		$msg = 'システム設定情報を登録しました。';
	}
	break;
case 'update':
	rec_update();
	$msg = 'システム設定情報を更新しました。';
	break;
}

// 戻り先
if ($ok)
	$ret = "location.href='list.php'";
else
	$ret = "history.back()";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('マスタメンテナンス｜システム設定情報｜更新') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
