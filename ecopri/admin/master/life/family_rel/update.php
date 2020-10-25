<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:家族続柄マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '家族続柄マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT fr_name, fr_order FROM m_family_rel WHERE fr_name='$fr_name' OR fr_order=$fr_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(fr_family_rel_cd), 0) + 1) FROM m_family_rel";
		$sql = "INSERT INTO m_family_rel (fr_family_rel_cd, fr_name, fr_attb, fr_sex, fr_order) VALUES (($sub), '$fr_name', $fr_attb, $fr_sex, $fr_order)";
		db_exec($sql);
		$msg = '家族続柄名を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序または家族続柄名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT fr_name FROM m_family_rel WHERE fr_name='$fr_name' AND fr_family_rel_cd<>$fr_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_family_rel SET" .
					" fr_name=" . sql_char($fr_name) .
					", fr_attb=" . sql_number($fr_attb) .
					", fr_sex=" . sql_number($fr_sex) .
					", fr_order=" . sql_number($fr_order) .
					" WHERE fr_family_rel_cd=$fr_cd";
		db_exec($sql);
		$msg = '家族続柄マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '家族続柄名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_family_rel WHERE fr_family_rel_cd=$fr_cd";
	db_exec($sql);
	$msg = '家族続柄名を削除しました。';
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
