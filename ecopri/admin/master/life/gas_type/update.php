<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:ガスタイプマスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', 'ガスタイプマスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT gt_gas_type_cd, gt_order FROM m_gas_type WHERE gt_gas_type_name='$name' or gt_order=$gt_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(gt_gas_type_cd), 0) + 1) FROM m_gas_type WHERE gt_gas_type_cd<>999";
		$sql = "INSERT INTO m_gas_type (gt_gas_type_cd, gt_gas_type_name, gt_order,gt_calorie) VALUES (($sub), '$name', $gt_order, $gt_calorie)";
		db_exec($sql);
		$msg = 'ガスタイプを登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序またはガスタイプ名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT gt_gas_type_name FROM m_gas_type WHERE gt_gas_type_name='$name' AND gt_gas_type_cd<>$gt_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_gas_type SET" .
					" gt_gas_type_name=" . sql_char($name) .
					", gt_order=" . sql_number($gt_order) .
					", gt_calorie=" . sql_number($gt_calorie) .
					" WHERE gt_gas_type_cd=$gt_cd";
		db_exec($sql);
		$msg = 'ガスタイプマスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = 'ガスタイプ名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_gas_type WHERE gt_gas_type_cd=$gt_cd";
	db_exec($sql);
	$msg = 'ガスタイプを削除しました。';
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
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
