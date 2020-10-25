<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
?>
<? monitor_menu() ?>
<form method="post" action="m_affi_mail2.php">
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
								<td>
									<font size="2"><br>ご紹介人予定人数を教えてください</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2">
												<hr>
												</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> ご紹介予定人数</font></td>
											<td colspan="2"><font size="2"><input type="text" name="intro_num" size="10" maxlength="10" value="">人</font></td>
										</tr>
									</table></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
											<input type="submit" value="　確認　">
											<input type="button" value="　戻る　" onclick="location.href='m_index_main.php'">
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