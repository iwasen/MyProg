<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:住居形態マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '住居形態マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT kt_keitai_cd, kt_order FROM m_keitai WHERE kt_keitai_name='$name' or kt_order=$kt_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(kt_keitai_cd), 0) + 1) FROM m_keitai WHERE kt_keitai_cd<>999";
		$sql = "INSERT INTO m_keitai (kt_keitai_cd, kt_keitai_name, kt_order) VALUES (($sub), '$name', $kt_order)";
		db_exec($sql);
		$msg = '住居形態を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序または住居形態名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT kt_keitai_name FROM m_keitai WHERE kt_keitai_name='$name' AND kt_keitai_cd<>$kt_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_keitai SET" .
					" kt_keitai_name=" . sql_char($name) .
					", kt_order=" . sql_number($kt_order) .
					" WHERE kt_keitai_cd=$kt_cd";
		db_exec($sql);
		$msg = '住居形態マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '住居形態名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_keitai WHERE kt_keitai_cd=$kt_cd";
	db_exec($sql);
	$msg = '住居形態を削除しました。';
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
