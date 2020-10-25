<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT mr_mail_addr,ag_agent_id,ag_mail_addr,ag_bcc_addr"
		. " FROM t_marketer JOIN t_agent ON mr_agent_id=ag_agent_id"
		. " WHERE mr_marketer_id=$owner_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$agent_id = $fetch->ag_agent_id;

	// エージェントにメール送信
	send_mail($title, $fetch->ag_mail_addr, $fetch->mr_mail_addr, $text, 0, $fetch->ag_bcc_addr);

	// DBに保存
	$sql = sprintf("INSERT INTO t_communication (co_direction,co_marketer_id,co_agent_id,co_title,co_text) VALUES (1,%s,%s,%s,%s)",
			sql_number($owner_id),
			sql_number($agent_id),
			sql_char($title),
			sql_char($text));
	db_exec($sql);
}
?>
<? marketer_header('メッセージ送信完了', PG_CONTACT) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top"><img src="images/t_mkk_messcompl.gif" width="400" height="20"><br><br></td>
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
						メッセージを送信しました。<br>
						後ほど専属エージェントからご連絡させていただきますので、返事をお待ち下さい。<br><br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768" align="center"><a href="mkk_index.php"><img src="images/mkk_achange/bt01.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a></td>
	</tr>
</table>

<? marketer_footer() ?>
