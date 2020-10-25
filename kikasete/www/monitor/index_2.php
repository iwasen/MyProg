<?
$top = '.';
$inc = "$top/../inc";
include("$inc/enq_graph.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header_2.php");
?>
<?monitor_menu()?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
		<TR>
			<TD background=image/header_top.gif colSpan=3 height=8><IMG height=8 src="image/spacer.gif" width=1></TD>
		</TR>
		<TR>
			<TD width=195 background=image/header_bg.gif height=59 rowSpan=2><A href="index.php" target="_top"><IMG height=59 src="image/logo.gif" width=195 border=0></A></TD>
			<TD width=18 background=image/header_bg.gif height=35></TD>
			<TD align=right background=image/header_bg.gif><IMG height=35 src="image/copy.gif" width=233></TD>
		</TR>
		<TR><TD width=18 height=24><IMG height=24 src="image/header_bg2.gif" width=18></TD>
			<TD align=right background=image/header_bg3.gif><A onmouseover="MM_swapImage('Image6','','image/header_home_on.gif',1)" onmouseout=MM_swapImgRestore() href="./index.php" target="_top"><IMG height=17 hspace=5 src="image/header_home_off.gif" width=51 border=0 name=Image6></A>
				<A onmouseover="MM_swapImage('Image7','','image/header_msitemap_on.gif',1)" onmouseout=MM_swapImgRestore()  href="./guide/map.php"><IMG height=17 hspace=5 src="image/header_msitemap_off.gif" width=73 border=0 name=Image7></A>
				<A onmouseover="MM_swapImage('Image8','','image/header_minquiry_on.gif',1)"  onmouseout=MM_swapImgRestore() href="./guide/contactus.php"><IMG height=17 hspace=5 src="image/header_minquiry_off.gif" width=91 border=0 name=Image8></A>
				<A onmouseover="MM_swapImage('Image9','','image/header_mresearch_on.gif',1)" onmouseout=MM_swapImgRestore() href="http://www.kikasete.net/marketer/" target="_blank"><IMG height=17 hspace=10 src="image/header_mresearch_off.gif" width=152 border=0 name=Image9></A>
			</TD>
		</TR>
		<TR>
			<TD background=image/header_bottom.gif colSpan=3 height=3><IMG height=3 src="image/spacer.gif" width=1></TD>
		</TR>
	</TBODY>
</TABLE>

<!-- コンテンツ本体のソースはこの間に表記 -->
<TABLE width=600 cellSpacing=0 cellPadding=0 border=0>
	<TBODY>
		<TR align=middle>
			<TD><IMG height=237 src="<?=$img?>/voice.jpg" width=600 vspace=1></TD>
		</TR>
		<TR align=middle>
			<TD bgColor=#c0e2fa>
				<A href="site_introduce/intro.php"><IMG height=80 src="<?=$img?>/main_hajimete_off.gif" width=195 border=0 name=Image16 onmouseover="this.src='<?=$img?>/main_hajimete_on.gif'" onmouseout="this.src='<?=$img?>/main_hajimete_off.gif'"></A>
				<A href="site_introduce/enq.php"><IMG height=80 hspace=3 src="<?=$img?>/main_enq_off.gif" width=195 border=0 name=Image17 onmouseover="this.src='<?=$img?>/main_enq_on.gif'" onmouseout="this.src='<?=$img?>/main_enq_off.gif'"></A>
				<A href="site_introduce/point.php"><IMG  height=80 src="<?=$img?>/main_point_off.gif" width=195 border=0 name=Image18  onmouseover="this.src='<?=$img?>/main_point_on.gif'" onmouseout="this.src='<?=$img?>/main_point_off.gif'"></A>
			</TD>
		</TR>
		<TR align=right>
			<TD>
				<A href="regist/regist0.php"><IMG height=37 src="<?=$img?>/body_monitor_off.gif" width=198 vspace=2 border=0 name=Image19 onmouseover="this.src='<?=$img?>/body_monitor_on.gif'"	onmouseout="this.src='<?=$img?>/body_monitor_off.gif'"></A>
			</TD>
		</TR>
		<TR>
			<TD>　</TD>
		</TR>
