<?
$top = './..';
$inc = "$top/inc";
include("$inc/mn_header2.php");
$img = "$top/image";

$monitor_id = $_SESSION['ss_monitor_id'];
?>
<?monitor_menu()?>
<!--★コンテンツここから★-->
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>■　お問い合わせ</SPAN>
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td valign="top"><br>
			<table border="0" cellpadding="5" cellspacing="0" width="100%" bgcolor="white">
				<tr>
					<td><font color="#2b2b63"><b><br>まずは<a href="<?=$top?>/mypage/faq.php">FAQ</a>をご確認ください。その他にわからない点がございましたら、事務局までご質問ください。</b></font></td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#c3c2ff">
									<font color="#ffcc00"><b>●</b></font>
									<font size="2"><b>お問い合わせ先メールアドレス（<a href="mailto:m-info@kikasete.net">m-info@kikasete.net</a>)</b></font>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ebebeb"><font size="2"><br>きかせて・net では、メールによるお問い合わせ対応を、平日９：３０ 〜１８：００の間におこなっております。そのため、対応時間外にいただいたお問い合わせへの返信につきましては、多少お時間をいただいております。その点、ご了承頂けますよう、お願い致します。<br><br></font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!--★コンテンツここまで★-->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
