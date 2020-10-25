<?
if ($key) {
	header("location: member/help_ans.php?key=$key");
} else {
	header("location: member/top.php");
}
exit;
?>
