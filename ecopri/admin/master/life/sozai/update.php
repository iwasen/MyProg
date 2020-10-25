<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:住居素材マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '住居素材マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT sz_sozai_cd, sz_order FROM m_sozai WHERE sz_sozai_name='$name' or sz_order=$sz_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(sz_sozai_cd), 0) + 1) FROM m_sozai WHERE sz_sozai_cd<>999";
		$sql = "INSERT INTO m_sozai (sz_sozai_cd, sz_sozai_name, sz_order) VALUES (($sub), '$name', $sz_order)";
		db_exec($sql);
		$msg = '住居素材を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序または住居素材名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT sz_sozai_name FROM m_sozai WHERE sz_sozai_name='$name' AND sz_sozai_cd<>$sz_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_sozai SET" .
					" sz_sozai_name=" . sql_char($name) .
					", sz_order=" . sql_number($sz_order) .
					" WHERE sz_sozai_cd=$sz_cd";
		db_exec($sql);
		$msg = '住居素材マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '住居素材名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_sozai WHERE sz_sozai_cd=$sz_cd";
	db_exec($sql);
	$msg = '住居素材を削除しました。';
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
