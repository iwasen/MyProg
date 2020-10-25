<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:メール購読解除理由マスタ更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// レコード存在チェック
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_mail_cancel_result WHERE mcr_cancel_cd=%s",
			sql_char($_POST['cancel_cd']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// レコード挿入処理
function rec_insert() {
	$rec['mcr_cancel_cd'] = sql_number($_POST['cancel_cd']);
	$rec['mcr_cancel_text'] = sql_char($_POST['cancel_text']);
	db_insert('t_mail_cancel_result', $rec);
}

// レコード更新処理
function rec_update() {
	$rec['mcr_cancel_text'] = sql_char($_POST['cancel_text']);
	db_update('t_mail_cancel_result', $rec, "mcr_cancel_cd={$_POST['cancel_cd']}");
}

// レコード削除処理
function rec_delete() {
	db_delete('t_mail_cancel_result', "mcr_cancel_cd={$_POST['cancel_cd']}");
}

// メイン処理
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '入力したメール購読解除理由コードは既に登録されています。';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = 'メール購読解除理由を登録しました。';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	rec_update();
	$msg = 'メール購読解除理由情報を更新しました。';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	rec_delete();
	$msg = 'メール購読解除理由を削除しました。';
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

<? center_header('マスタメンテナンス｜メール購読解除理由マスタ｜更新') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
