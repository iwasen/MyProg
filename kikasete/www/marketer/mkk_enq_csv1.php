<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv.php");
if ($owner_id == '') {
	$inc = "$top/inc";
	include("$inc/mkk_mypage.php");

	$owner_id = $_SESSION['ss_owner_id'];
}

// ���󥱡���ID����
if ($enquete_id == '')
	redirect('mkk_enq.php');

// CSV�ǡ�������
switch ($type) {
case 'send':
	csv_send($enquete_id, false);
	break;
case 'recv':
	csv_recv($enquete_id, false);
	break;
}
exit;
?>