<?
/******************************************************
' System :ICP会議室ページ
' Content:発言編集実行
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/crypt.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$crypt_id = $_POST['id'];
$remark_id = decrypt_id($crypt_id);

// 発言更新
$rec['rm_subject'] = sql_char($_POST['subject']);
$rec['rm_content'] = sql_char($_POST['content']);
db_update('l_remark', $rec, "rm_remark_id=$remark_id");

redirect("../contents_top.php?id=$crypt_id&reload_subject=1");
?>