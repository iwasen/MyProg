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
switch ($type) {
case 'send':
	csv_send($enquete_id);
	break;
case 'recv':
	csv_recv($enquete_id);
	break;
}
exit;
?>