<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$owner_id = $_SESSION['ss_owner_id'];
$sql = "UPDATE t_communication SET co_status=9 WHERE co_seq_no=$seq_no AND co_marketer_id=$owner_id AND co_status=0 AND co_direction=2";
db_exec($sql);
?>
<? marketer_header('メッセージ送信完了', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top"><img src="images/t_mkk_mdet1.gif" alt="メッセージ詳細" width="400" height="20"><br>
	  <br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="540" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						メッセージを削除しました。
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
