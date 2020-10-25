<?
include('../inc/common.php');
include('../inc/shoukai.php');

session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$shoukai = new CShoukai;
?>
<HTML>

<HEAD>
<META HTTP-EQUIV="content-type" CONTENT="text/html;charset=EUC-JP">
<TITLE>MY友達紹介</TITLE>
<SCRIPT LANGUAGE="JavaScript">
<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0
	window.open(theURL,winName,features);
}
//-->
</SCRIPT>
<STYLE TYPE="TEXT/CSS">
<!--
.text9 {  font-size: 9pt; line-height: 125%}
-->
</STYLE>
</HEAD>

<BODY BGCOLOR="white" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" onLoad="">
<TABLE CELLSPACING="0" CELLPADDING="0" BORDER="0">
			<TR HEIGHT="45">
				<TD WIDTH="202" ROWSPAN="2" BGCOLOR="#2f2180" ALIGN="left" VALIGN="top">
					<DIV ALIGN="right">
						<IMG HEIGHT="111" WIDTH="202" SRC="../img/title_left.gif" ALT="はいめーる・net"></DIV>
				</TD>
				<TD HEIGHT="45" VALIGN="bottom" BGCOLOR="#2f2180" ALIGN="left" WIDTH="536">
					<TABLE BORDER="0" CELLSPACING="0" BGCOLOR="#2f2180" HEIGHT="43">
						<FORM NAME="login" ACTION="index.php" METHOD="post">
						<TR HEIGHT="27">
							<TD WIDTH="58" ALIGN="left" VALIGN="middle" HEIGHT="27"><IMG SRC="../img/mypage.gif" WIDTH="58" HEIGHT="22"></TD>
							<TD WIDTH="67" HEIGHT="27" ALIGN="right" VALIGN="middle"><IMG SRC="../img/mailaddress.gif" WIDTH="65" HEIGHT="12"></TD>
							<TD WIDTH="86" HEIGHT="27"><INPUT TYPE="text" NAME="mail_adr" SIZE="23"></TD>
							<TD HEIGHT="27" WIDTH="55" ALIGN="right" VALIGN="middle"><IMG SRC="../img/password.gif" WIDTH="49" HEIGHT="12"> </TD>
							<TD HEIGHT="27" WIDTH="61" ALIGN="left" VALIGN="middle"><INPUT TYPE="password" NAME="password" SIZE="10"></TD>
							<TD WIDTH="62" HEIGHT="27" ALIGN="center" VALIGN="middle"><INPUT TYPE="submit" VALUE="ログイン" NAME="login"></TD>
						</TR>
						<TR>
							<TD WIDTH="58"></TD>
							<TD WIDTH="67"></TD>
							<TD COLSPAN="3" ALIGN="right" VALIGN="middle"><A HREF="../9/9-1.php"><IMG SRC="../img/password_right.gif" WIDTH="185" HEIGHT="14" BORDER=0></A></TD>
							<TD WIDTH="62"></TD>
						</TR>
						</FORM>
					</TABLE></TD>
			</TR>
			<TR>
				<TD BGCOLOR="#5d85b8" ALIGN="left" VALIGN="bottom" WIDTH="536"><IMG SRC="../img/head.jpg" WIDTH="535" HEIGHT="66" ALT="interactive communication network"></TD>
			</TR>
			<TR HEIGHT="18">

		<TD COLSPAN="2" BGCOLOR="#3f659e" HEIGHT="18" ALIGN="left" VALIGN="middle"><img height="16" width="14" src="../img/space.gif"><a href="../index.php"><img src="../img/home.gif" width="53" height="16" alt="HOME" border="0"></a><a href="../2/index.html"><img src="../img/himail_net.gif" width="130" height="16" border="0" alt="はいめーる・netって？"></a><a href="../movie/index.html"><img src="../img/tsushin2.gif" width="119" height="16" alt="はいめーる映画通信" border="0"></a><a href="../3/index.html"><img src="../img/promise.gif" width="44" height="16" border="0" alt="お約束"></a><a href="../4/index.html"><img src="../img/policy.gif" width="119" height="16" border="0" alt="プライバシーポリシー"></a><a href="../5/index.html"><img src="../img/faq.gif" width="42" height="16" border="0" alt="FAQ"></a><a href="../6/index.php"><img src="../img/use_business.gif" width="108" height="16" border="0" alt="ビジネスで利用する"></a><a href="../7/index.html"><img src="../img/use_personal.gif" width="88" height="16" border="0" alt="個人で利用する"></a></TD>
			</TR>
		</TABLE><BR>
		<BR>
		<TABLE WIDTH="604" BORDER="0" CELLSPACING="0" MM_NOCONVERT="TRUE" CELLPADDING="0">
			<FORM NAME="FormName" ACTION="8b-2_check.php" METHOD="post">
			<TR HEIGHT="36">
				<TD WIDTH="50" HEIGHT="36" ALIGN="right" VALIGN="top"><IMG HEIGHT="16" WIDTH="50" SRC="../img/space.gif"></TD>
				<TD WIDTH="561" HEIGHT="36" VALIGN="top">
					<DIV ALIGN="left">
						<B><IMG HEIGHT="26" WIDTH="435" SRC="img/otomo_title.gif" ALT="お友達紹介"><BR>
						</B></DIV>
				</TD>
			</TR>
			<TR HEIGHT="36">
				<TD WIDTH="50" HEIGHT="36" ALIGN="right" VALIGN="top">　</TD>

			<TD WIDTH="561" HEIGHT="36" CLASS="text9">
					<BR>
					<SPAN CLASS="text9">あなたに紹介していただいたお友達が、はいめーる・netに登録するたびに <BR>
					1人につき『100ポイント』が加算されます。どうぞこの機会にたくさんの <BR>
					お友達をご紹介ください。 </SPAN></FONT></P>
				<P><SPAN CLASS="text9"><FONT SIZE="2"> 紹介メールには『あなたのお名前またはニックネーム』と『お友達のメールアドレス』、
					　<BR>
					『メッセージ』に「はいめーる・net」の登録フォームがつきます。 <BR>
					あなたのメッセージ内容はこんな感じで届けられます。 <BR>
					<BR>
					<A HREF="#" onMouseDown="MM_openBrWindow('8b-2a.html','','scrollbars=yes,resizable=yes,width=475,height=300')"><IMG NAME="Image19" BORDER="0" SRC="img/botton.gif" WIDTH="292" HEIGHT="22"></A>
					<BR>
					<BR>
					</FONT>あなたのご紹介によって、以下のポイントが入ります。 　</SPAN></P>
				<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
					<TR>
						<TD><IMG SRC="img/arrow.gif" WIDTH="36" HEIGHT="21"></TD>
						<TD ROWSPAN="2"><IMG SRC="img/tomo.gif" WIDTH="514" HEIGHT="42" ALT="紹介によって、あなたとお友達にそれぞれ100ポイントプレゼント"></TD>
					</TR>
					<TR>
						<TD><IMG SRC="img/arrow.gif" WIDTH="36" HEIGHT="21"></TD>
					</TR>
				</TABLE>
				<P><FONT SIZE="2"><SPAN CLASS="text9">例えば、3人のお友達に紹介しそのうちの2人がメンバー登録すると、<BR>
					あなたに200ポイント、お友達にはお1人につき100ポイント加算されます。<BR>
					もちろんお友達にポイントが入るからといって、あなたのポイントが減るわけ<BR>
					ではありませんので、ご安心ください。</SPAN></FONT> </P>
				<HR>
				</TD>
			</TR>
			<TR HEIGHT="36">
				<TD WIDTH="50" HEIGHT="36" ALIGN="right" VALIGN="top">　</TD>
				<TD WIDTH="561" HEIGHT="36"><FONT SIZE="2">
				<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="2" WIDTH="504">
					<TR>
						<TD ALIGN="left" VALIGN="top"><FONT SIZE="2" COLOR="#b2cce4">■</FONT><FONT SIZE="2">あなたのお名前または<BR>
							　ニックネーム<?ErrMark($shoukai->err_nickname)?></FONT></TD>
						<TD WIDTH="267" ALIGN="left" VALIGN="top">
							<INPUT TYPE="text" NAME="nickname" SIZE="37" MAXLENGTH=50 VALUE="<?=htmlspecialchars($shoukai->nickname)?>">
							<BR>
							<FONT SIZE="1">お友達にメッセージを送る際に表示されます。 <BR>
							</FONT><FONT SIZE="2"><BR>
							</FONT></TD>
					</TR>
					<TR>
						<TD ALIGN="left" VALIGN="top"><FONT SIZE="2" COLOR="#b2cce4">■</FONT><FONT SIZE="2">お友達のメールアドレス<?ErrMark($shoukai->err_tomodachi_adr)?><BR>
							　（何人でも送れます） </FONT><FONT SIZE="2"><BR>
							　※1行につき1つのメールアドレスを<BR>
							　　半角英数で入力してください。</FONT></TD>
						<TD WIDTH="267" ALIGN="left" VALIGN="top">
							<TEXTAREA NAME="tomodachi_adr" COLS="35" ROWS="4"><?=htmlspecialchars($shoukai->tomodachi_adr)?></TEXTAREA>
							<BR>
						</TD>
					</TR>
					<TR>
						<TD COLSPAN="2"><FONT SIZE="2" COLOR="#b2cce4"><BR>
							■</FONT><FONT SIZE="2">メッセージ（全角200文字以内）<?ErrMark($shoukai->err_message)?><BR>
							</FONT></TD>
					</TR>
					<TR>
						<TD COLSPAN="2">
							<TEXTAREA NAME="message" ROWS="5" COLS="60"><?=htmlspecialchars($shoukai->message)?></TEXTAREA>
							<BR>
						</TD>
					</TR>
					<TR>
						<TD COLSPAN="2" ALIGN="CENTER">
							<INPUT TYPE="submit" NAME="Submit" VALUE="　申し込む　">
						</TD>
					</TR>
				</TABLE>
				</FONT></TD>
			</TR>
			<TR HEIGHT="36">
				<TD WIDTH="50" HEIGHT="36">　</TD>
				<TD WIDTH="561" HEIGHT="36"><FONT SIZE="2">
					<HR>
					</FONT><A HREF="index.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/mypage.gif" ALT="my pageに戻る" BORDER="0"></A><BR>
					<BR>
				</TD>
			</TR>
			</FORM>
		</TABLE>
	</BODY>

</HTML>
