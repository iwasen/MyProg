<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:マスタメンテナンス｜スタッフ関連マスタ｜所属種別マスタ
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master_st', 'マスタ管理｜似顔絵関連マスタ', '商品マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT pd_product_cd FROM m_product WHERE pd_product_cd='$pd_product_cd'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "INSERT INTO m_product (pd_product_cd, pd_name, pd_explain)"
			. " VALUES ('$pd_product_cd', '$pd_name', '$pd_explain')";
		db_exec($sql);
		$msg = '商品情報を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した商品コードは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT pd_product_cd FROM m_product WHERE pd_product_cd<>'$pd_cd' AND pd_name='$pd_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_product SET" .
					" pd_name=" . sql_char($pd_name) .
					", pd_explain=" . sql_char($pd_explain) .
					" WHERE pd_product_cd='$pd_cd'";
		db_exec($sql);
		$msg = '商品マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '商品コードは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "SELECT nd_nigaoe_id FROM t_nigaoe_data WHERE nd_product_cd='$pd_cd'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "DELETE FROM m_product WHERE pd_product_cd='$pd_cd'";
		db_exec($sql);
		$msg = '商品情報を削除しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '商品コードは既に使用されている為、削除できません。';
		$ret = 'history.back()';
	}
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
