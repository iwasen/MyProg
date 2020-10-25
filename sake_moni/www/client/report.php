<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// 入力パラメータ
$chain_cd = $_GET['chain_cd'];
$gyoutai_cd  = $_GET['gyoutai_cd'];

// チェーン名検索処理
if (isset($_GET['chain_search_text'])) {
	$chain_search_text  = $_GET['chain_search_text'];
	if ($chain_search_text == '')
		redirect("c_index.php?search_err=1");

	$sql = "SELECT ch_chain_cd"
		. " FROM m_chain"
		. " JOIN m_client_chain ON st_client_id={$_SESSION['ss_client_id']} AND st_chain_cd=ch_chain_cd"
		. " WHERE ch_name LIKE '{$chain_search_text}%'";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow == 0)
		redirect("c_index.php?search_err=2&chain_search_text=" . urlencode($chain_search_text));
	else if ($nrow > 1)
		redirect("c_index.php?search_err=3&chain_search_text=" . urlencode($chain_search_text));

	$fetch = pg_fetch_object($result, 0);
	$chain_cd = $fetch->ch_chain_cd;
}

// チェーン名または業態名取得
if ($chain_cd) {
	$sql = "SELECT ch_name FROM m_chain WHERE ch_chain_cd=$chain_cd";
	$name = db_fetch1($sql);
	$kind = 'チェーン';
} else if ($gyoutai_cd) {
	$sql = "SELECT gt_gyoutai_name FROM m_gyoutai WHERE gt_gyoutai_cd=$gyoutai_cd";
	$name = db_fetch1($sql);
	$kind = '業態';
	//↓一時的に追加。GSMとSMをまとめる。20061222
	if($name == "GMS")
		$name = "GMS・SM";
	//↑END 20061222
} else
	redirect("c_index.php");
?>
<HTML>
<HEAD>
<TITLE>お酒と買物のアンケートモニター｜チェーン別結果｜</TITLE>
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
	<TD background="images/marketer_title2.gif">

		<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
		<TD ROWSPAN=3>
			<IMG SRC="images/c_header_01.gif" WIDTH=618 HEIGHT=106></TD>
		<TD>
			<IMG SRC="images/c_header_02.gif" WIDTH=90 HEIGHT=75></TD>
		<TD>
			<IMG SRC="images/c_header_03.gif" WIDTH=12 HEIGHT=75></TD>
		</TR>
		<TR>
		<TD><a href="c_logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_logout','','images/b_logout_over.gif',1)"><IMG SRC="images/b_logout.gif" WIDTH=90 HEIGHT=24 name=b_logout BORDER=0 ALT="ログアウト"></TD>
		<TD>
			<IMG SRC="images/c_header_05.gif" WIDTH=12 HEIGHT=24></TD>
		</TR>
		<TR>
		<TD>
			<IMG SRC="images/c_header_06.gif" WIDTH=90 HEIGHT=7></TD>
		<TD>
			<IMG SRC="images/c_header_07.gif" WIDTH=12 HEIGHT=7></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#FFFFFF"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD bgcolor="#FFCC66" height=18>

		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=25 HEIGHT=18></TD>
		<TD><span class="t10"><a href="c_index.php">ＴＯＰ</a>&nbsp;＞&nbsp;<?=$kind?>別結果</span></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#666666"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD align=center>

