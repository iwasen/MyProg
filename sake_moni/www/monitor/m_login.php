<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
include("$inc/encrypt.php");
include("$inc/login_log.php");

// 入力パラメータ取得
$mail_addr = get_mail_addr($_POST['mail_addr']);
$password = get_password($_POST['password']);
$enquete_id = get_password($_POST['enquete_id']);
$id = $_GET['id'];

if ($mail_addr != '') {
	if ($password == '')
		$err_msg = 'パスワードが入力されていないようです';
	elseif (!check_mail_addr($mail_addr))
		$err_msg = 'メールアドレスの形式が正しくないようです';
	else {
		$sql = sprintf("SELECT mn_monitor_id FROM t_monitor WHERE mn_mail_addr_low=%s AND mn_password=%s AND mn_status<>9",
				sql_char(strtolower($mail_addr)),
				sql_char($password));
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			login_log(1, $mail_addr, $password, $fetch->mn_monitor_id);

			session_start();
			$_SESSION['ss_monitor_id'] = $fetch->mn_monitor_id;

			if ($enquete_id)
				redirect("m_remlenq3a.php?enquete_id=$enquete_id");
			else
				redirect('m_index.php');
		} else
			$err_msg = 'メールアドレスまたはパスワードが正しくないようです';
	}
} elseif (strlen($id) == 14) {
	decrypt_param($id, $enquete_id, $monitor_id);
	if ($monitor_id != 0) {
		$sql = "SELECT mn_mail_addr FROM t_monitor WHERE mn_monitor_id=$monitor_id AND mn_status<>9";
		$mail_addr = db_fetch1($sql);
	} else
		$enquete_id = '';
}

login_log(1, $mail_addr, $password, null);
?>
<HTML>
<HEAD>
<TITLE>お酒と買物のアンケートモニター｜ログイン画面｜</TITLE>
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
	<TD><IMG SRC="images/monitor00_title.gif" WIDTH=720 HEIGHT=106 ALT="お酒と買物のアンケートモニター"></TD>
	</TR>
	<TR>
	<TD>
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td align="right"><span class="t12"><a href="faq.php">≫よくある質問はこちらから</a></span></td>
		</tr>
		</table>
		
	</TD>
	</TR>
	<TR>
	<TD align=center height=320>

<!--▼ログインフォーム▼-->
<form method="post" name="form1">
	<TABLE WIDTH=440 BORDER=0 CELLPADDING=1 CELLSPACING=4 bgcolor=#DDDDDD>
		<TR>
		<TD bgcolor=#FFFFFF>

			<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=1 bgcolor=#99CCFF>
			<TR>
			<TD>

				<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 bgcolor=#FFFFFF>
				<TR>
				<TD align="center" valign="center">

					<TABLE WIDTH=383 BORDER=0 CELLPADDING=0 CELLSPACING=7>
					<TR>
					<TD><IMG SRC="images/spacer.gif" WIDTH=102 HEIGHT=20></TD>
					<TD><IMG SRC="images/spacer.gif" WIDTH=260 HEIGHT=20></TD>
					</TR>
					<TR>
					<TD><IMG SRC="images/monitor00_05.gif" WIDTH=102 HEIGHT=22 ALT="メールアドレス"></TD>
					<TD><INPUT size=40 name="mail_addr" <?=value($mail_addr)?>></TD>
					</TR>
					<TR>
					<TD><IMG SRC="images/monitor00_08.gif" WIDTH=102 HEIGHT=22 ALT="パスワード"></TD>
					<TD><INPUT size=30 name="password" type="password"></TD>
					</TR>
<?
if ($err_msg != '') {
?>
					<TR>
					<TD></TD>
					<TD><span class="err">＜<?=$err_msg?>＞</span></TD>
					</TR>
<?
}
?>
					<TR>
					<TD></TD>
					<TD><span class="t10"><a href="m_pw.php">≫パスワード忘れの方はこちら</a></span></TD>
					</TR>
					<TR>
					<TD></TD>
					<TD align="left"><span onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_ssl0','','images/b_ssl0_over.gif',1)"><input type="image" SRC="images/b_ssl0.gif" WIDTH=130 HEIGHT=36 BORDER=0 ALT="非ＳＳＬでログイン" name=b_ssl0 onclick="document.form1.action='<?=http_url('m_login.php')?>'"></span><span onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_ssl1','','images/b_ssl1_over.gif',1)"><input type="image" SRC="images/b_ssl1.gif" WIDTH=130 HEIGHT=36 BORDER=0 ALT="ＳＳＬでログイン" name=b_ssl1 onclick="document.form1.action='<?=https_url('m_login.php')?>'"></span></TD>
					</TR>
					<TR>
					<TD colspan=2><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=10></TD>
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
	<input type="hidden" name="enquete_id" <?=value($enquete_id)?>>
</form>
<!--▲ログインフォーム▲-->

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