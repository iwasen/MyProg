<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:車種マスタ更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// レコード存在チェック
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_car_model WHERE crm_car_model_cd<>%s AND crm_car_model_name=%s",
			sql_number($_POST['car_model_cd'], 0),
			sql_char($_POST['car_model_name']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// レコード挿入処理
function rec_insert() {
	$rec['crm_car_model_name'] = sql_char($_POST['car_model_name']);
	$rec['crm_car_category_cd'] = sql_number($_POST['car_category_cd']);
	db_insert('t_car_model', $rec);
}

// レコード更新処理
function rec_update() {
	$rec['crm_car_model_name'] = sql_char($_POST['car_model_name']);
	$rec['crm_car_category_cd'] = sql_number($_POST['car_category_cd']);
	db_update('t_car_model', $rec, "crm_car_model_cd={$_POST['car_model_cd']}");
}

// レコード削除処理
function rec_delete() {
	db_delete('t_car_model', "crm_car_model_cd={$_POST['car_model_cd']}");
}

// メイン処理
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '入力した車種名は既に登録されています。';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = '車種を登録しました。';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	if (rec_check()) {
		$msg = '入力した車種名は既に登録されています。';
		$ret = 'history.back()';
	} else {
		rec_update();
		$msg = '車種情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'delete':
	rec_delete();
	$msg = '車種を削除しました。';
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

<? center_header('マスタメンテナンス｜車種マスタ｜更新') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
