<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:事務局スタッフ情報更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// レコード存在チェック
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_staff WHERE stf_staff_id<>%s AND stf_mail_addr=%s",
			sql_number($_POST['staff_id'], 0),
			sql_char($_POST['mail_addr']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// レコード挿入処理
function rec_insert() {
	$rec['stf_status'] = 0;
	$rec['stf_password'] = sql_char(md5($_POST['password']));
	$rec['stf_name'] = sql_char($_POST['name']);
	$rec['stf_mail_addr'] = sql_char($_POST['mail_addr']);
	$rec['stf_privilege'] = sql_char($_POST['privilege']);
	$rec['stf_signature'] = sql_char($_POST['signature']);
	db_insert('t_staff', $rec);
}

// レコード更新処理
function rec_update() {
	$rec['stf_status'] = 0;
	if ($_POST['password_change'])
		$rec['stf_password'] = sql_char(md5($_POST['password']));
	$rec['stf_name'] = sql_char($_POST['name']);
	$rec['stf_mail_addr'] = sql_char($_POST['mail_addr']);
	$rec['stf_privilege'] = sql_char($_POST['privilege']);
	$rec['stf_signature'] = sql_char($_POST['signature']);
	db_update('t_staff', $rec, "stf_staff_id={$_POST['staff_id']}");
}

// レコード削除処理
function rec_delete() {
	db_delete('t_staff', "stf_staff_id={$_POST['staff_id']}");
}

// メイン処理
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = 'スタッフを登録しました。';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	if (rec_check()) {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	} else {
		rec_update();
		$msg = 'スタッフ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'delete':
	rec_delete();
	$msg = 'スタッフを削除しました。';
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

<? center_header('マスタメンテナンス｜事務局スタッフ（オペレータ）マスタ｜更新') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
