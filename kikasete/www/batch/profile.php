<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:モニタープロファイル集計処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/profile.php");
include("$inc/batch_log.php");

define('FILE_NAME', __FILE__);

// 最大実行時間（３分）
set_time_limit(180);

// プロファイル集計処理
monitor_profile();

batch_log(0, FILE_NAME, "モニタープロファイルを作成しました。");
?>