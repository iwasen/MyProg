#!/usr/local/bin/php4
<?
/******************************************************
' System :flette予約入力ページ
' Content:予約受付完了画面
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/mail.php");
include("$inc/macro.php");
include("$inc/decode.php");
include("$inc/csv.php");

// メール送信処理
function send_reserve_mail(&$macro_ary, $template_id) {
	// 送信メールテンプレート取得
	get_mail_template($template_id, $subject, $from_addr, $from_name, $cc_addr, $bcc_addr, $to_addr, $body);

	// 差し込みマクロ処理
	replace_macro($subject, $macro_ary);
	replace_macro($from_name, $macro_ary);
	replace_macro($from_addr, $macro_ary);
	replace_macro($to_addr, $macro_ary);
	replace_macro($cc_addr, $macro_ary);
	replace_macro($bcc_addr, $macro_ary);
	replace_macro($body, $macro_ary);

	// メール送信
	send_mail($subject, $to_addr, $from_addr, $from_name, $body, $cc_addr, $bcc_addr);
}

// 予約申込みログに書き込み
function write_log(&$reserve, &$data) {
	$rec['rl_seq_no'] = sql_number($reserve->seq);
	$rec['rl_date'] = sql_date($reserve->date);
	$rec['rl_ip_addr'] = sql_char($_SERVER['REMOTE_ADDR']);
	$rec['rl_data'] = sql_char(str_rot13(base64_encode($data)));
	db_insert('l_reserve', $rec);
}

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('timeout.php');

// 予約申し込み連番
$reserve->seq = get_next_seq('l_reserve', 'rl_seq_no');

// 予約日時
$reserve->date = date('Y/m/d H:i:s');

// 差し込みデータ取得
$macro_ary = get_macro($reserve);

// 予約申込みログに書き込み
write_log($reserve, $macro_ary['CSV']);

// 予約申込者と事務局にメール送信
send_reserve_mail($macro_ary, 'reserve_customer');
send_reserve_mail($macro_ary, 'reserve_center');

// セッション変数削除
session_unregister('reserve');
?>
<HTML lang="ja">
<HEAD>
<TITLE>FLETTE / ご予約</TITLE>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="../../css/flette.css" type="text/css">
<script language="JavaScript" src="../../js/fjs.js" type="text/JavaScript"></script>
<script type="text/javascript">
<!--
function test() {
<?
if (time() < $start + 60) {
?>
	location.href = "index.php?shop_id=1&course_id=1";
<?
}
?>
}
//-->
</script>
</head>

<body BGCOLOR="#ffffff" TEXT="#333333" MARGINWIDTH="0" MARGINHEIGHT="0" TOPMARGIN="0" LEFTMARGIN="0" onload="test()">

<font size="+2">
<?=++$counter?>
</font>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 >
<TR>
<TD align="left" background="../../images/header_bg.gif"><img src="../../images/header.gif" width="749" height="30" border="0"></TD>
</TR>
</TABLE>

<!--main start-->

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="10"></TD>
</TR>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD valign="left">

<TABLE WIDTH=740 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="10" height="1"></TD>
<TD ><img src="../../images/reserve_title.gif" width="660" height="20"></TD>
<TD ><A HREF="javascript:parent.close();"><img src="../../images/btn_close.gif" width="70" height="20" border="0" alt="ウインドウを閉じる"></A></TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="10"></TD>
</TR>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD valign="left">

<TABLE WIDTH=740 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="30" height="1"></TD>
<TD bgcolor="#DCDCD7" valign="center">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="710">
	<TR>
	<TD colspan="2">

<TABLE WIDTH=710 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD>
			<IMG SRC="../../images/reserve_h1_01.gif" WIDTH=145 HEIGHT=36></TD>
		<TD>
			<IMG SRC="../../images/reserve_h1_02.gif" WIDTH=145 HEIGHT=36></TD>
		<TD>
			<IMG SRC="../../images/reserve_h1_03.gif" WIDTH=145 HEIGHT=36></TD>
		<TD>
			<IMG SRC="../../images/reserve_h1_04.gif" WIDTH=145 HEIGHT=36></TD>
		<TD>
			<IMG SRC="../../images/reserve_h2_05.gif" WIDTH=130 HEIGHT=36></TD>
	</TR>
</TABLE>

	</TD>
	</TR>
	<TR><TD width="710" bgcolor="#FFFFFF" colspan="2"><img src="../../images/spacer.gif" width="710" height="1"></TD></TR>
	<TR>
	<TD bgcolor="#595959" height="24" valign="center" width="692"><span class="txt-m">&nbsp;<font color="#FFFFFF">５．予約受付完了&nbsp;&nbsp;&nbsp;</font></span><span class="txt-m"><B><font color="#FFFFFF"><?=htmlspecialchars($reserve->course)?>&nbsp;<?=htmlspecialchars($reserve->shop)?></font></B></span></TD>
	<TD align="right" valign="top" width="18" bgcolor="#595959"><img src="../../images/corner_sttl_gr.gif" width="18" height="18"></TD>
	</TR>
	</TABLE>

</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

<TABLE WIDTH=749 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="60"></TD>
</TR>
</table>

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD><img src="../../images/spacer.gif" width="52" height="1" border="0" ></TD>
<TD><span class="txt-m">ご予約ありがとうございます。<BR>
ただ今、“ご予約確認メール”を送らせていただきました。<BR>
<BR>
「ご予約確認メールが届かない」、「予約の内容を変更したい」などの
ご要望がございましたら、<BR>
お手数ですが、
<a href="mailto:info@flette.jp"><b>info@flette.jp</b></a>
もしくは&nbsp;フリーダイヤル&nbsp;<B>0120-2010-38</B>&nbsp;まで、ご連絡ください。<BR>
 <BR>
<B><font class="kome">※</font>現時点では、予約は確定しておりませんのでご注意ください。<BR></B>
&nbsp;&nbsp;&nbsp;予約確定のご連絡は、こちらよりお客様のご指定の方法でさせていただきます。<BR>
</span></TD>
</TR>
</table>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="160"></TD>
</TR>
</table>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD height="30" valign="top" align="left">

	<TABLE WIDTH=740 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TD align="right"><A HREF="javascript:window.close();"><img src="../../images/btn_close.gif" width="70" height="20" border="0" alt="ウインドウを閉じる"></A></TD>
	</TR>
	</table>

</TD>
</TR>
<TR>
<td bgcolor="#595959" align="right" class="txt-s"><font color="#FFFFFF"><?=COPYRIGHT?> &nbsp;&nbsp;&nbsp;&nbsp;</font></td>
</TR>
</TABLE>

</BODY>
</HTML>
