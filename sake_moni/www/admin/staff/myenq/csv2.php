<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�ͣ����󥱡���CSV���Ͻ�����01������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv2.php");

// ���󥱡���ID����
if ($my_enq_no) {
	$sql = "SELECT me_enquete_id FROM t_my_enquete WHERE me_staff_id=$staff_id AND me_my_enq_no=$my_enq_no";
	$enquete_id = db_fetch1($sql);
}

if (!$enquete_id)
	redirect('show.php');

// CSV�ǡ�������
csv_answer2($enquete_id, $clean1, $clean2);
exit;
?>