<?
if ($key) {
	header("location: member/member_input_exp.php?key=$key");
} else {
	header("location: member/top.php");
}
exit;
?>
