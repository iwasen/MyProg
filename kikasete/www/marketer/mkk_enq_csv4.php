<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/enquete.php");
include("$inc/mye_csv.php");
if ($owner_id == '') {
	$inc = "$top/inc";
	include("$inc/mkk_mypage.php");

	$owner_id = $_SESSION['ss_owner_id'];
}

// ���󥱡���ID����
if ($enquete_id == '')
	redirect('mkk_enq.php');

// CSV�ե�����̾
$filename = "questions_${enquete_id}.csv";
prepare_csv($filename);

// CSV�ǡ�������
output_attribute_csv();
output_question_csv($enquete_id);
exit;
?>