<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アフィリエイトポイント削除処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/point.php");
include("$inc/resign.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// メイン処理
set_global('monitor', 'モニター管理', 'アフィリエイト管理', BACK_TOP);

if (is_array($check)) {
	db_begin_trans();

	foreach ($check as $id) {
		list($affiliate_id2, $monitor_id2) = explode(':', $id);

		// モニターID取得
		$sql = "SELECT af_monitor_id FROM t_affiliate WHERE af_affiliate_id=$affiliate_id2";
		$monitor_id = db_fetch1($sql);

		// 付与したアフィリエイトポイント取得
		$sql = "SELECT afr_point FROM t_affiliate_reg WHERE afr_affiliate_id=$affiliate_id2 AND afr_monitor_id=$monitor_id2";
		$point = db_fetch1($sql);

		if ($point > 0) {
			// アフィリエイト申請者のポイントを削除
			monitor_point($monitor_id, PT_AFFILIATE, -$point);

			// アフィリエイト登録者のポイントを削除
			monitor_point($monitor_id2, PT_AFFILIATE, -$point);

			// 付与ポイント数を0にセット
			$rec['afr_point'] = sql_number(0);
			db_update('t_affiliate_reg', $rec, "afr_affiliate_id=$affiliate_id2 AND afr_monitor_id=$monitor_id2");
		}

		// アフィリエイト無効
		$sql = "UPDATE t_affiliate SET af_status=2 WHERE af_monitor_id=$monitor_id2 AND af_affiliate_mgr_id=$affiliate_mgr_id AND af_status<>2";
		db_exec($sql);

		// アフィリエイト登録者を退会処分
		resign_monitor($monitor_id2);
	}

	// モニター数リセット
	$sql = "DELETE FROM t_sum_data WHERE su_sum_id=1";
	db_exec($sql);

	db_commit_trans();
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
<p class="msg">アフィリエイトポイントを無効にしました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='reg_list.php?affiliate_id=<?=$affiliate_id?>'"></p>
</div>

<? page_footer() ?>
</body>
</html>
