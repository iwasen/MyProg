<?
$top = './..';
$inc = "$top/inc";
include("$inc/mn_header2.php");
$img = "$top/image";

$monitor_id = $_SESSION['ss_monitor_id'];
?>
<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
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
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
<TBODY>
	<TR><TD><IMG src="<?=$img?>/spacer.gif" width=1 height=25 border=0></TD></TR>
	<TR>
	<TD align="left">
<SPAN class=fs12_lh130>
<font color="#A771B0">■&nbsp;</font><B>自分だけのキャラクター「ポイキャラ」</B><BR>
</SPAN>
	</TD>
	</TR>
	<TR><TD><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD></TR>
	<TR>
	<TD align="left">
<SPAN class=fs12_lh130>
ポイントを貯めるごとに成長する、自分だけのキャラクター、その名も「ポイキャラ（そのままですが・・・）」。<BR>
<IMG src="<?=$img?>/enq_poichara.gif" width=400 height=120 border=0 alt="image" vspace="15"><BR>
ポイキャラは、過去のアンケートに何回参加したかで『優等生』や『不良』になったり、
これまで貯めたポイントによって『赤ちゃん』から『大人』へと成長するようになっています。<BR>
<IMG src="<?=$img?>/enq_poichara2.gif" width=400 height=400 border=0 alt="image" vspace="15">
</SPAN>
	</TD>
	</TR>
	</TBODY>
	</TABLE>

</TD>
</TR>
</TBODY>
</TABLE>

<!-- コンテンツ本体のソースはこの間に表記 -->
		</TD>
	</TR>
</TABLE>
</BODY>
</HTML>