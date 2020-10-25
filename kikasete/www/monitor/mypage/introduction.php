<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
?>
<? monitor_menu('', false) ?>
<form method="post" action="introduction2.php">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#c0e2fa>
				<TD>
					<IMG height=29 alt=アフィリエイト src="<?=$img?>/my_affiliate.gif" width=45 align=absMiddle>
					<SPAN class=mysttl>アフィリエイト </SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">　</td>
		<td colspan="3"><table width="481" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">■　</font><font color="white">きかせて・net紹介　応募フォーム</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br>
									</b></font><font size="2"><br>
									「</font><font size="2" color="#ff0009">※</font><font size="2">」 のある項目は必須項目となりますので、必ずご記入ください。<br>
									</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2">
												<hr>
												</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> あなたのホームページURL</font></td>
											<td colspan="2"><font size="2"><input type="text" name="web_url" size="39" maxlength="100" value=""></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> ホームページの簡単な説明</font></td>
											<td colspan="2"><font size="2"><textarea name="introduction" rows="4" cols="40"></textarea></font></td>
										</tr>
									</table></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
											<input type="submit" value="　確認　">
											<input type="button" value="　戻る　" onclick="history.back()">
										</font></center>
								</td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
<input type="hidden" name="affiliate_mgr_id" <?=value($id)?>>
</form>