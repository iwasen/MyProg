<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");

$limit = disp_limit(20);
?>

<HTML>
<HEAD>
	<TITLE>Ｍｙモニターやり取り履歴</TITLE>
	<META content="text/html; charset=EUC-JP" http-equiv=Content-Type><LINK href="images/kikasete.css" rel=stylesheet>
	<META content="MSHTML 5.00.2614.3500" name=GENERATOR>
<script type="text/javascript">
<!--
function set_display(id, visible) {
	if (visible) {
		if (document.all)
			document.all(id).style.display = "";
		else if (document.getElementById)
			document.getElementById(id).style.display = "";
	} else {
		if (document.all)
			document.all(id).style.display = "none";
		else if (document.getElementById)
			document.getElementById(id).style.display = "none";
	}
}
function onclick_sort(n) {
	document.form1.sort_col.value = n;
	document.form1.submit();
}
//-->
</script>
</HEAD>
<BODY bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<form name="form1">
<input type="hidden" name="room_id" value="<?=$room_id?>">
<input type="hidden" name="monitor_id" value="<?=$monitor_id?>">
<input type="hidden" name="sort_col">
	<TABLE border=0 cellPadding=0 cellSpacing=0 width=400>
	<TBODY>
	<tr>
		<td width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768" >
			<table width="768" border=0 cellspacing="0" cellpadding="0">
				<tr>
					<td width="200"><b>やり取り履歴覧</b></td>
					<td width="500" align="right">
						表示件数
						<select name="displine" onchange="submit()"><? select_displine($displine) ?></select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768">
			<table width="768" border="1" cellspacing="0" cellpadding="3" bordercolor="#ffffff" bordercolorlight="#ffffff" bordercolordark="#ffffff">
				<tr>
					<td width="50" valign="top" align="left" bgcolor="#eeeeee">　
						
					</td>
					<td width="50" valign="top" align="left" bgcolor="#eeeeee">
						<a href="javascript:onclick_sort(1)">No.</a>
					</td>
					<td width="500" valign="top" align="left" bgcolor="#eeeeee">
						<a href="javascript:onclick_sort(2)">件名</a>
					</td>
					<td width="100" valign="top" align="left" bgcolor="#eeeeee">
						<a href="javascript:onclick_sort(3)">日付</a>
					</td>
				</tr>
<?
$order_by = order_by(1, 0, 'rm_send_no', 'rm_title', 'rm_date');
$sql = "SELECT rm_msg_id,rm_send_no,rm_title,rm_contents,rm_date"
		. " FROM t_my_monitor_msg"
		. " JOIN t_my_monitor_send ON rs_msg_id=rm_msg_id"
		. " WHERE rm_room_id=$room_id AND rs_monitor_id=$monitor_id"
		. " $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$msg_id = $fetch->rm_msg_id;
?>
				<tr>
					<td width="50" valign="top" align="left" bgcolor="#ffeecc">
						送信
					</td>
					<td width="50" valign="top" align="left" bgcolor="#ffeecc">
						<?=$fetch->rm_send_no?>
					</td>
					<td width="500" valign="top" align="left" bgcolor="#ffeecc">
						<input name="send" type="checkbox" onclick="set_display('snd<?=$i?>', checked)"><?=htmlspecialchars($fetch->rm_title)?>
						<table border="1" cellspacing="1" cellpadding="0" width="100%" id="snd<?=$i?>" style="display:none">
							<tr>
								<td><?=nl2br(htmlspecialchars($fetch->rm_contents))?></td>
							</tr>
						</table>
					</td>
					<td width="100" valign="top" align="left" bgcolor="#ffeecc">
						<?=format_datetime($fetch->rm_date)?>
					</td>
				</tr>
<?
	$sql = "SELECT rr_title,rr_contents,rr_date"
			. " FROM t_my_monitor_recv"
			. " WHERE rr_msg_id=$msg_id AND rr_monitor_id=$monitor_id"
			. " ORDER BY rr_seq_no";
	$result2 = db_exec($sql);
	$nrow2 = pg_numrows($result2);
	for ($j = 0; $j < $nrow2; $j++) {
		$fetch2 = pg_fetch_object($result2, $j);

?>
				<tr>
					<td width="50" valign="top" align="left" bgcolor="#ffffff">
						┗返信
					</td>
					<td width="50" valign="top" align="left" bgcolor="#ffffff">
						<?=$fetch->rm_send_no?>
					</td>
					<td width="500" valign="top" align="left" bgcolor="#ffffff">
						<input name="res" type="checkbox" onclick="set_display('res<?=$i?>', checked)"><?=htmlspecialchars($fetch2->rr_title)?>
						<table border="1" cellspacing="1" cellpadding="0" width="100%" id="res<?=$i?>" style="display:none">
							<tr>
								<td><tt><?=nl2br(htmlspecialchars($fetch2->rr_contents))?></tt></td>
							</tr>
						</table>
					</td>
					<td width="100" valign="top" align="left" bgcolor="#ffffff">
						<?=format_datetime($fetch2->rr_date)?>
					</td>
				</tr>
<?
	}
}
?>
			</table>
		</td>
	</tr>
	<tr>
		<td width="20" height="30"><img src="images/common/spacer.gif" width="20" height="30" border="0"></td>
		<td width="768" height="30"><img src="images/common/spacer.gif" width="768" height="30" border="0"></td>
	</tr>
	<tr>
		<td colspan="2" width="788" align="center"><a href="javascript:window.close()">閉じる</a></td>
	</tr>
	</TABLE>
</form>
</BODY>
</HTML>
