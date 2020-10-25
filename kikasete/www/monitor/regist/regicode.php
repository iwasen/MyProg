<?
$top = './..';
$inc = "$top/../inc";
include("$inc/mail.php");
include("$inc/encrypt.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_header.php");

// 登録受付けメール送信
function mail_send($reg, $seq_no) {
	get_mail_template('reg_mn1', $subject, $from, $cc, $bcc, $repty_to, $body);
	$fetch = get_system_info('sy_url_monitor');
	$body = str_replace('%MAIL_ADDR%', $reg->mail_addr, $body);
	$body = str_replace('%REGIST_URL%', "{$fetch->sy_url_monitor}regi-login.php?key=" . encrypt_number($seq_no, 8), $body);
	send_mail($subject, $reg->mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('regist2.php');
$reg = &$_SESSION['ss_regist'];

db_begin_trans();

// 仮登録テーブルに保存
$seq_no = $reg->save_db();

// アフィリエイト仮登録数更新
if ($cookie_affiliate_id) {
	$rec['afc_kari_count'] = 'afc_kari_count+1';
	db_update('t_affiliate_cnt', $rec, "afc_affiliate_id=$cookie_affiliate_id");
}

db_commit_trans();

// メール送信
mail_send($reg, $seq_no);

// セッション変数削除
session_destroy();
?>
<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_reg.gif" width=590 height=30 border=0 alt="モニター登録"></TD>
		<TD align="right" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_bk.gif" width=10 height=30 border=0></TD>
	</TR>
</TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD align="left"><IMG src="../image/reg5_m.gif" width=600 height=36 border=0 alt="仮登録完了"></TD>
</TR>
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD colspan="2">

	<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
	<TD align="left">
<SPAN class=fs12_lh120>
<font color="#666666">
<B>仮登録を受付けました。</B><BR>
<BR>
ただいまご登録頂きましたメールアドレス宛てに、【登録確認メール】を送らせて頂いておりますのでご確認ください。 <br>
こちらのメールは、メールアドレスが正しく登録されているかと、ご本人様であるかの確認のためとなっています。 <br>
<BR>
</font></SPAN>
	</TD>
	</TR>
	<TR>
	<TD align="left" bgcolor="#EEEEEE">

	<TABLE cellSpacing=0 cellPadding=10 border=0 width="100%">
	<TBODY>
	<TR>
	<TD align="left">
<SPAN class=fs12_lh120>
<font color="#CC0000">
<b>【登録確認メール】をご確認の上、そのメールに記載されたＵＲＬをクリックして登録手続きを完了させてください。 </b><BR>
<BR>
※ この手続きを完了しませんと、登録されたことにはなりませんのでご注意下さい。<br>
※ クリックをしてもページが開かない場合は、ＵＲＬをコピーしてブラウザのアドレスバーに直接貼り付けて下さい。<BR>
</font>
</SPAN>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TBODY>
</TABLE>
<DIV align="left"><img height="75" width="600" src="../image/reg_flow05.gif" border="0" alt="仮登録完了" vspace="15"></DIV>

</TD>
</TR>
</TBODY>
</TABLE>
<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
