<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if ($my_enq_no) {
	$owner_id = $_SESSION['ss_owner_id'];
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	$myenq->read_db($owner_id, $my_enq_no);

	$myenq->my_enq_no = '';
	$myenq->enquete_id = '';
	$myenq->search_id = '';
	$myenq->save_enquete = true;
	$myenq->save_search = true;
	$myenq->make_step = 0;
	$myenq->mail_title = '';
	$myenq->mail_header = '';
	$myenq->mail_contents = '';
	$myenq->mail_footer = '';

	$enquete = &$myenq->enquete;
	$enquete->status = 0;
	$enquete->description = '';

	$enquete->duplicate_image();

	$myenq->write_db();
} else
	redirect('mkk_myenq.php');

redirect("mkk_myenq_make.php?my_enq_no=$myenq->my_enq_no");
?>