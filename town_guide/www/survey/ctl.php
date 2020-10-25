<?
$inc = '../inc';
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/error.php");

session_start();

if (isset($_SESSION['s_enquete'])) {
	$enquete = &$_SESSION['s_enquete'];

	// 回答をセッションに保存
	if (isset($_POST['question_no']))
		$enquete->save_answer();

	$question_no = $enquete->get_question_no();
	$max_category = $enquete->get_max_category();
	if ($question_no <= $max_category) {
		include('enq.php');
	} elseif ($question_no == $max_category + 1) {
		include('f1.php');
	} elseif ($question_no == $max_category + 2) {
		include('f2.php');
	} elseif ($question_no == $max_category + 3) {
		include('f3.php');
	} elseif ($question_no == $max_category + 4) {
		include('send.php');
	} elseif ($question_no == $max_category + 5) {
		include('thanks.php');
	}
} else
	redirect("index.php?id={$_COOKIE['c_user_id']}&gkey={$_COOKIE['c_group_id']}");
?>