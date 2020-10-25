<?
/******************************************************
' System :きかせて・netモニターページ
' Content:左メニュー
'******************************************************/
include("$top/../inc/common.php");
include("$top/../inc/database.php");
include("$top/../inc/count.php");
include("$top/../inc/format.php");
header('Content-Type: text/html; charset=EUC-JP');
function monitor_menu($body_option='') {global $top; global $img;
?>
<html lang="ja">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>きかせて・net</title>
<LINK href="<?=$top?>/monitor.css" rel=stylesheet>
<SCRIPT src="<?=$top?>/monitor.js"></SCRIPT>
</head>
<body bgColor=#ffffff leftMargin=0 topMargin=0 onload="MM_preloadImages('<?=$img?>/main_hajimete_off.gif','<?=$img?>/main_enq_off.gif','<?=$img?>/main_point_off.gif','<?=$img?>/main_hajimete_on.gif','<?=$img?>/main_enq_on.gif','<?=$img?>/main_point_on.gif','<?=$img?>/body_monitor_on.gif','<?=$img?>/right_monitor_on.gif','<?=$img?>/right_ssl_on.gif','<?=$img?>/right_nossl_on.gif','<?=$img?>/right_faq_on.gif','<?=$img?>/right_kiyaku_on.gif','<?=$img?>/right_privacy_on.gif')" <?=$body_option?>>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
		<TR>
			<TD class=leftmenu2 vAlign=top width=160>
				<TABLE cellSpacing=0 cellPadding=0 width=160 border=0>
					<TBODY>
						<TR>
							<TD><IMG height=30 src="<?=$img?>/right_menu.gif" width=160></TD>
						</TR>
						<form method="post" action="<?=$top?>/mypage/login.php" target="_top" name="login_form">
						<TR>
							<TD class=leftmenu1>
								<BR><IMG height=14 src="<?=$img?>/right_email.gif" width=98 vspace=2>
								<BR><input type="text" name="mail_addr" size="25" maxlength="100" value="<?=$_COOKIE['ck_monitor_mail_addr']?>" tabindex=1>
								<BR><IMG height=13 src="<?=$img?>/right_pass.gif" width=77 vspace=2>
								<BR><input type="password" name="password" size="25" maxlength="20" tabindex=2>
								<BR><input type="image" src="<?=$img?>/right_ssl_off.gif" height=33 width=76 vspace=10 border=0 name=Image4 onmouseover="this.src='<?=$img?>/right_ssl_on.gif'" onmouseout="this.src='<?=$img?>/right_ssl_off.gif'" tabindex=4 onclick="document.login_form.ssl.value='1'">
									<input type="image" src="<?=$img?>/right_nossl_off.gif" height=33 width=76 vspace=10 border=0 name=Image4 onmouseover="this.src='<?=$img?>/right_nossl_on.gif'" onmouseout="this.src='<?=$img?>/right_nossl_off.gif'" tabindex=3 onclick="document.login_form.ssl.value='0'">
									<input type="hidden" name="ssl">
								<IMG height=11 src="<?=$img?>/blue_arrow1.gif" width=11 align=absMiddle vspace=5>
								<A class=menu1 href="<?=$top?>/guide/pwrequest.php">パスワードを忘れた方</A>
							</TD>
						</TR>
						</form>
						<TR>
							<TD align=middle>
								<A  onmouseover="MM_swapImage('Image7','','<?=$img?>/right_monitor_on.gif',1)"  onmouseout=MM_swapImgRestore() href="<?=$top?>/regist/regist0.php"><IMG  height=33 src="<?=$img?>/right_monitor_off.gif" width=152 vspace=15 border=0 name=Image7></A><BR>
								<TABLE cellSpacing=0 cellPadding=0 width=149 border=0>
										<TR>
											<TD colSpan=3><IMG height=21 src="<?=$img?>/right_count.gif" width=149></TD>
										</TR>
										<TR>
											<TD width=3 background=<?=$img?>/right_count_l.gif height=30><IMG height=1 src="<?=$img?>/spacer.gif" width=3></TD>
											<TD class=count align=middle width=149 bgColor=#fafafa><?=number_format(count_monitor())?>人</TD>
											<TD width=3 background=<?=$img?>/right_count_r.gif><IMG height=1 src="<?=$img?>/spacer.gif" width=3></TD>
										</TR>
										<TR>
											<TD colSpan=3><IMG height=3 src="<?=$img?>/right_count_under.gif"  width=149></TD>
										</TR>
								</TABLE>
							</TD>
						</TR>
						<TR>
							<TD><A onmouseover="MM_swapImage('Image13','','<?=$img?>/right_kiyaku_on.gif',1)" onmouseout=MM_swapImgRestore() href="<?=$top?>/guide/rules.php"><IMG height=13 hspace=2 src="<?=$img?>/right_kiyaku_off.gif" width=82 vspace=3 border=0 name=Image13></A>
							<BR><A onmouseover="MM_swapImage('Image14','','<?=$img?>/right_privacy_on.gif',1)" onmouseout=MM_swapImgRestore() href="<?=$top?>/guide/privacy.php"><IMG height=15 hspace=2 src="<?=$img?>/right_privacy_off.gif" width=131 vspace=3 border=0 name=Image14></A>
							<BR><A onmouseover="MM_swapImage('Image12','','<?=$img?>/right_faq_on.gif',1)" onmouseout=MM_swapImgRestore() href="<?=$top?>/guide/faq.php"><IMG height=12 hspace=2 src="<?=$img?>/right_faq_off.gif" vspace=5 width=48 space=3 border=0 name=Image12></A>
							</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD vAlign=top align=middle>
<?
}
?>