<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:ȯ���Խ��¹�
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/crypt.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// ���ϥѥ�᡼��
$crypt_id = $_POST['id'];
$remark_id = decrypt_id($crypt_id);

// ȯ������
$rec['rm_subject'] = sql_char($_POST['subject']);
$rec['rm_content'] = sql_char($_POST['content']);
db_update('l_remark', $rec, "rm_remark_id=$remark_id");

redirect("../contents_top.php?id=$crypt_id&reload_subject=1");
?>