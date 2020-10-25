<?
$top = './..';
$inc = "$top/../inc";
include("$inc/enq_graph.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");

// ID����
if (is_numeric($id)) {
	$sql = "SELECT fe_enquete_id FROM t_free_enquete WHERE fe_seq_no=$id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$enquete_id = $fetch->fe_enquete_id;

		$sql = "SELECT en_enquete_id"
				. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
				. " WHERE en_status=7"
				. " ORDER BY en_enquete_id DESC";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			if ($fetch->en_enquete_id == $enquete_id) {
				$no = $i;
				break;
			}
		}
	}
}

if (!isset($no))
	redirect('pastenq.php');

// �����ͥ��󥱡���
$sql = "SELECT en_enquete_id,en_title,en_description"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE en_status=7"
		. " ORDER BY en_enquete_id DESC"
		. " LIMIT 2 OFFSET $no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->en_enquete_id;
	$title = htmlspecialchars($fetch->en_title);
	$description = nl2br(htmlspecialchars($fetch->en_description));
} else
	redirect('pastenq.php');
?>

<?monitor_menu()?>
<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" background="<?=$img?>/title_bg.gif"><img src="<?=$img?>/title_enq2.gif" width="320" height="39" alt="�����Τ������ƣ����ͥ��󥱡���"></td>
	</tr>
	<tr>
		<td align="center">
			<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD background="<?=$img?>/title_catch_01.gif"><IMG SRC="<?=$img?>/spacer.gif" WIDTH=1 HEIGHT=2></TD>
				</TR>
				<TR>
					<TD bgcolor="#E8F6FF">
					<table width="100%" border="0" cellspacing="10" cellpadding="0">
				<tr>
					<td><font size="3" class=fs12_lh130>�������ơ�net�Υ�˥����ʤ�ï�Ǥ⻲�ãϣ�!<BR>�轵�ơ��ޤ��ؤ��֤ߤ�ʤϤɤ�����˻פäƤ����������פ����ܤ�ʬ����ޤ���<BR>�ޤ�����������ǥݥ������������ǳ����ݥ���Ȥ��������Фȷ빽������<BR>�ߤʤ���⤼�ҥ�˥�����Ͽ���ƻ��ä��ޤ��󤫡�<BR></font></td>
				</tr>
			</table>
		</TD>
	</TR>
	<TR>
		<TD background="<?=$img?>/title_catch_03.gif"><IMG SRC="<?=$img?>/spacer.gif" WIDTH=1 HEIGHT=2></TD>
	</TR>
</TABLE>
<table width="660" border="0" cellspacing="0" cellpadding="0" align="left">
	<tr>
		<td align="center">
			<table width="620" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="<?=$img?>/spacer.gif" width="1" height="15" border="0"></td>
				</tr>
			</table>
	<table width="600" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td bgcolor="#DDDDDD">
				<table width="100%" border="0" cellspacing="1" cellpadding="10">
					<tr>
						<td align="left" bgcolor="#FFFFFF"><font size="3" class=fs12_lh130><strong><?=$title?></strong><BR><BR><?=$description?></font></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table width="620" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><img src="<?=$img?>/spacer.gif" width="1" height="15" border="0"></td>
		</tr>
	</table>
<?
$sql = "SELECT eq_question_no,eq_question_text FROM t_enq_question WHERE eq_enquete_id=$enquete_id ORDER BY eq_question_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$question_no = $fetch->eq_question_no;
	if ($fetch->eq_question_text != '') {
?>
	<table width="600" border="0" cellspacing="0" cellpadding="5" bgcolor="#EEEEEE">
		<tr>
<?  /* 07/04/23 wakasa HTML������ǽ�ɲ� */ ?>
			<td align="left"><font size="3" class=fs12_lh130><strong>Q��<?=$question_no?>��<?=nl2br($fetch->eq_question_text)?></strong></font></td>
		</tr>
	</table>
<?
	}
?>
	<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
		<TBODY>
			<TR>
				<TD align="left">
					<table border="0" cellspacing="5" cellpadding="0">
						<tr>
							<td><IMG height=10 src="<?=$img?>/graph.gif" width=10></td>
							<td align="left"><font size="3" class=fs12_lh130><strong>�ߤ�ʤε�����</strong></font></td>
						</tr>
					</table>
				</TD>
			</TR>
			<TR>
				<TD>
					<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
						<TBODY>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphtop><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
							<TR>