<?
// １万人アンケート
$sql = "SELECT fe_seq_no,fe_top_page,en_enquete_id,en_title"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE en_status=5 ORDER BY en_end_date LIMIT 1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$fno = $fetch->fe_seq_no;
	$top_page = $fetch->fe_top_page;
	$enquete_id = $fetch->en_enquete_id;
	$title = htmlspecialchars($fetch->en_title);
}
?>
		<TR>
			<TD bgColor=#c0e2fa>
				<TABLE cellSpacing=0 cellPadding=0 border=0>
					<TBODY>
						<TR>
							<TD><A href="./thousand_enq/w_enqresult.php?fno=<?=$fno?>"><IMG height=33 width=158 border=0 src="<?=$img?>/body_kikasete.gif" ></a><IMG height=1 src="<?=$img?>/spacer.gif" width=10></TD>
							<TD>あなたは多数派？それとも少数派？<BR> 毎週きかせて・net のみんなが１つになれるアンケートです。（獲得ポイント：３〜５Ｐ）</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><B><BR><?=$title?></B>
				<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
					<TBODY>
						<TR>
							<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							<TD class=graphtop><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
						</TR>
						<TR>
<?
if ($fno) {
	$scale = 440;
	$html = '<TD width=10>　</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./image/graph.gif" width=%WIDTH%> %COUNT%</td>';
	enq_graph_all($enquete_id, $top_page, $scale, $html);
} else {
	echo '<TD width=10>　</TD><TD align=left width=100></TD><TD class=graphmiddle width=450>現在実施中の１万人アンケートはありません。</td>';
}
?>
						<TR>
							<TD>　</TD>
							<TD>　</TD>
							<TD class=graphbottom align=right>
<?
if ($fno) {
?>
								<IMG height=8 src="<?=$img?>/blue_arrow2.gif" width=7 align=absBottom vspace=4>
								<A class=fs10_lh100 href="./thousand_enq/w_enqresult.php?fno=<?=$fno?>">今回の詳しい結果を見る</A> 
<?
}
?>
								<IMG height=8 src="<?=$img?>/blue_arrow2.gif" width=7 align=absBottom vspace=4>
								<A class=fs10_lh100 href="./thousand_enq/pastenq.php">過去の結果をみる</A>
							</TD>
						</TR>
					</TBODY>
				</TABLE><BR>
			</TD>
		</TR>
		<TR>
			<TD background=<?=$img?>/blue_bar.gif><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
		</TR>
		<TR>
			<TD>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TBODY>
						<TR>
							<TD>モニタなら誰でも参加<SPAN class=fs12_lh120>OK!テーマが毎週替わるアンケートです。あなたの１票で結果がかわるかもしれません。今</SPAN>すぐ登録してアンケートに参加してみませんか？</TD>
							<TD align=right width=150><A href="regist/regist0.php"><IMG height=24 src="<?=$img?>/body_shinki.gif" width=147 vspace=2 border=0></A></TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><IMG height=20 src="<?=$img?>/spacer.gif" width=1></TD>
		</TR>
		<TR>
			<TD bgColor=#c0e2fa>
				<TABLE cellSpacing=0 cellPadding=0 border=0>
					<TBODY>
						<TR>
							<TD><A href="trend_watch/list.php"><IMG height=33 src="<?=$img?>/body_trend.gif" width=158 border=0></a><IMG height=1 src="<?=$img?>/spacer.gif" width=10></TD>
							<TD>みなさんにおききしたアンケートの内、<BR>きかせて・net独自のアン ケート結果を紹介しています。（獲得ポイント：およそ１００Ｐ）</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD class=fs12_lh120>
				<IMG height=8 src="<?=$img?>/blue_arrow2.gif" width=7 align=absMiddle vspace=7><A href="trend_watch/tw90.php" target="_top">「第3のビール」に関するアンケート:【飲みやすさ】【後味のすっきりさ】が成長期を迎える「第3のビール」のベネフィットに！</A><BR>
				<IMG height=8 src="<?=$img?>/blue_arrow2.gif" width=7 align=absMiddle vspace=7><A href="trend_watch/tw89.php" target="_top">「愛知万博」に関するアンケート：万国博覧会の醍醐味である、国際色豊かなパビリオンの評価高し！<BR></A>
				<IMG height=8 src="<?=$img?>/blue_arrow2.gif" width=7 align=absMiddle vspace=7><A href="trend_watch/tw88.php" target="_top">「花粉症」に関するアンケート【後編】：充実してきた花粉症対策商品。効果以上の＋αが評価される段階に。</A>
			</TD>
		</TR>
		<TR>
			<TD background=<?=$img?>/blue_bar.gif><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
		</TR>
		<TR>
			<TD>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TBODY>
						<TR>
							<TD class=fs12_lh120>毎回、旬の商品・サービス・時事問題などをテーマに皆さんへアンケートを行っています。皆さんの回答が紹介されるかも！</TD>
							<TD align=right width=150><A href="trend_watch/list.php"><IMG height=24 src="<?=$img?>/body_kininaru.gif" width=147 vspace=2 border=0></A></TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><IMG height=20 src="<?=$img?>/spacer.gif" width=1></TD></TR>
		<TR>
			<TD bgColor=#c0e2fa>
				<TABLE cellSpacing=0 cellPadding=0 border=0>
					<TBODY>
						<TR>
							<TD><A href="conference/cvs.php"><IMG height=33 src="<?=$img?>/body_oshiaberi.gif" width=158 border=0></A><IMG height=1 src="<?=$img?>/spacer.gif" width=10></TD>
							<TD>日常生活での発見や驚きを、生活者(リサーチパートナー)の人々が<BR>リアル タイムにおしゃべりする場所です。（獲得ポイント：１ヶ月およそ２０００Ｐ）</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TBODY>
						<TR>
							<TD class=fs12_lh120>「新商品・新製品・お買い物」または「コンビニ」に関する話題が盛り沢山です！ “イマ”どんな商品が流行っているか、ぜひチェックしてみてくださいね！ </TD>
							<TD align=right width=150><A href="conference/rp.php"><IMG height=24 src="<?=$img?>/body_research.gif" width=147 vspace=2 border=0></A></TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><IMG height=20 src="<?=$img?>/spacer.gif" width=1></TD>
		</TR>
