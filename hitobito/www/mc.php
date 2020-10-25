<?php
/**
 * ひとびと･net ユーザページ
 *
 * メルマガクリックカウンター処理
 *
 *
 * @package
 * @author
 * @version
 */

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// メイン処理
$ctid = $_SERVER['QUERY_STRING'];

if ($ctid == '' || !is_numeric($ctid))
	param_error();

$sql = "SELECT mmi_jump_url FROM t_melmaga_click WHERE mmi_ct_id=$ctid";
$jump_url = db_fetch1($sql);
if ($jump_url == '')
	param_error();

$sql = "UPDATE t_melmaga_click SET mmi_click_count=mmi_click_count+1 WHERE mmi_ct_id=$ctid";
db_exec($sql);

redirect($jump_url);

// パラメータエラー
function param_error() {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>