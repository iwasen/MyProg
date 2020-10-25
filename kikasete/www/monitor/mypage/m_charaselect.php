<?
$top = './..';
$inc = "$top/inc";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_chara.php");

$img = "$top/image";

$monitor_id = $_SESSION['ss_monitor_id'];

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
		<td width="15"><img src="<?=$img?>/spacer.gif" width="15" height="1"></td>
	</tr>
	<tr>
		<td width="15">　</td>
		<td colspan="3"><table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td><font color="#32316b"><b>■お気に入りのポイキャラを選ぼう！<br>
						</b></font><font size="2"><br>
						下の３タイプの中から、あなたのお好きなポイキャラを選んでください。<br>
						アンケートに回答し貯まったポイントや、アンケートの回答頻度によってポイキャラが育ちます。 <br>
						頑張ってよい子に育ててくださいね。 <br>
						</font>
						<form method="post" name="form1" action="m_characonfirm.php">
						<table width="374" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><img src="<?=$img?>/circle_top.gif" width="374" height="10"></td>
							</tr>
							<tr>
								<td>
									<table width="374" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td bgcolor="#8584d2" width="1"><img src="<?=$img?>/spacer.gif" width="1" height="8"></td>
											<td bgcolor="#e8f8ff" align="center" width="372">
<?
$sql = "SELECT mp_chara_name,mp_chara_type,mp_total_point FROM t_monitor_point WHERE mp_monitor_id={$_SESSION['ss_monitor_id']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$chara_name = $fetch->mp_chara_name;
	$chara_state = get_chara_state($fetch->mp_total_point);
	$chara_type = htmlspecialchars($fetch->mp_chara_type);
}
?>
												<font size="2">好きな名前を入れてください<br>
												<input type="text" name="chara_name" size="30" maxlength="15" <?=value($chara_name)?>><br><br><br>
												</font></td>
											<td bgcolor="#8584d2" width="1">
												<img src="<?=$img?>/spacer.gif" width="1" height="8"></td>
										</tr>
									</table>
									<table width="374" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td bgcolor="#8584d2" width="1"><img src="<?=$img?>/spacer.gif" width="1" height="8"></td>
											<td bgcolor="#e8f8ff" align="center" width="372">
												<table width="300" border="0" cellspacing="0" cellpadding="0">
													<tr align="center">
														<td><img src="<?=$img?>/<?=get_chara_image_file(1, $chara_state, 3)?>" width="70" height="92" alt="ポイキャラ"></td>
														<td rowspan="2"><img src="<?=$img?>/line_tate.gif" width="5" height="116"></td>
														<td><img src="<?=$img?>/<?=get_chara_image_file(2, $chara_state, 3)?>" width="70" height="91" alt="ポイキャラ"></td>
														<td rowspan="2"><img src="<?=$img?>/line_tate.gif" width="5" height="116"></td>
														<td><img src="<?=$img?>/<?=get_chara_image_file(3, $chara_state, 3)?>" width="70" height="92" alt="ポイキャラ"></td>
													</tr>
													<tr align="center">
														<td><input type="radio" name="chara_type" <?=value_checked('1', $chara_type)?>> </td>
														<td><input type="radio" name="chara_type" <?=value_checked('2', $chara_type)?>> </td>
														<td><input type="radio" name="chara_type" <?=value_checked('3', $chara_type)?>> </td>
													</tr>
													<tr><td><br></td></tr>
												</table></td>
											<td bgcolor="#8584d2" width="1"><img src="<?=$img?>/spacer.gif" width="1" height="8"></td>
										</tr>
									</table></td>
							</tr>
							<tr>
								<td><img src="<?=$img?>/table_nomal.gif" width="374" height="10"></td>
							</tr>

							<tr><td>　</td></tr>
							<tr>
								<td><center><input type="submit" value="これにする"></center></td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
			</table></td>
	</tr>
</table>

<!-- コンテンツ本体のソースはこの間に表記 -->
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>