<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:チェーンマスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', 'その他管理｜マスタメンテナンス', 'チェーンマスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT ch_chain_cd FROM m_chain WHERE ch_name='$ch_name' or ch_order=$ch_order";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(ch_chain_cd), 0) + 1) FROM m_chain";
		$sql = "INSERT INTO m_chain (ch_chain_cd, ch_status, ch_name, ch_order, ch_chain_area_cd, ch_gyoutai_cd) VALUES (($sub) , 0, '$ch_name', $ch_order, $chain_area_cd, $gyoutai_cd)";
		db_exec($sql);
		$msg = 'チェーン名を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序またはチェーン名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT ch_chain_cd FROM m_chain WHERE ch_name='$ch_name' AND ch_chain_cd<>$chain_cd";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sql = "UPDATE m_chain SET" .
					" ch_name=" . sql_char($ch_name) .
					", ch_status=" . sql_number($status) .
					", ch_order=" . sql_number($ch_order) .
					", ch_chain_area_cd=" . sql_number($chain_area_cd) .
                                        ", ch_gyoutai_cd=" . sql_number($gyoutai_cd) .
					" WHERE ch_chain_cd=$chain_cd";
		db_exec($sql);
		$msg = 'チェーンマスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = 'チェーン名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_chain WHERE ch_chain_cd=$chain_cd";
	db_exec($sql);
	$msg = 'チェーン名を削除しました。';
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
