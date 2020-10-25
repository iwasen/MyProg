<?
$inc = "inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/encrypt.php");

// パラメータ存在チェック
if ($id == '')
	error_param();

// パラメータ長チェック
if (strlen($id) != 9)
	error_param();

// PRID取得
$pr_id = decrypt_number($id);
if (!$pr_id)
	error_param();

// クリックログに記録
$sql = "INSERT INTO l_pr_click (pc_pr_id,pc_ip_addr) VALUES ($pr_id,'$REMOTE_ADDR')";
db_exec($sql);

// PRマスタにクリック数加算
$sql = "UPDATE t_promotion SET pr_click_count=pr_click_count+1 WHERE pr_pr_id=$pr_id";
db_exec($sql);

// 飛び先URL取得
$sql = "SELECT pr_jump_url FROM t_promotion WHERE pr_pr_id=$pr_id";
$jump_url = db_fetch1($sql);
if ($jump_url == '')
	$jump_url = 'monitor/index.php';

SetCookie('cookie_pr_id', $pr_id, 0, '/');
redirect($jump_url);

//=== パラメータエラー処理 ===
function error_param() {
	redirect('index.php');
}
?>