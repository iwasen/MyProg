<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// 入力パラメータ取得
$monitor_id = $_SESSION['ss_monitor_id'];
?>
<HTML>
<HEAD>
<TITLE>お酒と買物のアンケートモニター｜現在実施中のアンケート一覧｜</TITLE>
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
		<TD><a href="m_logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_logout','','images/b_logout_over.gif',1)"><IMG SRC="images/b_logout.gif" WIDTH=90 HEIGHT=24 name=b_logout BORDER=0 ALT="ログアウト"></TD>
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
		<TD align="left"><span class="t10">現在実施中のアンケート一覧</span></TD>
		<TD align="right" WIDTH=400><span style="font-size:14px; font-weight:bold; color:#FF6600"><a href="m_change_add.php">［<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />メールアドレスの変更］</a>&nbsp;<a href="m_change_pw.php">［<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />パスワード変更］</a>&nbsp;</span></TD>
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
		<TD rowspan=2 valign="top"><IMG SRC="images/monitor01_01.gif" WIDTH=88 HEIGHT=135></TD>
		<TD><IMG SRC="images/monitor01_02.gif" WIDTH=585 HEIGHT=87></TD>
	</TR>
	<TR>
		<TD valign="top">

<!--▼表組み▼-->
<TABLE WIDTH=585 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD>
			<IMG SRC="images/monitor01_h_01.gif" WIDTH=13 HEIGHT=22></TD>
		<TD>
			<IMG SRC="images/monitor01_h_02.gif" WIDTH=493 HEIGHT=22></TD>
		<TD>
			<IMG SRC="images/monitor01_h_03.gif" WIDTH=62 HEIGHT=22></TD>
		<TD>
			<IMG SRC="images/monitor01_h_05.gif" WIDTH=17 HEIGHT=22></TD>
	</TR>
<?
// Myアンケート表示
$sql = "SELECT en_enquete_id,en_title,en_end_date,an_enquete_id"
		. " FROM t_enquete_list"
		. " JOIN t_enquete ON en_enquete_id=el_enquete_id"
		. " LEFT JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
		. " WHERE el_monitor_id=$monitor_id AND el_status=1 AND en_status=5"
		. " ORDER BY en_start_date DESC";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$enquete_id = $fetch->en_enquete_id;

	if ($fetch->an_enquete_id) {
		$title = htmlspecialchars($fetch->en_title);
		$end_date = '回答済み';
	} else {
		$title = "<a href='m_remlenq3a.php?enquete_id=$enquete_id'>" . htmlspecialchars($fetch->en_title) . '</a>';
		$end_date = format_date($fetch->en_end_date);
	}
?>
	<TR>
		<TD></TD>
		<TD height="28"><SPAN class="t12"><?=$title?></SPAN></TD>
		<TD height="28"><SPAN class="t12"><?=$end_date?></SPAN></TD>
		<TD></TD>
	</TR>
	<TR>
		<TD COLSPAN=4>
			<IMG SRC="images/ten_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
<?
}
?>
	<TR>
		<TD COLSPAN=4>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD COLSPAN=4>
			<IMG SRC="images/line_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD COLSPAN=4>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=50></TD>
	</TR>
</TABLE>
<!--▲表組み▲-->

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
<SPAN class="copyright">このホームページに使われている画像・写真・記事等は、全て&nbsp;<a href="http://www.xxxxxxx.co.jp/" target="_blank">株式会社○○○○○○</a>&nbsp;に帰属します。<BR>
無断で複製・再利用することは法律により禁じられています。<BR><BR></SPAN>
	</TD>
	</TR>
	</TABLE>

</TD>
<TD background="images/bg_gray.gif"><IMG SRC="images/spacer.gif" WIDTH=40 HEIGHT=1></TD>
</TR>
</table>

</BODY>
</HTML>