<?
	$scale = 380;
	$html = '<TD width=10>��</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph.gif" width=%WIDTH%> %COUNT%</td>';
	enq_graph_all($enquete_id, $question_no, $scale, $html, true);
?>
							</TR>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphbottom><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
			</TR>
		</TBODY>
	</TABLE>
	<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
		<TBODY>
			<TR>
				<TD align="left">
					<table border="0" cellspacing="5" cellpadding="0">
						<tr>
							<td><IMG height=10 src="<?=$img?>/graph2.gif" width=10></td>
							<td align="left"><font size="3" class=fs12_lh130><strong>�����ε�����</strong></font></td>
						</tr>
					</table>
				</TD>
			</TR>
			<TR>
				<TD>
					<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
						<TBODY>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphtop><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
							<TR>
<?
	$scale = 380;
	$html = '<TD width=10>��</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph2.gif" width=%WIDTH%> %COUNT%</td>';
	enq_graph_woman($enquete_id, $question_no, $scale, $html, true);
?>
							</TR>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphbottom><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
			</TR>
		</TBODY>
	</TABLE>
	<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
		<TBODY>
			<TR>
				<TD align="left">
					<table border="0" cellspacing="5" cellpadding="0">
						<tr>
							<td><IMG height=10 src="<?=$img?>/graph3.gif" width=10></td>
							<td align="left"><font size="3" class=fs12_lh130><strong>�����ε�����</strong></font></td>
						</tr>
					</table>
				</TD>
			</TR>
			<TR>
				<TD>
					<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
						<TBODY>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphtop><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
							<TR>
<?
	$scale = 380;
	$html = '<TD width=10>��</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph3.gif" width=%WIDTH%> %COUNT%</td>';
	enq_graph_man($enquete_id, $question_no, $scale, $html, true);
?>
							</TR>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphbottom><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
			</TR>
		</TBODY>
	</TABLE>
	<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
		<TBODY>
			<TR>
				<TD align="left">
					<table border="0" cellspacing="5" cellpadding="0">
						<tr>
							<td><IMG height=10 src="<?=$img?>/graph3.gif" width=10></td>
							<td align="left"><font size="3" class=fs12_lh130><strong>�ȿȼԤε�����</strong></font></td>
						</tr>
					</table>
				</TD>
			</TR>
			<TR>
				<TD>
					<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
						<TBODY>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphtop><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
							<TR>
<?
	$scale = 380;
	$html = '<TD width=10>��</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph4.gif" width=%WIDTH%> %COUNT%</td>';
	enq_graph_mikon($enquete_id, $question_no, $scale, $html, true);
?>
							</TR>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphbottom><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
			</TR>
		</TBODY>
	</TABLE>
	<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
		<TBODY>
			<TR>
				<TD align="left">
					<table border="0" cellspacing="5" cellpadding="0">
						<tr>
							<td><IMG height=10 src="<?=$img?>/graph3.gif" width=10></td>
							<td align="left"><font size="3" class=fs12_lh130><strong>�����Ԥε�����</strong></font></td>
						</tr>
					</table>
				</TD>
			</TR>
			<TR>
				<TD>
					<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
						<TBODY>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphtop><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
							<TR>
<?
	$scale = 380;
	$html = '<TD width=10>��</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph5.gif" width=%WIDTH%> %COUNT%</td>';
	enq_graph_kikon($enquete_id, $question_no, $scale, $html, true);
?>
							</TR>
							<TR>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
								<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
								<TD class=graphbottom><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<?
}
?>
	<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
		<TBODY>
			<TR>
				<TD align="right" height="30"><font size="3" class=fs12_lh130><IMG height=8 src="<?=$img?>/blue_arrow2.gif" width=7 align=absBottom vspace=4><A href="pastenq.php" class=fs12_lh130>���η�̤�ߤ�</A></font></td>
			</tr>
	</table>
</td>
</tr>
</table>
<table width="590" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td>
			<img src="<?=$img?>/spacer.gif" width="1" height="15" border="0">
		</td>
	</tr>
</table>
<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
