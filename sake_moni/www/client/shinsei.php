<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mypage.php");

define('REPORT_DAYS', 10);		// レポートＵＰまでの営業日数

// 月日のチェック
function check_month_day($month, $day) {
	if (!check_num($month, 1, 12))
		return false;

	$year = date('Y');
	if ($month < date('n'))
		$year++;

	return checkdate($month, $day, $year);
}

// 翌営業日取得
function next_working_day($time) {
	for (;;) {
		$time += 24 * 60 * 60;
		$date = getdate($time);
		if ($date['wday'] != 6) {
			// 休日マスタ取得
			$sql = "SELECT COUNT(*) FROM m_holiday WHERE hd_holiday='{$date['year']}/{$date['mon']}/{$date['mday']}'";
			if (db_fetch1($sql) == 0) {
				if ($date['wday'] != 0)
					break;
			} else {
				// 振り替え休日
				if ($date['wday'] == 0)
					$time += 24 * 60 * 60;
			}
		}
	}

	return $time;
}

// 入力パラメータ取得
$start_month = $_POST['start_month'];
$start_day = $_POST['start_day'];
$report_month = $_POST['report_month'];
$report_day = $_POST['report_day'];
$chousahyou = $_POST['chousahyou'];
$client_id = $_SESSION['ss_client_id'];

if ($_POST['send'] != '') {
	if ($start_month == '' || $start_day == '')
		$err_start_date = '実査開始希望日が入力されていないようです';
	elseif (!check_month_day($start_month, $start_day))
		$err_start_date = '実査開始希望日が正しい日付ではないようです';

	if ($report_month == '' || $report_day == '')
		$err_report_date = 'レポートＵＰ日が入力されていないようです';
	elseif (!check_month_day($report_month, $report_day))
		$err_report_date = 'レポートＵＰ日が正しい日付ではないようです';

	if ($chousahyou == '')
		$err_chousahyou = '調査票が入力されていないようです';

	if ($toiawasesaki == '')
		$err_toiawasesaki = 'お問合せ先が入力されていないようです';

	if ($seikyusho == '')
		$err_seikyusho = '請求書発行先が入力されていないようです';

	if ($err_start_date == '' && $err_report_date == '' && $err_chousahyou) {
		// クライアントの名前とメールアドレスを取得
		$sql = "SELECT cl_mail_addr,cl_name1 FROM m_client WHERE cl_client_id=$client_id";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			// メールテンプレート取得
			get_mail_template('cl_up', $subject, $to, $cc, $bcc, $repty_to, $body);

			// 差込変数処理
			$body = str_replace('%CLIENT_NAME%', $fetch->cl_name1, $body);
			$body = str_replace('%CLIENT_MAIL_ADDR%', $fetch->cl_mail_addr, $body);
			$body = str_replace('%START_MONTH%', $start_month, $body);
			$body = str_replace('%START_DAY%', $start_day, $body);
			$body = str_replace('%REPORT_MONTH%', $report_month, $body);
			$body = str_replace('%REPORT_DAY%', $report_day, $body);
			$body = str_replace('%TOIAWASESAKI%', $toiawasesaki, $body);
			$body = str_replace('%SEIKYUSHO%', $seikyusho, $body);

			// Cc:, Bcc:
			$mail_header = '';
			if ($cc != '')
				$mail_header .= "Cc: $cc\n";
			if ($bcc != '')
				$mail_header .= "Bcc: $bcc\n";

			// メール送信
			db_begin_trans();
			$send_mail_id = send_mail_data($subject, $fetch->cl_mail_addr, '', $body, '', '', '');
			send_mail_file($send_mail_id, 1, $_FILES['chousahyou']['name'], file_get_contents($_FILES['chousahyou']['tmp_name']));
			send_mail_list($send_mail_id, $to, 1, $mail_header);
			send_mail_ok($send_mail_id);
			db_commit_trans();

			redirect('shinsei_thanks.php');
		}
	}
} elseif ($_POST['calc'] != '') {
	if ($start_month == '' || $start_day == '')
		$err_start_date = '実査開始希望日が入力されていないようです';
	elseif (!check_month_day($start_month, $start_day))
		$err_start_date = '実査開始希望日が正しい日付ではないようです';
	else {
		$year = date('Y');
		if ($start_month < date('n'))
			$year++;
		$date = mktime(0, 0, 0, $start_month, $start_day, $year);

		for ($i = 0; $i < REPORT_DAYS; $i++)
			$date = next_working_day($date);

		$report_month = date('n', $date);
		$report_day = date('j', $date);
	}
}
?>
<HTML>
<HEAD>
<TITLE>お酒と買物のアンケートモニター｜調査の申請｜</TITLE>
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
		<TD><span class="t10"><a href="c_index.php">ＴＯＰ</a>&nbsp;＞&nbsp;調査の申請</span></TD>
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
<form method="post" name="form1" action="shinsei.php" enctype="multipart/form-data">
<TABLE WIDTH=450 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<form>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=400 HEIGHT=30></TD>
	</TR>
	<TR>
		<TD><SPAN class="t14">■<B>調査の申請</B></SPAN></TD>
	</TR>
	<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=10 HEIGHT=5></TD>
	</TR>
	<TR>
		<TD>

