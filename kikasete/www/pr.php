<?
$inc = "inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/encrypt.php");

// �ѥ�᡼��¸�ߥ����å�
if ($id == '')
	error_param();

// �ѥ�᡼��Ĺ�����å�
if (strlen($id) != 9)
	error_param();

// PRID����
$pr_id = decrypt_number($id);
if (!$pr_id)
	error_param();

// ����å����˵�Ͽ
$sql = "INSERT INTO l_pr_click (pc_pr_id,pc_ip_addr) VALUES ($pr_id,'$REMOTE_ADDR')";
db_exec($sql);

// PR�ޥ����˥���å����û�
$sql = "UPDATE t_promotion SET pr_click_count=pr_click_count+1 WHERE pr_pr_id=$pr_id";
db_exec($sql);

// ������URL����
$sql = "SELECT pr_jump_url FROM t_promotion WHERE pr_pr_id=$pr_id";
$jump_url = db_fetch1($sql);
if ($jump_url == '')
	$jump_url = 'monitor/index.php';

SetCookie('cookie_pr_id', $pr_id, 0, '/');
redirect($jump_url);

//=== �ѥ�᡼�����顼���� ===
function error_param() {
	redirect('index.php');
}
?>