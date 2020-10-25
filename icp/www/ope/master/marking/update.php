<?
/******************************************************
' System :ICPオペレータページ
' Content:マーキングマスタ更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

// 新規登録
function regist_marking() {
	$rec['mk_marking_id'] = sql_number($_POST['marking_id']);
	$rec['mk_name'] = sql_char($_POST['name']);
	$rec['mk_color'] = sql_char($_POST['color']);
	db_insert('m_marking', $rec);
}

// 更新
function update_marking() {
	$rec['mk_name'] = sql_char($_POST['name']);
	$rec['mk_color'] = sql_char($_POST['color']);
	db_update('m_marking', $rec, "mk_marking_id='{$_POST['marking_id']}'");
}

// 削除
function delete_marking() {
	db_delete('m_marking', "mk_marking_id='{$_POST['marking_id']}'");
}

// 処理振り分け
switch ($_POST['proc']) {
case 'regist':
	if (!is_numeric($_POST['marking_id']))
		$msg[] = 'IDは半角数字で入力してください。';
	else {
		$sql = "SELECT COUNT(*) FROM m_marking WHERE mk_marking_id=" . sql_number($_POST['marking_id']);
		if (db_fetch1($sql) != 0)
			$msg[] = '入力したIDは既に登録されています。';
		else
			regist_marking();
	}
	$action = '新規登録';
	break;
case 'update':
	update_marking();
	$action = '更新';
	break;
case 'delete':
	delete_marking();
	$action = '削除';
	break;
}
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
<?
if (!$msg) {
?>
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>■マーキングマスタ<?=$action?>完了</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>マーキングマスタを<?=$action?>しました。</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="../menu.php">マスタメンテナンスメニューに戻る</a>　｜　<a href="list.php">マーキングマスタ一覧に戻る</a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
<?
} else
	disp_err("マーキングマスタ{$action}エラー", $msg);
?>
	</td>
	<!-- 右コンテンツ -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>
</html>
