<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:プロファイル更新
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// 選択肢登録
function add_sel($profile_id) {
	foreach ($_POST['profile_cd'] as $key => $val) {
		if ($val) {
			if ($_POST['sel_text'][$key] != '') {
				$rec['pfs_select_text'] = sql_char($_POST['sel_text'][$key]);
				$rec['pfs_order'] = sql_number($_POST['sel_order'][$key]);
				db_update('t_profile_sel', $rec, "pfs_profile_cd=$val");
			} else {
				db_delete('t_profile_sel', "pfs_profile_cd=$val");
			}
		} else {
			if ($_POST['sel_text'][$key] != '') {
				$rec['pfs_profile_id'] = sql_number($profile_id);
				$rec['pfs_select_text'] = sql_char($_POST['sel_text'][$key]);
				$rec['pfs_order'] = sql_number($_POST['sel_order'][$key]);
				db_insert('t_profile_sel', $rec);
			}
		}
	}
}

// メイン処理
set_global('monitor', 'モニター管理', 'プロファイル手動追加', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT COALESCE(MAX(pfi_profile_id),0)+1 FROM t_profile_item";
	$profile_id = db_fetch1($sql);

	db_begin_trans();
	$rec['pfi_profile_id'] = "(SELECT COALESCE(MAX(pfi_profile_id),0)+1 FROM t_profile_item)";
	$rec['pfi_profile_name'] = sql_char($_POST['profile_name']);
	$rec['pfi_order'] = sql_number($_POST['order']);
	$rec['pfi_hidden_flag'] = sql_bool($_POST['hidden_flag']);
	db_insert('t_profile_item', $rec);
	add_sel($profile_id);
	db_commit_trans();

	$msg = '追加プロファイルを登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	db_begin_trans();
	$rec['pfi_profile_name'] = sql_char($_POST['profile_name']);
	$rec['pfi_order'] = sql_number($_POST['order']);
	$rec['pfi_hidden_flag'] = sql_bool($_POST['hidden_flag']);
	db_update('t_profile_item', $rec, "pfi_profile_id={$_POST['profile_id']}");
	add_sel($_POST['profile_id']);
	db_commit_trans();

	$msg = '追加プロファイルを更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	db_begin_trans();
	db_delete('t_profile_item', "pfi_profile_id={$_POST['profile_id']}");
	db_delete('t_profile_sel', "pfs_profile_id={$_POST['profile_id']}");
	db_commit_trans();

	$msg = '追加プロファイルを削除しました。';
	$back = "location.href='list.php'";
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
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