<!--▼表組み▼-->
<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD COLSPAN=8>
			<IMG SRC="images/spacer.gif" WIDTH=590 HEIGHT=30></TD>
	</TR>
	<TR>
		<TD COLSPAN=8><SPAN class="t14">■<B><?=$kind?>別結果</B></SPAN></TD>
	</TR>
	<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=10 HEIGHT=5></TD>
		<TD><IMG SRC="images/spacer.gif" WIDTH=40 HEIGHT=5></TD>
		<TD><IMG SRC="images/spacer.gif" WIDTH=220 HEIGHT=5></TD>
		<TD><IMG SRC="images/spacer.gif" WIDTH=130 HEIGHT=5></TD>
		<TD><IMG SRC="images/spacer.gif" WIDTH=55 HEIGHT=5></TD>
		<TD><IMG SRC="images/spacer.gif" WIDTH=70 HEIGHT=5></TD>
		<TD><IMG SRC="images/spacer.gif" WIDTH=55 HEIGHT=5></TD>
		<TD><IMG SRC="images/spacer.gif" WIDTH=10 HEIGHT=5></TD>
	</TR>
	<TR>
		<TD COLSPAN=8 bgcolor="FFCC66">
			<IMG SRC="images/spacer.gif" WIDTH=590 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD COLSPAN=8>
			<IMG SRC="images/spacer.gif" WIDTH=590 HEIGHT=2></TD>
	</TR>

	<TR>
		<TD bgcolor="#EEEEEE"></TD>
		<TD height="25"colspan="7" bgcolor="#EEEEEE"><SPAN class="t12"><B>【　<?=htmlspecialchars($name)?>　】&nbsp;調査結果</B></SPAN></TD>
	</TR>
	<TR>
		<TD COLSPAN=8>
			<IMG SRC="images/spacer.gif" WIDTH=590 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD COLSPAN=8>
			<IMG SRC="images/ten_ore_590.gif" WIDTH=590 HEIGHT=1></TD>
	</TR>

<?
if ($chain_cd) {
	$sql = "SELECT rp_report_id,rp_chain_cd,rp_title,rp_from_date,rp_to_date"
			. " FROM t_report"
			. " WHERE rp_chain_cd=$chain_cd"
			. " ORDER BY rp_report_id DESC";
} else {
	$sql = "SELECT rp_report_id,rp_chain_cd,rp_title,rp_from_date,rp_to_date"
			. " FROM t_report"
			. " WHERE rp_gyoutai_cd=$gyoutai_cd"
			. " ORDER BY rp_report_id DESC";
}
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$report_id = $fetch->rp_report_id;
?>
	<TR>
		<TD></TD>
		<TD height="28"><SPAN class="t12">NO.<?=$nrow - $i?></SPAN></TD>
		<TD height="28"><SPAN class="t12"><?=htmlspecialchars($fetch->rp_title)?></SPAN></TD>
		<TD height="28"><SPAN class="t12"><?=format_date($fetch->rp_from_date)?>〜<?=substr(format_date($fetch->rp_to_date), 5)?></SPAN></TD>
		<TD height="28"><SPAN class="t12"><a href="download.php?kind=r&report_id=<?=$report_id?>">レポート</a></SPAN></TD>
		<TD height="28"><SPAN class="t12"><a href="download.php?kind=s&report_id=<?=$report_id?>">集計結果</a></SPAN></TD>
		<TD height="28"><SPAN class="t12"><a href="download.php?kind=e&report_id=<?=$report_id?>">調査票</a></SPAN></TD>
	</TR>
	<TR>
		<TD COLSPAN=8>
			<IMG SRC="images/ten_ore_590.gif" WIDTH=590 HEIGHT=1></TD>
	</TR>
<?
}
?>

	<TR>
		<TD COLSPAN=8>
			<IMG SRC="images/spacer.gif" WIDTH=590 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD COLSPAN=8 bgcolor="FFCC66">
			<IMG SRC="images/spacer.gif" WIDTH=590 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD COLSPAN=8>
			<IMG SRC="images/spacer.gif" WIDTH=590 HEIGHT=5></TD>
	</TR>
	<TR>
		<TD COLSPAN=8 align="right">
<a href="shinsei.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_shinsei','','images/b_shinsei_over.gif',1)"><IMG SRC="images/b_shinsei.gif" WIDTH=130 HEIGHT=36 BORDER=0 ALT="調査を申請する" name=b_shinsei></a></TD>
	</TR>
	<TR>
		<TD COLSPAN=8>
			<IMG SRC="images/spacer.gif" WIDTH=590 HEIGHT=30></TD>
	</TR>
</TABLE>
<!--▲表組み▲-->


	</TD>
	</TR>
	<TR>
	<TD><IMG SRC="images/footer_bar_ore.gif" WIDTH=720 HEIGHT=2></TD>
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
