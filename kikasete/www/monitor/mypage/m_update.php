<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

if (isset($_SESSION['ss_regist'])) {
	unset($_SESSION['ss_regist']);
	session_unregister('ss_regist');
}

if (isset($_SESSION['ss_partner'])) {
	unset($_SESSION['ss_partner']);
	session_unregister('ss_partner');
}

if (isset($birthday_id))
	$_SESSION['birthday_id'] = $birthday_id;
?>
<? monitor_menu() ?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>■　プロフィール</SPAN>
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
		<td colspan="3"><table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td><font size="2"><table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td><font size="2">登録内容が変更になった場合は、こちらのページから登録内容をご変更ください。<br>
									あなたの興味ジャンルに適したアンケートが届くためにも、変更事項があれば、<br>
									まめに登録情報を更新してくださいね。 <br>
									<br>
									</font></td>
							</tr>
							<tr>
								<td bgcolor="#525294"><a href="m_update_b.php"><font color="#ffcc00" size="2"><img src="<?=$img?>/koshin.gif" width="111" height="16" align="right" border="0"></font></a><font color="#ffcc00" size="2">■</font><font color="white" size="2">基本情報を更新する　</font></td>
							</tr>
							<tr>
								<td><font size="2">氏名・未既婚・メールアドレス・郵便番号・居住地域・職業・勤務先情報<br><br></font></td>
							</tr>
						</table></font></td>
				</tr>
			</table></td>
	</tr>
</table>
</body>
</html>
