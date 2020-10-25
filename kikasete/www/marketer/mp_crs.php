<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$enquete = new enquete_class;
$enquete->read_db($enquete_id);
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->

			<div align="center">
			<form method="post" name="form1" action="mp_crs_result.php">
			<table border=0 cellspacing=0 cellpadding=1 width="95%">
				<tr>
					<td>■設問一覧</td>
				</tr>
			</table>

			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="95%">
				<tr bgcolor="#eff7e8">
					<td align="center"><strong>設問番号</strong></td>
					<td align="center"><strong>設問</strong></td>
					<td align="center"><strong>集計設問</strong></td>
				</tr>
<?
$i = 0;
foreach ($enquete->question as $qno => $question) {
	if ($question->question_type == 1 || $question->question_type == 2) {
		$bgcolor = $i++ % 2 ? '#ffffff' : '#ffeecc';
?>
				<tr bgcolor="<?=$bgcolor?>">
					<td align="center" rowspan="2">Q<?=$qno?></td>
					<td><?=nl2br(htmlspecialchars($question->question_text))?></td>
					<td align="center" rowspan="2"><input type="radio" name="hyousoku" value="<?=$qno?>">表側<br>
					<input type="radio" name="hyoutou" value="<?=$qno?>">表</td>
				</tr>
				<tr bgcolor="<?=$bgcolor?>">
					<td>
<?
		foreach ($question->sel_text as $sel_text) {
?>
					<?=htmlspecialchars($sel_text)?><br>
<?
		}
?>
					</td>
				</tr>
<?
	}
}
?>
			</table>
			<br>
			<div align="center">
			<input type="submit" value="　検索　">
			<input type="button" value="　戻る　" onclick="history.back()">
			<input type="hidden" name="enquete_id" <?=value($enquete_id)?>>
			</div>
			</form>
			</div>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
