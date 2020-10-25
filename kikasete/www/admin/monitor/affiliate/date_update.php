<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アフィリエイト申請期間設定処理
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

$rec['afd_start_date'] = sql_date2($start_y, $start_m, $start_d);
$rec['afd_end_date'] = sql_date2($end_y, $end_m, $end_d);
db_update('t_affiliate_date', $rec, "true");
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
<p class="msg">アフィリエイト申請受付期間を設定しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
