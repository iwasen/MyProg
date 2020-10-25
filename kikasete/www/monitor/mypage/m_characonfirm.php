<?
$top = './..';
$inc = "$top/inc";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_chara.php");
include("$inc/mn_error.php");
$img = "$top/image";

$monitor_id = $_SESSION['ss_monitor_id'];

if (isset($chara_name)) {
	if ($chara_name == '')
		$msg[] = 'ポイキャラの名前が入力されていないようです。';
}

if (isset($chara_type)) {
	if ($chara_type == '')
		$msg[] = 'ポイキャラが選択されていないようです。';
}
?>

<?
if (!$msg) {
?>

<?monitor_menu()?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>■　ぽいきゃら</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">　</td>
		<td colspan="3">
			<table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>■<font color="#32316b">　<b>あなたの選んだポイキャラはこれです</b></font>。 <br>
						<font size="2"><br>このポイキャラでよろしいですか？<br>このポイキャラでいい場合は「これにする」を、<br>別のポイキャラがいい場合は「やりなおす」をお選びください。<br><br></font>
						<form method="post" name="form1" action="m_chara_set.php">
						<input type="hidden" name="chara_name" <?=value($chara_name)?>>
						<input type="hidden" name="chara_type" <?=value($chara_type)?>>
						<table width="374" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><img src="<?=$img?>/circle_top.gif" width="374" height="10"></td>
							</tr>
							<tr>
								<td>
									<table width="374" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td bgcolor="#8584d2" width="1"><img src="<?=$img?>/spacer.gif" width="1" height="8"></td>
<?
$sql = "SELECT mp_total_point FROM t_monitor_point WHERE mp_monitor_id={$_SESSION['ss_monitor_id']}";
$chara_state = get_chara_state(db_fetch1($sql));
?>
											<td bgcolor="#e8f8ff" align="center" width="372">
<?
if ($chara_name != '') {
?>
												<?=$chara_name?><br>
<?
}
?>
												<br><img src="<?=$img?>/<?=get_chara_image_file($chara_type, $chara_state, 3)?>" width="70" height="92" alt="ポイキャラ"><br><br>
											</td>
											<td bgcolor="#8584d2" width="1"><img src="<?=$img?>/spacer.gif" width="1" height="8"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td><img src="<?=$img?>/table_nomal.gif" width="374" height="10"></td>
							</tr>
							<tr><td>　</td></tr>
							<tr>
								<td><center><input type="submit" value="これにする"><input type="button" value="やりなおす" onclick="history.back()"></center></td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
} else
	error_msg($msg);
?>