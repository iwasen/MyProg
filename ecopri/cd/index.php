<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:宣伝用CDからのクリックカウンタ
'******************************************************/

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

// クリックログに記録
$sql = "INSERT INTO l_cd_click (cd_ip_addr) VALUES ('$REMOTE_ADDR')";
db_exec($sql);

header("location: ../member/index.php");
exit;
?>