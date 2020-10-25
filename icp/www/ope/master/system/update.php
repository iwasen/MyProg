<?
/******************************************************
' System :ICPオペレータページ
' Contentシステム情報更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// レコード存在チェック
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM m_system WHERE sy_key=%s",
			sql_char($_POST['key']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// レコード挿入処理
function rec_insert() {
	$rec['sy_key'] = sql_char($_POST['key']);
	$rec['sy_value'] = sql_char($_POST['value']);
	$rec['sy_caption'] = sql_char($_POST['caption']);
	db_insert('m_system', $rec);
}

// レコード更新処理
function rec_update() {
	$rec['sy_value'] = sql_char($_POST['value']);
	$rec['sy_caption'] = sql_char($_POST['caption']);
	db_update('m_system', $rec, 'sy_key=' . sql_char($_POST['key']));
}

// メイン処理
$ok = true;
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '入力された項目キーは既に登録されています。';
		$ok = false;
	} else {
		rec_insert();
		$msg = 'システム設定情報を登録しました。';
	}
	break;
case 'update':
	rec_update();
	$msg = 'システム設定情報を更新しました。';
	break;
}

// 戻り先
if ($ok)
	$ret = "location.href='list.php'";
else
	$ret = "history.back()";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>icp.net オペレーターページ</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>
<body>

<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- 右コンテンツ -->
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td align="center">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>■システムマスタ更新完了</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>システムマスタを更新しました。</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="../menu.php">マスタメンテナンスメニューに戻る</a>　｜　<a href="list.php">システム設定情報一覧に戻る</a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

	</td>
	<!-- 右コンテンツ -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>
</html>