<?
// 事務局からのお知らせ
$sql = "SELECT cs_date,cs_title,cs_message FROM t_center_msg WHERE cs_monitor_top AND cs_status=0 AND (cs_start_date IS NULL OR cs_start_date<=CURRENT_TIMESTAMP) AND (cs_end_date IS NULL OR cs_end_date>=DATE_TRUNC('day', CURRENT_TIMESTAMP)) ORDER BY cs_date DESC,cs_seq_no DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
?>
		<TR>
			<TD>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TBODY>
						<TR>
							<TD class=fs12_lh120 vAlign=top width=156 rowSpan=2><IMG height=16 src="<?=$img?>/body_jimukyoku.gif" width=156></TD>
							<TD align=right bgColor=#c7d5ed><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
						</TR>
						<TR>
							<TD>　</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
<?
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
		<TR>
			<TD>
				<TABLE width="100%" border=0 align=left>
					<TBODY>
						<TR>
							<TD width="100%"><IMG height=8 src="<?=$img?>/blue_arrow2.gif" width=7 align=absMiddle vspace=7><?=htmlspecialchars($fetch->cs_title)?>：<?=nl2br($fetch->cs_message)?></TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
<?
	}
?>
		<TR><TD>　</TD></TR>
	</TBODY>
</TABLE>
<?
}
?>

<!-- コンテンツ本体のソースはこの間に表記 -->

<TABLE height=40 cellSpacing=0 borderColorDark=#223166 cellPadding=0 
width="100%" border=0>
  <TBODY>
  <TR>
    <TD background=image/footer_bg.gif><IMG height=5 
      src="image/spacer.gif" width=1></TD></TR>
  <TR>
    <TD class=copy background=image/footer_bg2.gif height=36><SPAN 
      class=copy2 style="color:#ffffff;">Copyright (C) 2000-2005 <A class=copylink 
      href="http://www.xxxxxxx.co.jp/monitor/index.html" target=_blank>xx xxxxx 
      Inc.</A> All rights reserved 
      .</SPAN><BR><span style="color:#ffffff;">このホームページに使われている画像・写真・記事等は、全て株式会社○○○○○○に帰属します。無断で複製・再利用することは法律により禁じられています。</span><BR></TD></TR></TBODY></TABLE>

<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>

</BODY>
</HTML>
