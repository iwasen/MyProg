<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

if ($delete) {
	$owner_id = $_SESSION['ss_owner_id'];
	$seq_no = join(',', $delete);
	$sql = "UPDATE t_communication SET co_status=9 WHERE co_seq_no IN ($seq_no) AND co_marketer_id=$owner_id";
	if (db_exec($sql) == 0)
		$msg[] = 'メッセージを削除できませんでした。';
} else
	$msg[] = '削除するメッセージを選択してください。';
?>
<? marketer_header('エージェントへのメッセージを削除', PG_CONTACT) ?>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" height="10" border="0"></td>
		
    <td valign="top"><img src="images/contactus/t_mkk_messdelm.gif" width="200" height="30"><br>
      <br></td>
	</tr>
</table>
<?
if (!$msg) {
?>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="8" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>メッセージを削除しました。</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768" align="center"><a href="mkk_index.php"><img src="images/mkk_achange/bt01.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a></td>
	</tr>
</table>
<?
} else{?>
	<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>
<? }?></td>
    <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
  </tr>
</table>

<? marketer_footer() ?>
