<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/enquete.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// ���ϥѥ�᡼������
$enquete_id = $_GET['enquete_id'];
$monitor_id = $_SESSION['ss_monitor_id'];

// ���󥱡���ID��̵����Хȥåפ��᤹
if ($enquete_id == '')
	redirect('m_index.php');

// �����Ѥߤʤ�Хȥåפ��᤹
$sql = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id";
if (db_fetch1($sql))
	redirect('m_index.php');

// ���󥱡������Ƽ���
$enquete = new enquete_class;
$enquete->read_db($enquete_id);

// �����Υ��󥱡��Ȥ��»���Ǥʤ���Хȥåפ��᤹
if ($enquete->status != 5)
	redirect('m_index.php');
?>
<HTML>
<HEAD>
<TITLE>�������ʪ�Υ��󥱡��ȥ�˥����å��󥱡��Ȥ��������</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/JavaScript">
<link rel="stylesheet" href="style.css" type="text/css">
<script language="JavaScript" src="mouseover.js"></script>
</HEAD>
<body bgcolor=#DDDDDD text=#333333 link=#333333 vlink=#333333 alink=#333333 
leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth=0 marginheight=0>

<TABLE WIDTH=760 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor=#FFFFFF valign=top height=900>

	<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
	<TD background="images/monitor_title2.gif">

		<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
		<TD ROWSPAN=3>
			<IMG SRC="images/m_header_01.gif" WIDTH=618 HEIGHT=106></TD>
		<TD>
			<IMG SRC="images/m_header_02.gif" WIDTH=90 HEIGHT=75></TD>
		<TD>
			<IMG SRC="images/m_header_03.gif" WIDTH=12 HEIGHT=75></TD>
		</TR>
		<TR>
		<TD><a href="m_logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_logout','','images/b_logout_over.gif',1)"><IMG SRC="images/b_logout.gif" WIDTH=90 HEIGHT=24 name=b_logout BORDER=0 ALT="��������"></TD>
		<TD>
			<IMG SRC="images/m_header_05.gif" WIDTH=12 HEIGHT=24></TD>
		</TR>
		<TR>
		<TD>
			<IMG SRC="images/m_header_06.gif" WIDTH=90 HEIGHT=7></TD>
		<TD>
			<IMG SRC="images/m_header_07.gif" WIDTH=12 HEIGHT=7></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#FFFFFF"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD bgcolor="#99CCFF" height=18>

		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width=720>
		<TR>
		<TD WIDTH=25><IMG SRC="images/spacer.gif" WIDTH=25 HEIGHT=18></TD>
		<TD align="left"><span class="t10"><a href="m_index.php">���߼»���Υ��󥱡��Ȱ���</a>&nbsp;��&nbsp;���󥱡��Ȥ�������</span></TD>
		<TD align="right" WIDTH=400><span style="font-size:14px; font-weight:bold; color:#FF6600"><a href="m_change_add.php">��<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />�᡼�륢�ɥ쥹���ѹ���</a>&nbsp;<a href="m_change_pw.php">��<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />�ѥ�����ѹ���</a>&nbsp;</span></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#666666"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD align=left >

<TABLE WIDTH=673 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD rowspan=2 valign="top"><IMG SRC="images/monitor02_01.gif" WIDTH=88 HEIGHT=135></TD>
		<TD><IMG SRC="images/monitor02_02.gif" WIDTH=585 HEIGHT=87 ALT="���󥱡��Ȥ�������"></TD>
	</TR>
	<TR>
		<TD valign="top">

<!--��ɽ�Ȥߢ�-->
<form method="post" name="form1" action="m_thanks.php">
<TABLE WIDTH=585 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD>
			<IMG SRC="images/line_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD bgcolor="#EEEEEE" height="28"><SPAN class="t13">&nbsp;&nbsp;&nbsp;<B><?=htmlspecialchars($enquete->title)?></B></span></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/ten_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=10></TD>
	</TR>
	<TR>
		<TD align="center">

