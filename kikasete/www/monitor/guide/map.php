<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>

<?monitor_menu()?>
<!--������ƥ�Ĥ��������-->
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
		<TR>
			<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_map.gif" width=590 height=30 border=0 alt="�����ȥޥå�"></TD>
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
          <TD>��</TD>
          <TD><IMG SRC="<?=$img?>/spacer.gif" WIDTH="40" HEIGHT="8"></TD>
          <TD>��</TD>
        </TR>
        <TR>
          <TD height="20">������</TD>
          <TD><IMG SRC="<?=$img?>/sitemap_touroku.gif" alt="�����Ͽ" WIDTH="300" HEIGHT="20" vspace=3></TD>
          <TD>������</TD>
          <TD><IMG SRC="<?=$img?>/sitemap_service.gif" alt="�����ȥ����ӥ��ˤĤ���" WIDTH="300" HEIGHT="20" vspace=3></TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130">����<a href="<?=$top?>/regist/regist0.php">������˥�����Ͽ</a></TD>
          <TD>��</TD>
          <TD> ����<a href="<?=$top?>/guide/rules.php">��˥�������</a></TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130">&nbsp;</TD>
          <TD>��</TD>
          <TD>����<a href="<?=$top?>/guide/privacy.php">�ץ饤�Х����ݥꥷ��</a></TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130"><img src="<?=$img?>/sitemap_syoukai.gif" alt="�����ȾҲ�" width="300" height="20" vspace=3></TD>
          <TD>��</TD>
          <TD>����<a href="<?=$top?>/guide/faq.php">�ƣ���</a></TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130"> ����<a href="<?=$top?>/site_introduce/intro.php">���Ƥ�����</a></TD>
          <TD>��</TD>
          <TD>����<a href="<?=$top?>/guide/contactus.php">��̳�ɤؤΤ��䤤��碌</a></TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130"> ����<a href="<?=$top?>/site_introduce/enq.php">���󥱡��Ȥμ���</a></TD>
          <TD>��</TD>
          <TD>����<a href="<?=$top?>/guide/pwrequest.php">�ѥ���ɤ��䤤��碌</a></TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130"> ����<a href="<?=$top?>/site_introduce/point.php">�ݥ���Ȥθ�</a></TD>
          <TD>��</TD>
          <TD>&nbsp;</TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130">��</TD>
          <TD>��</TD>
          <TD><img src="<?=$img?>/sitemap_research.gif" alt="Ĵ����˾������" width="300" height="20" vspace=3></TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130"><img src="<?=$img?>/sitemap_contents.gif" alt="���󥱡��ȥǡ�������" width="300" height="20" vspace=3></TD>
          <TD>��</TD>
          <TD> ����<a href="http://www.kikasete.net/marketer/" target="_blank">�ޡ��������ڡ���</a></TD>
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
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130"> ����<a href="<?=$top?>/thousand_enq/w_enqresult.php?fno=<?=$fno?>">�����ͥ��󥱡���</a></TD>
          <TD>��</TD>
          <TD>&nbsp;</TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130"> ����<a href="<?=$top?>/trend_watch/list.php">�ȥ��ɥ����å�</a></TD>
          <TD>��</TD>
          <TD><img src="<?=$img?>/sitemap_company.gif" alt="��ҳ���" width="300" height="20" vspace=3></TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130"> ����<a href="<?=$top?>/conference/cvs.php">������٤��ļ�</a></TD>
          <TD>��</TD>
          <TD>����<a href="http://www.xxxxxxx.co.jp/" target="_blank">������ҡ�����������</a></TD>
        </TR>
        <TR>
          <TD height="20">��</TD>
          <TD CLASS="fs12_lh130">&nbsp;</TD>
          <TD>��</TD>
          <TD>&nbsp;</TD>
        </TR>
    </TABLE>

<!--������ƥ�Ĥ����ޤǡ�-->			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
