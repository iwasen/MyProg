<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>

<?monitor_menu()?>
<!-- コンテンツここから -->
	<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
		<TBODY>
			<TR>
				<TD align="left" bgcolor="#AC9D69" background="<?=$img?>/tit_poi_bk.gif"><IMG src="<?=$img?>/tit_poi_01.gif" width=590 height=60 border=0 alt="ポイントの交換"></TD>
				<TD align="right" bgcolor="#AC9D69" background="<?=$img?>/tit_poi_bk.gif"><IMG src="<?=$img?>/tit_poi_02.gif" width=10 height=60 border=0></TD>
			</TR>
			<TR>
				<TD align="left" colspan="2"><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
			</TR>
			<TR>
				<TD colspan="2">
					<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
						<TBODY>
							<TR>
								<TD align="left">
									<SPAN class=fs12_lh120><font color="#AC9D69">■&nbsp;</font><strong>アンケートにご回答いただくと、そのアンケートの謝礼としてモニターのみなさんにポイント（１ポイント＝１円）<br>&nbsp;&nbsp;&nbsp;がつきます。2000ポイント以上で「VISAギフト券」と交換、「楽天銀行」にて換金できます。</strong><BR></SPAN>
								</TD>
							</TR>
							<TR>
								<TD><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
							</TR>
							<TR>
								
          <TD align="left" background="<?=$img?>/enq_m01_bg.gif"><img src="<?=$img?>/poi_m0101.gif" width=600 height=40 border=0 alt="VISAギフト券"></TD>
							</TR>
							<TR>
								<TD><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
							</TR>
							<TR>
								
          <TD align="left"><SPAN class=fs12_lh130><img src="<?=$img?>/visagiftcard.gif" border=0 alt="VISAギフト券" align="left" hspace="10" width=150 height=72>全国50万店以上で使える全国共通の商品券です。<br>
            買い物時に最適です。<br>
            </SPAN></TD>
							</TR>
							<TR>
								<TD><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
							</TR>
<?/*							<TR>
								
          <TD align="left" background="<?=$img?>/enq_m01_bg.gif"><img src="<?=$img?>/poi_m0102.gif" width=600 height=40 border=0 alt="図書券"></TD>
							</TR>
							<TR>
								<TD><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
							</TR>
							<TR>
								
          <TD align="left"><SPAN class=fs12_lh130><img src="<?=$img?>/toshocard.gif" border=0 alt="図書券" align="left" hspace="10" width=150 height=72>全国の取り扱い書店で使える図書カードです。<br>
            本好きな方はもちろん、バースデープレゼント・入学祝等の贈り物としても使えます。</SPAN></TD>
							</TR>
							<TR>
								<TD><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
							</TR>
*/?>							<TR>
								
          <TD align="left" background="<?=$img?>/enq_m01_bg.gif"><img src="<?=$img?>/poi_m0104.gif" width=600 height=40 border=0 alt="楽天銀行"></TD>
							</TR>
							<TR>
								<TD><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
							</TR>
							<TR>
								
          <TD align="left"><img src="<?=$img?>/ebank.gif" border=0 alt="楽天銀行" align="left" hspace="20" height="28" width="115" ><SPAN class=fs12_lh130>口座維持手数料無料のインターネット上の銀行です。<br />
            パソコンや携帯電話から残高照会やお振込などができ、２４時間入出金可能です。<br /><br />
            　　&gt;&gt;詳しくは、<a href="http://www.ebank.co.jp/index.html" target="_blank">楽天銀行ホームページ</a>をご覧ください。<br />
            </SPAN></TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
			</TR>
			<TR>
				<TD colspan="2"><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
			</TR>
			<TR>
				<TD colspan="2" align="right"><a href="<?=$top?>/regist/regist0.php"><IMG src="<?=$img?>/enq_monitor_off.gif" width=150 height=30 border=0 alt="モニター登録はこちら" vspace="10" onmouseover="this.src='<?=$img?>/enq_monitor_on.gif'" onmouseout="this.src='<?=$img?>/enq_monitor_off.gif'"></a></TD>
			</TR>
		</TBODY>
	</TABLE>
<!-- コンテンツここまで -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
