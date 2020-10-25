#!/usr/local/bin/php4
<?
/******************************************************
' System :flette問い合わせページ
' Content:問い合わせ完了画面
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/inquiry.php");
include("$inc/mail.php");
include("$inc/macro.php");
include("$inc/decode.php");

// セッションから問い合わせ情報取得
session_start();
if ($inquiry == null)
	redirect('timeout.php');

// 送信メールテンプレート取得
get_mail_template('inquiry', $subject, $from_addr, $from_name, $cc_addr, $bcc_addr, $to_addr, $body);

// 差込マクロ
$macro_ary['INQUIRY_SUBJECT'] = decode_inquiry_subject($inquiry->subject);
$macro_ary['INQUIRY_CONTENT'] = $inquiry->content;
$macro_ary['INQUIRY_NAME'] = $inquiry->name;
$macro_ary['MAIL_ADDR'] = $inquiry->mail_addr;

// 差込マクロ置換
replace_macro($subject, $macro_ary);
replace_macro($from_name, $macro_ary);
replace_macro($from_addr, $macro_ary);
replace_macro($to_addr, $macro_ary);
replace_macro($cc_addr, $macro_ary);
replace_macro($bcc_addr, $macro_ary);
replace_macro($body, $macro_ary);

// メール送信
send_mail($subject, $to_addr, $inquiry->mail_addr, $from_name, $body, $cc_addr, $bcc_addr);

// セッション変数削除
session_unregister('inquiry');
?>
<HTML>
<HEAD>
<TITLE>FLETTE / お問い合わせ</TITLE>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="../../css/flette.css" type="text/css">
<script language="JavaScript" src="../../js/fjs.js" type="text/JavaScript"></script>
</head>

<body BGCOLOR="#ffffff" TEXT="#333333" MARGINWIDTH="0" MARGINHEIGHT="0" TOPMARGIN="0" LEFTMARGIN="0">

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 >
<TR>
<TD align="left" background="../../images/header_bg.gif"><img src="../../images/header2.gif" width="599" height="30" border="0"></TD>
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

<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="10" height="1"></TD>
<TD ><img src="../../images/info_title.gif" width="510" height="20"></TD>
<TD ><A HREF="javascript:window.close();"><img src="../../images/btn_close.gif" width="70" height="20" border="0"></A></TD>
</TR>
</TABLE>

<TABLE WIDTH=74 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="10"></TD>
</TR>
</table>

<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="20" height="1"></TD>
<TD ><img src="../../images/info_subtit.gif" width="570" height="18"></TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

<TABLE WIDTH=599 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="100"></TD>
</TR>
</table>

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD><img src="../../images/spacer.gif" width="52" height="1" border="0" ></TD>
<TD><span class="txt-m">お問い合わせありがとうございます。</font></TD>
</TR>
</table>

<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="150"></TD>
</TR>
</table>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD height="30" valign="top" align="left">

	<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
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
