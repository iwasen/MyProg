<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/pro_enquete.php");

header('Content-Type: text/html; charset=EUC-JP');
session_start();

$myenq = &$_SESSION[$ss];
if ($main)
	$enquete = &$myenq->enquete2;
else
	$enquete = &$myenq->enquete;

$move_to_ary = array();
$qno = 1;
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];
	if (!$question->delete_flag) {
		if ($i == $move_from)
			$from_qno = $qno;
		else
			$move_to_ary[$i] = $qno;
		$qno++;
	}
}
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=EUC-JP' />
<title>質問移動</title>
<script type="text/javascript">
<!--
function move_question() {
	var f = document.form1;
	var f2 = opener.document.form1;
	f2.move_from.value = <?=$move_from?>;
	f2.move_to.value = f.move_to.value;
	f2.next_action.value = "move_question";
	f2.submit();
	window.close();
}
//-->
</script>
</head>
<body bgcolor="#eeeeee" onload="focus()">
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
	<tr>
		<td align="center" valign="center" style="font-size:12px;">
			<form name="form1">
			Q<?=$from_qno?> をどこに移動しますか？<br><br>
			移動先の質問番号を選択してください。<br><br>
			<select name="move_to">
<?
foreach ($move_to_ary as $i => $qno) {
?>
				<option value="<?=$i?>">Q<?=$qno?></option>
<?
}
?>
			</select><br><br>
			<input type="button" value="　移動　" onclick="move_question()">
			<input type="button" value="キャンセル" onclick="window.close()">
			</form>
		</td>
	</tr>
</table>
</body>
</html>