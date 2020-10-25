<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// セッション処理
if ($my_enq_no) {
	$staff_id = $_SESSION['ss_staff_id'];
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	$myenq->read_db($staff_id, $my_enq_no);

	$myenq->my_enq_no = '';
	$myenq->enquete_id = '';
	$myenq->search_id = '';
	$myenq->save_enquete = true;
	$myenq->save_search = true;
	$myenq->make_step = 0;

	$enquete = &$myenq->enquete;
	$enquete->status = 0;

	$myenq->write_db();
} else
	redirect('myenq.php');

redirect("myenq_make.php?my_enq_no=$myenq->my_enq_no");
?>