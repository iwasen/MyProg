<?
$top = './..';
$inc = "$top/../inc";
include("$inc/enq_graph.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
// １万人アンケート
$sql = "SELECT en_enquete_id,en_title,en_description"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE fe_seq_no=$fno AND en_status=5";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->en_enquete_id;
	$title = htmlspecialchars($fetch->en_title);
	$description = nl2br(htmlspecialchars($fetch->en_description));
} else
	redirect('index.php');
?>

<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" background="<?=$img?>/title_bg.gif"><img src="<?=$img?>/title_enq.gif" width="320" height="39" alt="今週のきかせて１万人アンケート"></td>
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
					<td><font size="3" class=fs12_lh130>きかせて・netのモニターなら誰でも参加ＯＫ!<BR>毎週テーマが替わり「みんなはどんな風に思っているんだろう？」が一目で分かります。<BR>また選択肢を選んでポンと答えるだけで獲得ポイントが３〜５Ｐと結構お得！<BR>みなさんもぜひモニター登録して参加しませんか？<BR></font></td>
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
<?  /* 07/04/23 wakasa HTML差込機能追加 */ ?>
			<td align="left"><font size="3" class=fs12_lh130><strong>Q：<?=$question_no?>．<?=nl2br($fetch->eq_question_text)?></strong></font></td>
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
							<td align="left"><font size="3" class=fs12_lh130><strong>みんなの気持ち</strong></font></td>
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
	$html = '<TD width=10>　</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph.gif" width=%WIDTH%> %COUNT%</td>';
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
							<td align="left"><font size="3" class=fs12_lh130><strong>女性の気持ち</strong></font></td>
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
	$html = '<TD width=10>　</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph2.gif" width=%WIDTH%> %COUNT%</td>';
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
							<td align="left"><font size="3" class=fs12_lh130><strong>男性の気持ち</strong></font></td>
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
	$html = '<TD width=10>　</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph3.gif" width=%WIDTH%> %COUNT%</td>';
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
							<td><IMG height=10 src="<?=$img?>/graph4.gif" width=10></td>
							<td align="left"><font size="3" class=fs12_lh130><strong>独身者の気持ち</strong></font></td>
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
	$html = '<TD width=10>　</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph4.gif" width=%WIDTH%> %COUNT%</td>';
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
							<td><IMG height=10 src="<?=$img?>/graph5.gif" width=10></td>
							<td align="left"><font size="3" class=fs12_lh130><strong>既婚者の気持ち</strong></font></td>
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
	$html = '<TD width=10>　</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph5.gif" width=%WIDTH%> %COUNT%</td>';
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
				<TD align="right" height="30"><font size="3" class=fs12_lh130><IMG height=8 src="<?=$img?>/blue_arrow2.gif" width=7 align=absBottom vspace=4><A href="pastenq.php" class=fs12_lh130>過去の結果をみる</A></font></td>
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
<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
