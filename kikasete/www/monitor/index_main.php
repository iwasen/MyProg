<?
$top = '.';
$inc = "$top/../inc";
include("$inc/enq_graph.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>
<?monitor_menu()?>
<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
<TABLE width="600" cellspacing="0" cellpadding="0" border="0">
	<TBODY>
		<TR align="middle">
			<TD><IMG height="237" src="<?=$img?>/voice.jpg" width="600" vspace="1"></TD>
		</TR>
		<TR align="middle">
			<TD bgcolor="#c0e2fa">
				<A href="site_introduce/intro.php"><IMG height="80" src="<?=$img?>/main_hajimete_off.gif" width="195" border="0" name="Image16" onMouseOver="this.src='<?=$img?>/main_hajimete_on.gif'" onMouseOut="this.src='<?=$img?>/main_hajimete_off.gif'"></A>
				<A href="site_introduce/enq.php"><IMG height="80" hspace="3" src="<?=$img?>/main_enq_off.gif" width="195" border="0" name="Image17" onMouseOver="this.src='<?=$img?>/main_enq_on.gif'" onMouseOut="this.src='<?=$img?>/main_enq_off.gif'"></A>
				<A href="site_introduce/point.php"><IMG  height="80" src="<?=$img?>/main_point_off.gif" width="195" border="0" name="Image18"  onmouseover="this.src='<?=$img?>/main_point_on.gif'" onMouseOut="this.src='<?=$img?>/main_point_off.gif'"></A>
			</TD>
		</TR>
		<TR align="right">
			<TD>
				<A href="regist/regist0.php"><IMG height="37" src="<?=$img?>/body_monitor_off.gif" width="198" vspace="2" border="0" name="Image19" onMouseOver="this.src='<?=$img?>/body_monitor_on.gif'"	onmouseout="this.src='<?=$img?>/body_monitor_off.gif'"></A>
			</TD>
		</TR>

<?
// �����ͥ��󥱡���
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
			<TD bgcolor="#c0e2fa">
				<TABLE cellspacing="0" cellpadding="0" border="0">
					<TBODY>
						<TR>
							<TD><A href="./thousand_enq/w_enqresult.php?fno=<?=$fno?>"><IMG height="33" width="158" border="0" src="<?=$img?>/body_kikasete.gif" ></A><IMG height="1" src="<?=$img?>/spacer.gif" width="10"></TD>
							<TD>���ʤ���¿���ɡ�����Ȥ⾯���ɡ�<BR> �轵�������ơ�net �Τߤ�ʤ����Ĥˤʤ�륢�󥱡��ȤǤ����ʳ����ݥ���ȡ��������С�</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><B><BR><?=$title?></B>
				<TABLE cellspacing="0" cellpadding="2" width="100%" border="0">
					<TBODY>
						<TR>
							<TD><IMG height="1" src="<?=$img?>/spacer.gif" width="1"></TD>
							<TD><IMG height="1" src="<?=$img?>/spacer.gif" width="1"></TD>
							<TD class="graphtop"><IMG height="1" src="<?=$img?>/spacer.gif" width="50"></TD>
						</TR>
						<TR>
<?
if ($fno) {
	$scale = 440;
	$html = '<TD width=10>��</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./image/graph.gif" width=%WIDTH%> %COUNT%</td>';
	enq_graph_all($enquete_id, $top_page, $scale, $html);
} else {
	echo '<TD width=10>��</TD><TD align=left width=100></TD><TD class=graphmiddle width=450>���߼»���Σ����ͥ��󥱡��ȤϤ���ޤ���</td>';
}
?>
						<TR>
							<TD>��</TD>
							<TD>��</TD>
							<TD class="graphbottom" align="right">
<?
if ($fno) {
?>
								<IMG height="8" src="<?=$img?>/blue_arrow2.gif" width="7" align="absbottom" vspace="4">
								<A class="fs10_lh100" href="./thousand_enq/w_enqresult.php?fno=<?=$fno?>">����ξܤ�����̤򸫤�</A> 
<?
}
?>
								<IMG height="8" src="<?=$img?>/blue_arrow2.gif" width="7" align="absbottom" vspace="4">
								<A class="fs10_lh100" href="./thousand_enq/pastenq.php">���η�̤�ߤ�</A>
							</TD>
						</TR>
					</TBODY>
				</TABLE><BR>
			</TD>
		</TR>
		<TR>
			<TD background="<?=$img?>/blue_bar.gif"><IMG height="1" src="<?=$img?>/spacer.gif" width="1"></TD>
		</TR>
		<TR>
			<TD>
				<TABLE cellspacing="0" cellpadding="0" width="100%" border="0">
					<TBODY>
						<TR>
							<TD>��˥��ʤ�ï�Ǥ⻲��<SPAN class="fs12_lh120">OK!�ơ��ޤ��轵�ؤ�륢�󥱡��ȤǤ������ʤ��Σ�ɼ�Ƿ�̤�����뤫�⤷��ޤ��󡣺�</SPAN>������Ͽ���ƥ��󥱡��Ȥ˻��ä��Ƥߤޤ��󤫡�</TD>
							<TD align="right" width="150"><A href="regist/regist0.php"><IMG height="24" src="<?=$img?>/body_shinki.gif" width="147" vspace="2" border="0"></A></TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><IMG height="20" src="<?=$img?>/spacer.gif" width="1"></TD>
		</TR>
		<TR>
			<TD bgcolor="#c0e2fa">
				<TABLE cellspacing="0" cellpadding="0" border="0">
					<TBODY>
						<TR>
							<TD><A href="trend_watch/list.php"><IMG height="33" src="<?=$img?>/body_trend.gif" width="158" border="0"></A><IMG height="1" src="<?=$img?>/spacer.gif" width="10"></TD>
							<TD>�ߤʤ���ˤ������������󥱡��Ȥ��⡢<BR>�������ơ�net�ȼ��Υ��� �����ȷ�̤�Ҳ𤷤Ƥ��ޤ����ʳ����ݥ���ȡ����褽�������С�</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD class="fs12_lh120"><IMG height="8" src="<?=$img?>/blue_arrow2.gif" width="7" align="absmiddle" vspace="7"><A href="trend_watch/tw106.php" target="_top">����å�99�Ǥι����ư�˴ؤ��륢�󥱡���:
����å�99�ȥ����ѡ��ޡ����åȤǤο��ʤι����ư�ϡ�����Ź���١ס���Źư���סֹ������ʡפ˰㤤���ꡪ 
</A><BR>
	  </TD>
		</TR>
		<TR>
			<TD background="<?=$img?>/blue_bar.gif"><IMG height="1" src="<?=$img?>/spacer.gif" width="1"></TD>
		</TR>
		<TR>
			<TD>
				<TABLE cellspacing="0" cellpadding="0" width="100%" border="0">
					<TBODY>
						<TR>
							<TD class="fs12_lh120">��󡢽ܤξ��ʡ������ӥ�����������ʤɤ�ơ��ޤ˳�����إ��󥱡��Ȥ�ԤäƤ��ޤ���������β������Ҳ𤵤�뤫�⡪</TD>
							<TD align="right" width="150"><A href="trend_watch/list.php"><IMG height="24" src="<?=$img?>/body_kininaru.gif" width="147" vspace="2" border="0"></A></TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><IMG height="20" src="<?=$img?>/spacer.gif" width="1"></TD></TR>
		<TR>
			<TD bgcolor="#c0e2fa">
				<TABLE cellspacing="0" cellpadding="0" border="0">
					<TBODY>
						<TR>
							<TD><A href="conference/cvs.php"><IMG height="33" src="<?=$img?>/body_oshiaberi.gif" width="158" border="0"></A><IMG height="1" src="<?=$img?>/spacer.gif" width="10"></TD>
							<TD>��������Ǥ�ȯ����ä��������(�ꥵ�����ѡ��ȥʡ�)�ο͡���<BR>�ꥢ�� ������ˤ�����٤ꤹ����Ǥ����ʳ����ݥ���ȡ�������褽���������С�</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE cellspacing="0" cellpadding="0" width="100%" border="0">
					<TBODY>
						<TR>
							<TD class="fs12_lh120">�ֿ����ʡ������ʡ����㤤ʪ�פޤ��ϡ֥���ӥˡפ˴ؤ������꤬���������Ǥ��� �ȥ��ޡɤɤ�ʾ��ʤ�ή�ԤäƤ��뤫�����ҥ����å����ƤߤƤ��������͡� </TD>
							<TD align="right" width="150"><A href="conference/rp.php"><IMG height="24" src="<?=$img?>/body_research.gif" width="147" vspace="2" border="0"></A></TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><IMG height="20" src="<?=$img?>/spacer.gif" width="1"></TD>
		</TR>
		<TR>
			<TD bgcolor="#c0e2fa">
				<TABLE cellspacing="0" cellpadding="0" border="0">
					<TBODY>
						<TR>
							<TD><A href="haimail/haimail.php"><IMG height="33" src="<?=$img?>/body_haimail.gif" width="158" border="0"></A><IMG height="1" src="<?=$img?>/spacer.gif" width="10"></TD>
							<TD>��CM�Ǥ�ή��Ƥ��ʤ��סֻ���ˤ�ܤäƤ��ʤ��ס�¾�Ǥϸ��뤳�ȡ�������뤳�ȤΤǤ��ʤ��׺ǿ����󡦤��ȥ��ʾ��󤬥᡼����Ϥ��ޤ���</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE cellspacing="0" cellpadding="0" width="100%" border="0">
					<TBODY>
						<TR>
							<TD class="fs12_lh120">�֤������ơ�net�פ���Ͽ���Ƥ����������ֶ�̣�����뤳�ȡס֤ۤ�������פˤ��ä��᡼��򡢤��Ϥ����ޤ���</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><IMG height="20" src="<?=$img?>/spacer.gif" width="1"></TD>
		</TR>
<?
// ��̳�ɤ���Τ��Τ餻
$sql = "SELECT cs_date,cs_title,cs_message FROM t_center_msg WHERE cs_monitor_top AND cs_status=0 AND (cs_start_date IS NULL OR cs_start_date<=CURRENT_TIMESTAMP) AND (cs_end_date IS NULL OR cs_end_date>=DATE_TRUNC('day', CURRENT_TIMESTAMP)) ORDER BY cs_date DESC,cs_seq_no DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
?>
		<TR>
			<TD>
				<TABLE cellspacing="0" cellpadding="0" width="100%" border="0">
					<TBODY>
						<TR>
							<TD class="fs12_lh120" valign="top" width="156" rowspan="2"><IMG height="16" src="<?=$img?>/body_jimukyoku.gif" width="156"></TD>
							<TD align="right" bgcolor="#c7d5ed"><IMG height="1" src="<?=$img?>/spacer.gif" width="1"></TD>
						</TR>
						<TR>
							<TD>��</TD>
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
				<TABLE width="100%" border="0" align="left">
					<TBODY>
						<TR>
							<TD width="100%"><IMG height="8" src="<?=$img?>/blue_arrow2.gif" width="7" align="absmiddle" vspace="7"><?=htmlspecialchars($fetch->cs_title)?>��<?=nl2br($fetch->cs_message)?></TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
<?
	}
?>
		<TR><TD>��</TD></TR>
	</TBODY>
</TABLE>
<?
}
?>

<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->

			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
