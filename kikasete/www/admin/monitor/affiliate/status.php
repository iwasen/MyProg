<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アフィリエイト状態変更処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// メイン処理
set_global('monitor', 'モニター管理', 'アフィリエイト管理', BACK_TOP);

$rec['af_status'] = sql_number($status);
db_update('t_affiliate', $rec, "af_affiliate_id=$affiliate_id");

// 戻り先
$ret_url = isset($parent_affiliate_id) ? "reg_list.php?affiliate_id=$parent_affiliate_id" : 'mem_list.php';
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
<p class="msg">アフィリエイトを<?=$status == 1 ? '有効' : '無効'?>に設定しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='<?=$ret_url?>'"></p>
</div>

<? page_footer() ?>
</body>
</html>