<TABLE WIDTH=100% BORDER=1 CELLPADDING=10 CELLSPACING=1 bordercolor="#DDDDDD">
	<TR>
		<TD><SPAN class="t12">実査開始希望日</SPAN></TD>
		<TD><SPAN class="t12"><INPUT size=3 maxlength=2 name="start_month" <?=value($start_month)?>>&nbsp;月&nbsp;&nbsp;<INPUT size=3 maxlength=2 name="start_day" <?=value($start_day)?>>&nbsp;日&nbsp;<INPUT type=submit name="calc" value="計算"></SPAN>
<?
if ($err_start_date != '') {
?>
	<br><span class="err">＜<?=$err_start_date?>＞</span>
<?
}
?>
	</TD>
	</TR>
	<TR>
		<TD><SPAN class="t12">レポートＵＰ日</SPAN></TD>
		<TD><SPAN class="t12"><INPUT size=3 maxlength=2 name="report_month" <?=value($report_month)?>>&nbsp;月&nbsp;&nbsp;<INPUT size=3 maxlength=2 name="report_day" <?=value($report_day)?>>&nbsp;日</SPAN>
<?
if ($err_report_date != '') {
?>
	<br><span class="err">＜<?=$err_report_date?>＞</span>
<?
}
?>
	</TD>
	</TR>
	<TR>
		<TD><SPAN class="t12">調査票</SPAN></TD>
		<TD><SPAN class="t12"><INPUT type="file" size=40 name="chousahyou"></SPAN>
<?
if ($err_chousahyou != '') {
?>
	<br><span class="err">＜<?=$err_chousahyou?>＞</span>
<?
}
?>
	</TD>
	</TR>
	<TR>
		<TD><SPAN class="t12">お問合せ先</SPAN></TD>
		<TD><SPAN class="t12"><INPUT size=50 name="toiawasesaki" <?=value($toiawasesaki)?>></SPAN>
<?
if ($err_toiawasesaki != '') {
?>
	<br><span class="err">＜<?=$err_toiawasesaki?>＞</span>
<?
}
?>
	</TD>
	</TR>
	<TR>
		<TD><SPAN class="t12">請求書発行先</SPAN></TD>
		<TD><SPAN class="t12"><INPUT size=50 name="seikyusho" <?=value($seikyusho)?>></SPAN>
<?
if ($err_seikyusho != '') {
?>
	<br><span class="err">＜<?=$err_seikyusho?>＞</span>
<?
}
?>
	</TD>
	</TR>
	<TR>
		<TD colspan="2"><INPUT type=submit name="send" value="　送信する　"> <INPUT type=reset value="　クリア　"></TD>
	</TR>
</TABLE>

		</TD>
	</TR>
	<TR>
		<TD COLSPAN=8>
			<IMG SRC="images/spacer.gif" WIDTH=400 HEIGHT=40></TD>
	</TR>
</form>
</TABLE>
</form>
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