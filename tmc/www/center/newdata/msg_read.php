<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��å��������ɽ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");

// ���ɥ��å�
$rec['msr_message_id'] = sql_number($_GET['message_id']);
$rec['msr_staff_id'] = sql_number($g_staff_id);
db_insert('t_message_read', $rec);

redirect('top.php');
?>