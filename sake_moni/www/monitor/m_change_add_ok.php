<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// 入力パラメータ取得
$change_add = $_SESSION['change_add'];
$payment_type = $change_add['payment_type'];
$zip = $change_add['zip'];
$area = $change_add['area'];
$address = $change_add['address'];
$name1 = $change_add['name1'];
$name2 = $change_add['name2'];
$mail_addr = $change_add['mail_addr'];
$account_num = $change_add['account_num'];
$net_kind = $change_add['net_kind'];
$monitor_id = $_SESSION['ss_monitor_id'];

// モニター情報更新
/*if ($net_kind == 2) {
	$rec['mn_payment_type'] = sql_number($payment_type);
	$rec['mn_jitaku_zip'] = sql_char($payment_type == 2 ? $zip : '');
	$rec['mn_jitaku_area'] = sql_number($payment_type == 2 ? $area : '');
	$rec['mn_jitaku_addr2'] = sql_char($payment_type == 2 ? $address : '');
	$rec['mn_name1'] = sql_char($name1);
	$rec['mn_name2'] = sql_char($name2);
	$rec['mn_account_num'] = sql_char($payment_type == 3 ? $account_num : '');
}*/
$rec['mn_mail_addr'] = sql_char($mail_addr);
$rec['mn_mail_addr_low'] = sql_char(strtolower($mail_addr));
db_update('t_monitor', $rec, "mn_monitor_id=$monitor_id");
?>
<HTML>
<HEAD>
<TITLE>お酒と買物のアンケートモニター｜登録情報の変更｜</TITLE>
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
		<TD align="left"><span class="t10"><a href="m_index.php">現在実施中のアンケート一覧</a>&nbsp;＞&nbsp;メールアドレスの変更</span></TD>
		<TD align="right" WIDTH=400><span style="font-size:14px; font-weight:bold; color:#FF6600"><a href="m_change_add.php">［<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />メールアドレスの変更］</a>&nbsp;<a href="m_change_pw.php">［<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />パスワード変更］</a>&nbsp;</span></TD>
		</TR></TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#666666"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD align=center height=320>

<!--▼登録情報の変更フォーム▼-->
<form>
	<TABLE WIDTH=490 BORDER=0 CELLPADDING=1 CELLSPACING=4 bgcolor=#DDDDDD>
		<TR>
		<TD bgcolor=#FFFFFF>

			<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=1 bgcolor=#99CCFF>
			<TR>
			<TD>

				<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 bgcolor=#FFFFFF>
				<TR>
				<TD align="center" valign="center">

					<TABLE WIDTH=433 BORDER=0 CELLPADDING=0 CELLSPACING=7>
					<TR>
					<TD><IMG SRC="images/spacer.gif" WIDTH=102 HEIGHT=30></TD>
					<TD><IMG SRC="images/spacer.gif" WIDTH=310 HEIGHT=30></TD>
					</TR>
					<TR>
					<TD colspan=2 align="center"><span class="t12">登録情報を変更しました。</span></TD>
					</TR>
					<TR>
					<TD colspan=2><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=30></TD>
					</TR>
					</TABLE>

				</TD>
				</TR>
				</TABLE>

			</TD>
			</TR>
			</TABLE>

		</TD>
		</TR>
	</TABLE>
</form>
<!--▲登録情報の変更フォーム▲-->

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
