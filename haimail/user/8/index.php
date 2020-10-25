<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');
include('../inc/point.php');
include('../inc/header.php');

session_start();
$member = New CMember;

if ($mail_adr == '') {
	if (!$_SESSION['login'])
		redirect('../index.php');
} else {
	$mail_adr = ToHankaku(trim($mail_adr));
	if (!MailAdrCheck($mail_adr)) {
		$member->err_mail_adr = true;
		$member->SaveData();
		redirect('login_error.php');
	}

	if (!$member->Login($mail_adr, $password)) {
		$member->err_password = true;
		$member->SaveData();
		redirect('login_error.php');
	}
	$member->SaveData();
	$_SESSION['login'] = true;
}
?>
<HTML>

	<HEAD>
		<META HTTP-EQUIV="content-type" CONTENT="text/html;charset=EUC-JP">
		<TITLE>MY PAGE</TITLE>
	</HEAD>

	<BODY BGCOLOR="white" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">

<? haimail_header('..'); ?>

		<BR>
		<BR>
		<TABLE BORDER="0" CELLSPACING="0" WIDTH="634" CELLPADDING="0">
			<TR>

		<TD WIDTH="50"><IMG HEIGHT="1" WIDTH="50" SRC="../img/space.gif"></TD>
				<TD><FONT COLOR="#2f2180"><B>あなたの現在のポイントは <?=GetTotalPoint($member->member_id)?> ポイントです。</B></FONT>
			<TABLE WIDTH="550" BORDER="0" CELLSPACING="2" CELLPADDING="2">
				<TR HEIGHT="16">
					<TD COLSPAN="2" HEIGHT="16"></TD>
				</TR>
				<TR HEIGHT="18">
					<TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">あなたの登録情報</TD>
				</TR>
				<TR>
					<TD WIDTH="451">
						<BLOCKQUOTE> <FONT SIZE="2">あなたにぴったりの情報をお届けするために、<BR>
							あなたの興味度、住所、よく行くお店などが変わったら<BR>
							ぜひ更新してください。</FONT><BR>
							　<BR>
						</BLOCKQUOTE>
					</TD>
					<TD>
						<CENTER>
							<A HREF="8a-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/toroku_b.gif" ALT="登録変更" BORDER="0"></A>
						</CENTER>
					</TD>
				</TR>
        <TR HEIGHT="18">
          <TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">メールで紹介</TD>
        </TR>
        <TR>
          <TD WIDTH="451">
            <BLOCKQUOTE> <FONT SIZE="2">あなたのお友達を紹介しませんか？<BR>
              紹介したお友達が はいめーる・net に登録されると<BR>
              「<FONT COLOR="#2f2180">あなた</FONT>」と「<FONT COLOR="#2f2180">お友達</FONT>」にそれぞれ</FONT><FONT SIZE="2" COLOR="#ff2f29">100</FONT><FONT
										SIZE="2">ポイント入ります。<br>
              </FONT></BLOCKQUOTE>
          </TD>
          <TD>
            <CENTER>
              <A HREF="8b-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/shokai_b.gif" ALT="紹介する" BORDER="0"></A>
            </CENTER>
          </TD>
        </TR>
        <TR HEIGHT="18">
          <TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">バナーで紹介</TD>
        </TR>
        <TR>
          <TD>
<BLOCKQUOTE>
              <P><FONT SIZE="2">あなたのＨＰで「はいめーる・ｎｅｔ」を紹介していただけませんか？
                <BR>
                ＨＰのバナーからメンバー登録していただいた方お一人につき <BR>
                お礼としてあなたに１００ポイント差し上げます。 </FONT><BR>
              </P>
            </BLOCKQUOTE>
          </TD>
          <TD HEIGHT="18"><A HREF="8f-2.html"><IMG HEIGHT="17" WIDTH="80" SRC="img/shokai_b.gif" ALT="紹介する" BORDER="0"></A></TD>
        </TR>
				<TR HEIGHT="18">
					<TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">ポイント交換</TD>
				</TR>
				<TR>
					<TD WIDTH="451">
						<BLOCKQUOTE> <FONT SIZE="2">あなたは何ポイントたまりましたか？<BR>
							たまったポイントはいろいろなものに交換できます。<BR>
							　</FONT><BR>
						</BLOCKQUOTE>
					</TD>
					<TD>
						<CENTER>
							<A HREF="8c-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/ichiran_b.gif" ALT="一覧を見る" BORDER="0"></A>
						</CENTER>
					</TD>
				</TR>
				<TR HEIGHT="18">
					<TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">受信拒否企業</TD>
				</TR>
				<TR>
					<TD WIDTH="451">
						<BLOCKQUOTE> <FONT SIZE="2">今までに届いた企業からのメールで<BR>
							今後受信を拒否したい企業を設定できます。<BR>
							　</FONT><BR>
						</BLOCKQUOTE>
					</TD>
					<TD>
						<CENTER>
							<A HREF="8d-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/settei_b.gif" ALT="設定する" BORDER="0"></A>
						</CENTER>
					</TD>
				</TR>
				<TR HEIGHT="18">
					<TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">退会</TD>
				</TR>
				<TR>
					<TD WIDTH="451">
						<BLOCKQUOTE> <FONT SIZE="2">とても残念ですが、退会したい方はこちらから設定できます。<BR>
							　<BR>
							</FONT></BLOCKQUOTE>
					</TD>
					<TD>
						<CENTER>
							<A HREF="8e-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/taikai_b.gif" ALT="退会する" BORDER="0"></A>
						</CENTER>
					</TD>
				</TR>
			</TABLE>
			<BR>
				</TD>
			</TR>
			<TR>
				<TD WIDTH="50"></TD>
				<TD><TABLE BORDER="0" CELLSPACING="0" MM_NOCONVERT="TRUE">
						<TR HEIGHT="16">
							<TD HEIGHT="16" ALIGN="left" VALIGN="top" WIDTH="550"><FONT SIZE="2">
								<HR>
								お問い合わせ先はこちらから　　<A HREF="mailto:okotae@haimail.net">okotae@haimail.net</A></FONT></TD>
						</TR>
						<TR>
							<TD ALIGN="left" VALIGN="top" WIDTH="550"><FONT SIZE="2"><BR>
								<BR>
								<BR>
								</FONT>
								<HR>
								<A HREF="../index.php"><IMG HEIGHT="17" WIDTH="80" SRC="../img/home_b.gif" BORDER="0" ALT="ホームへ戻る"></A></TD>
						</TR>
						</TD>
						</TR>
					</TABLE></TD>
			</TR>
		</TABLE>
		<br>

<? haimail_footer('..'); ?>

	</BODY>

</HTML>
