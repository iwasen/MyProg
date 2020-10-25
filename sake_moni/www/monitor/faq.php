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
<TITLE>お酒と買物のアンケートモニター｜よくある質問｜</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/JavaScript">
<link rel="stylesheet" href="style.css" type="text/css">
<script language="JavaScript" src="mouseover.js"></script>
</HEAD>
<body bgcolor=#DDDDDD text=#333333 link=#333333 vlink=#333333 alink=#333333 
leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth=0 marginheight=0>
<a name="top"></a>
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
			<td align="right"><span class="t12"><a href="m_login.php">≫ログイン画面に戻る</a></span></td>
		</tr>
		</table>
	</TD>
	</TR>
	<TR>
	<TD align="center">
		<table border="0" cellspacing="0" cellpadding="2" width="508">
		<a name="q1"></a>
		<tr bgcolor="#dfeffe">
			<td width="35"><img src="images/q.gif" alt="質問" width="32" height="32" border="0"></td>
			<td width="465" class="t12"><b>お酒と買物アンケートモニターって・・・</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="回答" width="32" height="32" border="0"></td>
			<TD bgcolor="#fdf1e6" class=t12>モニターの募集は<A href="http://www.kikasete.net/" 
                  target=_blank>きかせて・net</A>、<A href="http://www.imi.ne.jp/" 
                  target=_blank>iMiネット</A>を通じて行っております。 
			  お酒の買物についてや、お酒を買われているお店についての評価などをＷＥＢアンケートにてお聞きします。 
			  ＷＥＢアンケートの準備ができ次第、電子メールでお知らせします。 
			  アンケート用の専用ページにログインしていただき、アンケートにご回答いただきます。 
			  活動期間は、２００６年１月〜２００６年１２月の予定です。</TD>
		</tr>
		<tr valign="top">
			<td width="35"><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td width="465" class="t10" align="right"><a href="#top">▲このページのトップへ戻る</a></td>
		</tr>
		<a name="q2"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="質問" width="32" height="32" border="0"></td>
			<td class="t12"><b>回答謝礼はどのように支払われるのですか・・・</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="回答" width="32" height="32" border="0"></td>
			<TD bgcolor="#fdf1e6" 
                  class=t12><B>【きかせて・net】を通じてご応募いただいた方</B>は、きかせて・netポイントとして、 <BR>
			    <B>【iMiネット】を通じてご応募いただいた方</B>は、iMiポイントとして、 <BR>
			  アンケート回答の翌月末までに差し上げます。 </TD>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">▲このページのトップへ戻る</a></td>
		</tr>
		<a name="q3"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="質問" width="32" height="32" border="0"></td>
			<td class="t12"><b>メールアドレスを変更したいのですが・・・</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="回答" width="32" height="32" border="0"></td>
			<TD bgcolor="#fdf1e6" 
                  class=t12>メールアドレスを変更したい場合は、ログイン後、トップページの右上にあります【メールアドレスの変更】から変更していただけます。</TD>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">▲このページのトップへ戻る</a></td>
		</tr>
		<a name="q4"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="質問" width="32" height="32" border="0"></td>
			<td class="t12"><b>パスワードを変更したいのですが・・・</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="回答" width="32" height="32" border="0"></td>
			<td class="t12">パスワードを変更したい場合は、ログイン後、トップページの右上にあります【パスワード変更】から変更していただけます。</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">▲このページのトップへ戻る</a></td>
		</tr>
		<a name="q5"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="質問" width="32" height="32" border="0"></td>
			<td class="t12"><b>パスワードを忘れてしまったのですが・・・</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="回答" width="32" height="32" border="0"></td>
			<td class="t12">ログイン画面の「<A 
                  href="m_pw.php">パスワード忘れはこちらから</A>」よりお問合せいただくとメールにてパスワードをお知らせいたします。</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">▲このページのトップへ戻る</a></td>
		</tr>
		<a name="q6"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="質問" width="32" height="32" border="0"></td>
			<td class="t12"><b>アンケートがなかなか届かないのですが・・・</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="回答" width="32" height="32" border="0"></td>
			<td class="t12">アンケートは主に、みなさまがお酒を買われる「お店」（ご応募いただいた際にご回答いただいた）ごとに行われます。 
                  お店によってはアンケートの頻度が異なってしまいます。ご了承ください。</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">▲このページのトップへ戻る</a></td>
		</tr>
		<a name="q7"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="質問" width="32" height="32" border="0"></td>
			<td class="t12"><b>ログインしてもアンケートが表示されていないのですが・・・</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="回答" width="32" height="32" border="0"></td>
			<td class="t12">現在実施中のアンケートが無い場合、アンケートは表示されておりません。 
                  順次アンケートのお願いをしていく予定ですので、その際はぜひご協力をお願いいたします。</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">▲このページのトップへ戻る</a></td>
		</tr>
		<a name="q8"></a>
		<tr bgcolor="#dfeffe">
		  <td><img src="images/q.gif" alt="質問" width="32" height="32" border="0"></td>
		  <td class="t12"><b>お酒を購入しているお店が変わってしまったのですが・・・</b></td>
		</tr>
		<tr bgcolor="#dfeffe">
		  <td valign="top" bgcolor="#FDF1E6"><img src="images/a.gif" alt="回答" width="32" height="32" border="0"></td>
		  <td valign="top" bgcolor="#FDF1E6" class="t12">お引越しや利用しているお店の閉店などで、お酒を購入しているお店が変わってしまった場合は、大変お手数ですが「お酒と買物のアンケートモニター事務局」までご連絡ください。</td>
		</tr>
		<tr>
		  <td valign="top"><img src="images/spacer.gif" width="35" height="35" border="0"></td>
		  <td align="right" valign="top" class="t10"><a href="#top">▲このページのトップへ戻る</a></td>
		</tr>
		<a name="q9"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="質問" width="32" height="32" border="0"></td>
			<td class="t12"><b>このアンケートはどこが行っているの？</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="回答" width="32" height="32" border="0"></td>
			<td class="t12">株式会社○○○○○○（<A href="http://www.xxxxxxx.co.jp/" 
                  target=_blank>http://www.xxxxxxx.co.jp/</A>）にて行っております。株式会社○○○○○○は今年２８年目を迎えたマーケティング会社です。<BR>個人情報は株式会社○○○○○○「お酒と買物アンケートモニター」事務局が厳重に管理しております。</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">▲このページのトップへ戻る</a></td>
		</tr>
		</table>
	</TD>
	</TR>
	<TR>
	<TD>
		<table border="0" cellspacing="0" cellpadding="20" width="100%">
		<tr>
		  <td align="right"><table width="300" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="images/td_top.gif" width="301" height="39"></td>
            </tr>
            <tr>
              <td style="border-color:#8EC8FF; border-style:solid; border-width:1px; padding:15px" class="t12">上記以外にご不明な点がございましたら、
              <br>
事務局までご質問ください。<br>
<br>
お問合せ先　：　<a href="mailto:sake_moni@xxxxxxx.co.jp?subject=%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9">sake_moni@xxxxxxx.co.jp</a></td>
            </tr>
            <tr>
              <td><img src="images/td_btt.gif" width="301" height="20"></td>
            </tr>
          </table></td>
		  </tr>
		<tr>
			<td align="right"><span class="t12"><a href="m_login.php">≫ログイン画面に戻る</a></span></td>
		</tr>
		</table>
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