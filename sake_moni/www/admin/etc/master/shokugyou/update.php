<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:職業マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', 'その他管理｜マスタメンテナンス', '職業マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT sg_shokugyou_cd, sg_order FROM m_shokugyou WHERE sg_shokugyou_name='$shokugyou_name' or sg_order=$sg_order";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(sg_shokugyou_cd), 0) + 1) FROM m_shokugyou";
		$sql = "INSERT INTO m_shokugyou (sg_shokugyou_cd, sg_shokugyou_name, sg_order) VALUES (($sub), '$shokugyou_name', $sg_order)";
		db_exec($sql);
		$msg = '職業名を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序または職業名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_name='$shokugyou_name' AND sg_shokugyou_cd<>$shokugyou_cd";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sql = "UPDATE m_shokugyou SET" .
					" sg_shokugyou_name=" . sql_char($shokugyou_name) .
					", sg_order=" . sql_number($sg_order) .
					" WHERE sg_shokugyou_cd=$shokugyou_cd";
		db_exec($sql);
		$msg = '職業マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '職業名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_shokugyou WHERE sg_shokugyou_cd=$shokugyou_cd";
	db_exec($sql);
	$msg = '職業名を削除しました。';
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
