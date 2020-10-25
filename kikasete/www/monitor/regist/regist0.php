<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>

<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->

<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left" bgcolor="#EEEEEE" background="../image/tit_bk.gif"><IMG src="../image/tit_reg.gif" width=590 height=30 border=0 alt="モニター登録"></TD>
		<TD align="right" bgcolor="#EEEEEE" background="../image/tit_bk.gif"><IMG src="../image/tit_bk.gif" width=10 height=30 border=0></TD>
	</TR>
	<TR>
		<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
	</TR>
</TABLE>
		<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
			<TBODY>
				<TR>
					<TD align="left"><SPAN class=fs12_lh120><font color="#666666">モニター登録していただく過程で、あなたの基本情報、生活情報をお伺いしています。ご登録いただく情報は、適切なアンケートをお届けするためにお尋ねしており、本人の同意なく第三者に開示することはありません。詳しくは『規約の確認』でご確認ください。<BR><BR></font></SPAN></TD>
				</TR>
				<TR>
					<TD align="center"><IMG src="../image/reg_flow.gif" width=600 height=110 border=0 alt="モニター登録の流れ" vspace="15"></TD>
				</TR>

				<TR>
					<TD align="left">
						<TABLE cellSpacing=0 cellPadding=10 border=0 width="100%">
							<TR>
								<TD align="left" bgcolor="#EEEEEE">
									<SPAN class=fs12_lh120><font color="#666666"><B>■登録前のご注意</B><BR><BR>●登録は無料です。<BR>●フリーのメールアドレスでご登録されると、きかせて・netよりお送りするメールが届かないケースがございます。<br>　なるだけインターネットプロバイダ様から提供されたメールアドレスでご登録ください。<BR>●携帯メールアドレスでのご登録はご遠慮ください。アンケートにお答えいただくことができません。<BR>●16歳以下の方は、アンケートによって保護者の方の同意書が必要になります。<BR>●アンケートにはご登録者本人がお答えください。<BR>●複数メールアドレスによる多重登録は禁止しております。</font></SPAN>
								</TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
				<TR>
					<TD align="left">
						<TABLE cellSpacing=0 cellPadding=10 border=0 width="100%">
							<TR>
								<TD align="left" bgcolor="#EEEEEE">
									<SPAN class=fs12_lh120><font color="#666666"><B>■&nbsp;SSLによる登録情報の暗号化について</B><BR><BR>皆さんに安心してご登録頂くために、きかせて・net では、SSLにより登録内容を暗号化し、登録情報の送受信をおこなっております。ただ、ブラウザによっては、SSLに対応していないものもございます。ご使用のブラウザのバージョンをご確認の上、当てはまる方をクリックして、登録ページへとお進みください。<BR><BR></font></SPAN>
								</TD>
							</TR>
							<TR>
								<TD align="left" bgcolor="#EEEEEE">
									<TABLE cellSpacing=8 cellPadding=0 border=0 width="550">
										<TR>
											<TD align="left" colspan="3">
												<SPAN class=fs12_lh120><B><font color="#088F8F">［&nbsp;SSL対応ブラウザ&nbsp;］<BR></font></B></SPAN>
											</TD>
										</TR>
										<TR>
											<TD align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Netscape Communicator 4.7以降<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Microsoft Internet Explorer 5.0以降<BR></TD>
											<TD align="left" valign="top"><IMG src="../image/reg_ya.gif" width=40 height=60 border=0></TD>
											<TD align="left" valign="top"><a href="<?=https_url('regist.php')?>"><IMG src="../image/reg_btn01_off.gif" width=160 height=60 border=0 onmouseover="this.src='../image/reg_btn01_on.gif'" onmouseout="this.src='../image/reg_btn01_off.gif'"></a></TD>
										</TR>
										<TR>
											<TD align="left"><SPAN class=fs12_lh120><B><font color="#75479E">［&nbsp;SSL非対応ブラウザ&nbsp;］<BR></font></B></SPAN></TD>
											<TD align="left" valign="top" rowspan="2"><IMG src="../image/reg_ya.gif" width=40 height=60 border=0></TD>
											<TD align="left" valign="top" rowspan="2"><a href="<?=http_url('regist.php')?>"><IMG src="../image/reg_btn02_off.gif" width=160 height=60 border=0 onmouseover="this.src='../image/reg_btn02_on.gif'" onmouseout="this.src='../image/reg_btn02_off.gif'"></a></TD>
										</TR>
										<TR>
											<TD align="left" valign="top"><SPAN class=fs12_lh120><font color="#666666"> &nbsp;&nbsp;&nbsp;上記にあてはまらない場合は、SSLに対応していません。<BR> &nbsp;&nbsp;&nbsp;恐れ入りますが、SSL非対応ページよりご登録ください。<BR></SPAN></TD>
										</TR>
									</TABLE>
								</TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE>
<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
