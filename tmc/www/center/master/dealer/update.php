<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店マスタ更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// レコード存在チェック
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_dealer WHERE dlr_dealer_cd=%s",
			sql_char($_POST['dealer_cd']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// レコード挿入処理
function rec_insert() {
	db_begin_trans();

	$rec['dlr_dealer_cd'] = sql_char($_POST['dealer_cd']);
	$rec['dlr_dealer_name'] = sql_char($_POST['dealer_name']);
	$rec['dlr_short_name'] = sql_char($_POST['short_name']);
	$rec['dlr_sales_channel_cd'] = sql_number($_POST['sales_channel_cd']);
	$rec['dlr_tantousha_id'] = sql_number($_POST['tantousha_id']);
	db_insert('t_dealer', $rec);

	dealer_car();

	db_commit_trans();
}

// レコード更新処理
function rec_update() {
	db_begin_trans();

	$rec['dlr_dealer_name'] = sql_char($_POST['dealer_name']);
	$rec['dlr_short_name'] = sql_char($_POST['short_name']);
	$rec['dlr_sales_channel_cd'] = sql_number($_POST['sales_channel_cd']);
	$rec['dlr_tantousha_id'] = sql_number($_POST['tantousha_id']);
	db_update('t_dealer', $rec, "dlr_dealer_cd='{$_POST['dealer_cd']}'");

	dealer_car();

	db_commit_trans();
}

// レコード削除処理
function rec_delete() {
	db_begin_trans();

	db_delete('t_dealer', "dlr_dealer_cd='{$_POST['dealer_cd']}'");
	db_delete('t_dealer_car', "dlc_dealer_cd='{$_POST['dealer_cd']}'");

	db_commit_trans();
}

// 取り扱い車種登録
function dealer_car() {
	$dealer_car = $_POST['dealer_car'];

	if (is_array($dealer_car)) {
		$sql = "SELECT dlc_car_model_cd FROM t_dealer_car WHERE dlc_dealer_cd='{$_POST['dealer_cd']}'";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$car_model_cd = pg_fetch_result($result, $i, 0);
			$key = array_search($car_model_cd, $dealer_car);
			if ($key === false)
				db_delete('t_dealer_car', "dlc_dealer_cd='{$_POST['dealer_cd']}' AND dlc_car_model_cd=$car_model_cd");
			else
				unset($dealer_car[$key]);
		}

		foreach ($dealer_car as $car_model_cd) {
			$rec['dlc_dealer_cd'] = sql_char($_POST['dealer_cd']);
			$rec['dlc_car_model_cd'] = sql_number($car_model_cd);
			db_insert('t_dealer_car', $rec);
		}
	} else
		db_delete('t_dealer_car', "dlc_dealer_cd='{$_POST['dealer_cd']}'");
}

// メイン処理
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '入力した販売店コードは既に登録されています。';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = '販売店を登録しました。';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	rec_update();
	$msg = '販売店情報を更新しました。';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	rec_delete();
	$msg = '販売店を削除しました。';
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

<? center_header('マスタメンテナンス｜販売店マスタ｜更新') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