<TABLE WIDTH=100% BORDER=1 CELLPADDING=10 CELLSPACING=1 bordercolor="#DDDDDD">
  <TR>
    <TD bgColor=white colSpan=2><FONT size=2><?=nl2br(htmlspecialchars(str_replace('%POINT%', $enquete->point, $enquete->description)))?><BR></FONT></TD></TR>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_text != '') {
?>
  <TR>
    <TD bgColor=#EEEEEE colSpan=2>
			<FONT color=#003366 size=2><B>Q<?=$qno?>. <?=nl2br(htmlspecialchars($question->question_text))?></B></FONT>
<?
			if ($question->url != '') {
?>
			<br><a href="<?=$question->url?>" target="_blank"><?=htmlspecialchars($question->url)?></a>
<?
			}
			if ($question->image_id) {
?>
			<br><img src="img.php?id=<?=$question->image_id?>" alt="">
<?
			}
?>
		</TD>
	</TR>
  <TR>
    <TD vAlign=top width="9%" bgColor=white><FONT size=2>A<?=$qno?>.</FONT></TD>
    <TD width="91%" bgColor=white><FONT size=2>
<?
			switch ($question->question_type) {
			case 1:		// ñ������
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sno => $sel_text) {
						if ($sel_text != '') {
?>
			<?=$br?><nobr><input type="radio" name="answer<?=$qno?>" value="<?=$sno?>"> <?=htmlspecialchars($sel_text)?> </nobr>
<?
							$br = '<br>';
						}
					}
					if ($question->fa_flag == 't') {
?>
			<input type="text" name="free_answer<?=$qno?>" size=25>
<?
					}
				}
				break;
			case 2:		// ʣ������
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sno => $sel_text) {
						if ($sel_text != '') {
?>
			<?=$br?><nobr><input type="checkbox" name="answer<?=$qno?>[]" value="<?=$sno?>"> <?=htmlspecialchars($sel_text)?> </nobr>
<?
							$br = '<br>';
						}
					}
					if ($question->fa_flag == 't') {
?>
			<input type="text" name="free_answer<?=$qno?>" size=25>
<?
					}
				}
				break;
			case 3:		// ��ͳ����
?>
			<textarea name="answer<?=$qno?>" cols="50" rows="15"></textarea>
			<br><br>����ͳ�ˤ���������������
<?
				break;
			case 4:		// �ޥȥꥯ��(SA)
?>
			<table>
				<tr>
					<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td bgcolor="#ffddee"><font size="2"><?=$tno?>��<?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
				</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
				<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
					<td><font size="2"><?=$sno?>��<?=htmlspecialchars($hyousoku)?></font></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td align="center"><input type="radio" name="answer<?=$qno?>[<?=$sno?>]" value="<?="$sno:$tno"?>"></td>
<?
					}
?>
				</tr>
<?
				}
?>
		</table>
<?
				if ($question->dup_flag == 't') {
?>
								��Ʊ�������ǽ�ʣ�������򤹤뤳�ȤϤǤ��ޤ����
<?
				}
				break;
			case 5:		// �ޥȥꥯ��(MA)
?>
			<table>
				<tr>
					<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td bgcolor="#ffddee"><font size="2"><?=$tno?>��<?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
				</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
				<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
					<td><font size="2"><?=$sno?>��<?=htmlspecialchars($hyousoku)?></font></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td align="center"><input type="checkbox" name="answer<?=$qno?>[]" value="<?="$sno:$tno"?>"></td>
<?
					}
?>
				</tr>
<?
				}
?>
			</table>
<?
				if ($question->dup_flag == 't') {
?>
								��Ʊ�������ǽ�ʣ�������򤹤뤳�ȤϤǤ��ޤ����
<?
				}
				break;
			}
?>
			</FONT>
		</TD>
	</TR>
<?
		}
	}
}
?>
	<TR>
		<TD colspan="2"><INPUT type=submit value="�����������롡"> <INPUT type=reset value="�����ꥢ��"></TD>
	</TR>
</TABLE>

		</TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=10></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/ten_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/line_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=30></TD>
	</TR>
</TABLE>
<input type="hidden" name="enquete_id" <?=value($enquete_id)?>>
</form>
<!--��ɽ�Ȥߢ�-->

		</TD>
	</TR>
</TABLE>

	</TD>
	</TR>
	<TR>
	<TD><IMG SRC="images/footer_bar.gif" WIDTH=720 HEIGHT=2></TD>
	</TR>
	<TR>
	<TD align=right><IMG SRC="images/copyright.gif" WIDTH=349 HEIGHT=22 ALT="copyright"></TD>
	</TR>
	<TR>
	<TD align=right>
<SPAN class="copyright">���Υۡ���ڡ����˻Ȥ��Ƥ���������̿����������ϡ�����&nbsp;<a href="http://www.xxxxxxx.co.jp/" target="_blank">������ҡ�����������</a>&nbsp;�˵�°���ޤ���<BR>
̵�Ǥ�ʣ���������Ѥ��뤳�Ȥ�ˡΧ�ˤ��ؤ����Ƥ��ޤ���<BR><BR></SPAN>
	</TD>
	</TR>
	</TABLE>

</TD>
<TD background="images/bg_gray.gif"><IMG SRC="images/spacer.gif" WIDTH=40 HEIGHT=1></TD>
</TR>
</table>

</BODY>
</HTML>