<?
/******************************************************
' System :ICP会議室ページ
' Content:発言削除実行
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/crypt.php");
include("$inc/remark.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$crypt_id = $_POST['id'];
$remark_id = decrypt_id($crypt_id);

if ($remark_id) {
	// 親発言取得
	$sql = "SELECT rm_parent_remark_id FROM l_remark WHERE rm_remark_id=$remark_id";
	$parent_remark_id = db_fetch1($sql);

	// 発言更新
	$rec['rm_status'] = sql_number(9);
	$rec['rm_parent_remark_id'] = sql_null();
	db_update('l_remark', $rec, "rm_remark_id=$remark_id");

	// スレッドの子を親に接続
	$rec['rm_parent_remark_id'] = sql_number($parent_remark_id);
	db_update('l_remark', $rec, "rm_parent_remark_id=$remark_id");

	// 子発言有無フラグ更新
	set_child_flag($parent_remark_id);
}

redirect("../contents_top.php?reload_subject=1");
?>