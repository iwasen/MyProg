<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>

<?monitor_menu()?>
<!--★コンテンツここから★-->
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
		<TR>
			<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_map.gif" width=590 height=30 border=0 alt="サイトマップ"></TD>
			<TD align="right" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_bk.gif" width=10 height=30 border=0></TD>
		</TR>
		<TR>
			<TD align="left" colspan="2"><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
		</TR>
	</TBODY>
</TABLE>
      <TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">
        <TR>
          <TD height="20"><IMG SRC="<?=$img?>/spacer.gif" WIDTH="40" HEIGHT="8"></TD>
          <TD>　</TD>
          <TD><IMG SRC="<?=$img?>/spacer.gif" WIDTH="40" HEIGHT="8"></TD>
          <TD>　</TD>
        </TR>
        <TR>
          <TD height="20">　　　</TD>
          <TD><IMG SRC="<?=$img?>/sitemap_touroku.gif" alt="会員登録" WIDTH="300" HEIGHT="20" vspace=3></TD>
          <TD>　　　</TD>
          <TD><IMG SRC="<?=$img?>/sitemap_service.gif" alt="サイトサービスについて" WIDTH="300" HEIGHT="20" vspace=3></TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130">　・<a href="<?=$top?>/regist/regist0.php">新規モニター登録</a></TD>
          <TD>　</TD>
          <TD> 　・<a href="<?=$top?>/guide/rules.php">モニター規約</a></TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130">&nbsp;</TD>
          <TD>　</TD>
          <TD>　・<a href="<?=$top?>/guide/privacy.php">プライバシーポリシー</a></TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130"><img src="<?=$img?>/sitemap_syoukai.gif" alt="サイト紹介" width="300" height="20" vspace=3></TD>
          <TD>　</TD>
          <TD>　・<a href="<?=$top?>/guide/faq.php">ＦＡＱ</a></TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130"> 　・<a href="<?=$top?>/site_introduce/intro.php">初めての方へ</a></TD>
          <TD>　</TD>
          <TD>　・<a href="<?=$top?>/guide/contactus.php">事務局へのお問い合わせ</a></TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130"> 　・<a href="<?=$top?>/site_introduce/enq.php">アンケートの種類</a></TD>
          <TD>　</TD>
          <TD>　・<a href="<?=$top?>/guide/pwrequest.php">パスワードお問い合わせ</a></TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130"> 　・<a href="<?=$top?>/site_introduce/point.php">ポイントの交換</a></TD>
          <TD>　</TD>
          <TD>&nbsp;</TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130">　</TD>
          <TD>　</TD>
          <TD><img src="<?=$img?>/sitemap_research.gif" alt="調査希望の方へ" width="300" height="20" vspace=3></TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130"><img src="<?=$img?>/sitemap_contents.gif" alt="アンケートデータ公開" width="300" height="20" vspace=3></TD>
          <TD>　</TD>
          <TD> 　・<a href="http://www.kikasete.net/marketer/" target="_blank">マーケターページ</a></TD>
        </TR>
<?
$sql = "SELECT fe_seq_no,fe_top_page,en_enquete_id,en_title"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE en_status=5 ORDER BY en_end_date LIMIT 1";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
$fno = $fetch->fe_seq_no;
?>

        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130"> 　・<a href="<?=$top?>/thousand_enq/w_enqresult.php?fno=<?=$fno?>">１万人アンケート</a></TD>
          <TD>　</TD>
          <TD>&nbsp;</TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130"> 　・<a href="<?=$top?>/trend_watch/list.php">トレンドウォッチ</a></TD>
          <TD>　</TD>
          <TD><img src="<?=$img?>/sitemap_company.gif" alt="会社概要" width="300" height="20" vspace=3></TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130"> 　・<a href="<?=$top?>/conference/cvs.php">おしゃべり会議室</a></TD>
          <TD>　</TD>
          <TD>　・<a href="http://www.xxxxxxx.co.jp/" target="_blank">株式会社○○○○○○</a></TD>
        </TR>
        <TR>
          <TD height="20">　</TD>
          <TD CLASS="fs12_lh130">&nbsp;</TD>
          <TD>　</TD>
          <TD>&nbsp;</TD>
        </TR>
    </TABLE>

<!--★コンテンツここまで★-->			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
