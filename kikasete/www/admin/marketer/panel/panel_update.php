<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:スペシャルパネル管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");

// メイン処理
set_global('myenquete', 'Ｍｙアンケート管理', 'スペシャルパネル管理', BACK_TOP);

switch ($_REQUEST['next_action']) {
case 'new':
	$rec['pnl_panel_id'] = sql_number($_REQUEST['panel_id']);
	$rec['pnl_category_id'] = sql_number($_REQUEST['category_id']);
	$rec['pnl_panel_name'] = sql_char($_REQUEST['panel_name']);
	$rec['pnl_display_status'] = sql_number(0);
	db_insert('t_panel', $rec);

	$msg = 'スペシャルパネルを登録しました。';
	break;
case 'update':
	$rec['pnl_panel_name'] = sql_char($_REQUEST['panel_name']);
	$rec['pnl_update_date'] = 'current_timestamp';
	db_update('t_panel', $rec, sprintf("pnl_panel_id=%s", sql_number($_REQUEST['panel_id'])));

	$msg = 'スペシャルパネルを更新しました。';
	break;
case 'delete':
	db_begin_trans();
	db_delete('t_panel', sprintf("pnl_panel_id=%s", sql_number($_REQUEST['panel_id'])));
	db_delete('t_panel_monitor_list', sprintf("pnm_panel_id=%s", sql_number($_REQUEST['panel_id'])));
	db_commit_trans();

	$msg = 'スペシャルパネルを削除しました。';
	break;
case 'order':
	db_begin_trans();
	if (is_array($_REQUEST['order'])) {
		foreach ($_REQUEST['order'] as $panel_id => $order) {
			$rec['pnl_display_order'] = sql_number($order);
			db_update('t_panel', $rec, sprintf("pnl_panel_id=%s", sql_number($panel_id)));
		}
	}
	db_commit_trans();

	$msg = '対象条件表示順を更新しました。';
	break;
case 'status':
	$rec['pnl_display_status'] = "CASE WHEN pnl_display_status=0 THEN 1 ELSE 0 END";
	$rec['pnl_update_date'] = 'current_timestamp';
	db_update('t_panel', $rec, sprintf("pnl_panel_id=%s", sql_number($_REQUEST['panel_id'])));

	$msg = '対象条件表示を変更しました。';
	break;
default:
	redirect('panel_list.php');
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
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='panel_list.php?category_id=<?=$_REQUEST['category_id']?>'"></p>
</div>

<? page_footer() ?>
</body>
</html>
