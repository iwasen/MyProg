<?
/******************************************************
' System :ICPオペレータページ
' Content:組織登録完了
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

$org_id = $_POST['org_id'];

// 組織名重複チェック
function check_org_name($org_name, $org_id = 0) {
	$sql = "SELECT COUNT(*) FROM m_org WHERE or_org_id<>$org_id AND or_name=" . sql_char($org_name);
	return db_fetch1($sql) == 0;
}

// 組織情報登録
function regist_org() {
	global $g_operator_id;

	switch ($_POST['type']) {
	case '1':
		$parent_org_id = '';
		break;
	case '2':
		$parent_org_id = $_POST['parent_org_id1'];
		break;
	case '3':
		$parent_org_id = $_POST['parent_org_id2'];
		break;
	}
	$rec['or_status'] = sql_number(1);
	$rec['or_parent_org_id'] = sql_number($parent_org_id);
	$rec['or_name'] = sql_char($_POST['or_name']);
	$rec['or_description'] = sql_char($_POST['description']);
	$rec['or_type'] = sql_number($_POST['type']);
	$rec['or_operator_id'] = sql_number($g_operator_id);
	db_insert('m_org', $rec);
	return get_current_seq('m_org', 'or_org_id');
}

// 組織情報更新
function update_org() {
	$org_id = $_POST['org_id'];

	switch ($_POST['type']) {
	case '1':
		$parent_org_id = '';
		break;
	case '2':
		$parent_org_id = $_POST['parent_org_id1'];
		break;
	case '3':
		$parent_org_id = $_POST['parent_org_id2'];
		break;
	}
	$rec['or_status'] = sql_number($_POST['status']);
	$rec['or_parent_org_id'] = sql_number($parent_org_id);
	$rec['or_name'] = sql_char($_POST['or_name']);
	$rec['or_description'] = sql_char($_POST['description']);
	$rec['or_type'] = sql_number($_POST['type']);
	db_update('m_org', $rec, sprintf("or_org_id=%s", sql_number($org_id)));

	return $org_id;
}

// 組織削除
function delete_org() {
	$org_id = $_POST['org_id'];

	db_delete('m_org', sprintf("or_org_id=%s", sql_number($org_id)));

	return $org_id;
}

switch ($_POST['proc']) {
case 'regist':
	if (check_org_name($_POST['or_name']))
		$org_id = regist_org();
	else
		$msg[] = '組織　' . htmlspecialchars($_POST['or_name']) . '　は既に登録されています。';
	$target = '新規組織';
	$action = '登録';
	break;
case 'update':
	if (check_org_name($_POST['or_name'], $_POST['org_id']))
		$org_id = update_org();
	else
		$msg[] = '組織　' . htmlspecialchars($_POST['or_name']) . '　は既に登録されています。';
	$target = '組織';
	$action = '更新';
	break;
case 'delete':
	$org_id = delete_org();
	$target = '組織';
	$action = '削除';
	break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
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
					<td>■<?=$target?><?=$action?>完了</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td><?=$target?>　<?=htmlspecialchars($_POST['or_name'])?>（<?=$org_id?>）　を<?=$action?>しました。</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center">
						<a href="../top.php">トップページに戻る</a>　｜　
						<a href="list.php">組織一覧に戻る</a>
					</td>
				</tr>
				</table>
<?
} else
	disp_err("{$target}{$action}エラー", $msg);
?>
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
