<?
/******************************************************
' System :Eco-footprint ����y�[�W
' Content:��`�pCD����̃N���b�N�J�E���^
'******************************************************/

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

// �N���b�N���O�ɋL�^
$sql = "INSERT INTO l_cd_click (cd_ip_addr) VALUES ('$REMOTE_ADDR')";
db_exec($sql);

header("location: ../member/index.php");
exit;
?>