<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:都道府県マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜都道府県マスタ', '都道府県マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT ar_area_cd FROM m_area WHERE ar_area_name='$ar_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(ar_area_cd), 0) + 1) FROM m_area";
		$sql = "INSERT INTO m_area (ar_area_cd, ar_area_name,ar_area2_cd) VALUES (($sub), '$ar_name',$aa_cd)";
		db_exec($sql);
		$msg = '都道府県名を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した都道府県名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT ar_area_cd FROM m_area WHERE ar_area_name='$ar_name' AND ar_area_cd<>$ar_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_area SET" .
					" ar_area_name=" . sql_char($ar_name) .
					" ,ar_area2_cd=" . sql_number($aa_cd) .
					" WHERE ar_area_cd=$ar_cd";
		db_exec($sql);
		$msg = '都道府県マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '都道府県名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_area WHERE ar_area_cd=$ar_cd";
	db_exec($sql);
	$msg = '都道府県名を削除しました。';
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
