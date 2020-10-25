<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:アップロード担当マスタ更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// レコード存在チェック
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_upload_tantou WHERE upt_upload_cd=%s",
			sql_char($_POST['upload_cd']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// レコード挿入処理
function rec_insert() {
	db_begin_trans();

	$rec['upt_upload_cd'] = sql_char($_POST['upload_cd']);
	$rec['upt_name'] = sql_char($_POST['name']);
	$rec['upt_active'] = sql_bool($_POST['active']);
	$rec['upt_bgcolor'] = sql_char($_POST['bgcolor']);
	db_insert('t_upload_tantou', $rec);

	tantou_dealer();

	db_commit_trans();
}

// レコード更新処理
function rec_update() {
	db_begin_trans();

	$rec['upt_name'] = sql_char($_POST['name']);
	$rec['upt_active'] = sql_bool($_POST['active']);
	$rec['upt_bgcolor'] = sql_char($_POST['bgcolor']);
	db_update('t_upload_tantou', $rec, "upt_upload_cd='{$_POST['upload_cd']}'");

	tantou_dealer();

	db_commit_trans();
}

// レコード削除処理
function rec_delete() {
	db_begin_trans();

	db_delete('t_upload_tantou', "upt_upload_cd='{$_POST['upload_cd']}'");
	db_delete('t_upload_dealer', "upd_upload_cd='{$_POST['upload_cd']}'");

	db_commit_trans();
}

// 担当販売店
function tantou_dealer() {
	$dealer_ary = $_POST['dealer_cd'];

	if (is_array($dealer_ary)) {
		$sql = "SELECT upd_dealer_cd FROM t_upload_dealer WHERE upd_upload_cd='{$_POST['upload_cd']}'";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$dealer_cd = pg_fetch_result($result, $i, 0);
			$key = array_search($dealer_cd, $dealer_ary);
			if ($key === false)
				db_delete('t_upload_dealer', "upd_upload_cd='{$_POST['upload_cd']}' AND upd_dealer_cd='$dealer_cd'");
			else
				unset($dealer_ary[$key]);
		}

		foreach ($dealer_ary as $dealer_cd) {
			$rec['upd_dealer_cd'] = sql_char($dealer_cd);
			$rec['upd_upload_cd'] = sql_char($_POST['upload_cd']);
			db_insert('t_upload_dealer', $rec);
		}
	} else
		db_delete('t_upload_dealer', "upd_upload_cd='{$_POST['upload_cd']}'");
}

// メイン処理
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '入力したアップロード担当コードは既に登録されています。';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = 'アップロード担当を登録しました。';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	rec_update();
	$msg = 'アップロード担当情報を更新しました。';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	rec_delete();
	$msg = 'アップロード担当を削除しました。';
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

<? center_header('マスタメンテナンス｜アップロード担当マスタ｜更新') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
