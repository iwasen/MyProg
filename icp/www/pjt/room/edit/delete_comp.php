<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:ȯ������¹�
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

// ���ϥѥ�᡼��
$crypt_id = $_POST['id'];
$remark_id = decrypt_id($crypt_id);

if ($remark_id) {
	// ��ȯ������
	$sql = "SELECT rm_parent_remark_id FROM l_remark WHERE rm_remark_id=$remark_id";
	$parent_remark_id = db_fetch1($sql);

	// ȯ������
	$rec['rm_status'] = sql_number(9);
	$rec['rm_parent_remark_id'] = sql_null();
	db_update('l_remark', $rec, "rm_remark_id=$remark_id");

	// ����åɤλҤ�Ƥ���³
	$rec['rm_parent_remark_id'] = sql_number($parent_remark_id);
	db_update('l_remark', $rec, "rm_parent_remark_id=$remark_id");

	// ��ȯ��̵ͭ�ե饰����
	set_child_flag($parent_remark_id);
}

redirect("../contents_top.php?reload_subject=1");
?>