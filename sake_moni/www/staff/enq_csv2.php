<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv.php");
if ($staff_id == '') {
	$inc = "$top/inc";
	include("$inc/mypage.php");

	$staff_id = $_SESSION['ss_staff_id'];
}

// ���󥱡���ID����
if ($enquete_id == '')
	redirect('enq.php');

// CSV�ǡ�������
csv_answer($enquete_id);
exit;
?>