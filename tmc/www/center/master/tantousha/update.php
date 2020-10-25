<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:担当者マスタ更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// レコード存在チェック
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_tantousha WHERE tts_tantousha_id<>%s AND tts_tantousha_name=%s",
			sql_number($_POST['tantousha_id'], 0),
			sql_char($_POST['tantousha_name']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// レコード挿入処理
function rec_insert() {
	$rec['tts_tantousha_name'] = sql_char($_POST['tantousha_name']);
	$rec['tts_from_name'] = sql_char($_POST['from_name']);
	$rec['tts_from_addr'] = sql_char($_POST['from_addr']);
	$rec['tts_mail_account'] = sql_char($_POST['mail_account']);
	db_insert('t_tantousha', $rec);
}

// レコード更新処理
function rec_update() {
	$rec['tts_tantousha_name'] = sql_char($_POST['tantousha_name']);
	$rec['tts_from_name'] = sql_char($_POST['from_name']);
	$rec['tts_from_addr'] = sql_char($_POST['from_addr']);
	$rec['tts_mail_account'] = sql_char($_POST['mail_account']);
	db_update('t_tantousha', $rec, "tts_tantousha_id={$_POST['tantousha_id']}");
}

// レコード削除処理
function rec_delete() {
	db_delete('t_tantousha', "tts_tantousha_id={$_POST['tantousha_id']}");
}

// メイン処理
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '入力した担当者名は既に登録されています。';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = '担当者を登録しました。';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	if (rec_check()) {
		$msg = '入力した担当者名は既に登録されています。';
		$ret = 'history.back()';
	} else {
		rec_update();
		$msg = '担当者情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'delete':
	rec_delete();
	$msg = '担当者を削除しました。';
	$ret = 'location.href=\'list.php\'';
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('マスタメンテナンス｜担当者マスタ｜更新') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
