#!/usr/local/bin/php4
<?
/******************************************************
' System :flette問い合わせページ
' Content:問い合わせ内容入力画面
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/inquiry.php");
include("$inc/select.php");

// 問い合わせ件名
function select_inquiry($selected) {
	if ($selected == '')
		option_tag('', $selected, '--- お選びください ---');

	$sql = "SELECT iq_inquiry_id,iq_inquiry_text FROM m_inquiry ORDER BY iq_inquiry_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		option_tag($fetch->iq_inquiry_id, $selected, $fetch->iq_inquiry_text);
	}
}

// セッションから問い合わせ情報取得
session_start();
if ($inquiry == null)
	redirect('timeout.php');
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
<TD ><img src="../../images/spacer.gif" width="1" height="15"></TD>
</TR>
</table>

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD><img src="../../images/spacer.gif" width="52" height="1" border="0" ></TD>
<TD><span class="txt-m">必要事項を入力のうえ「送信」ボタンをクリックしてください。</font></TD>
</TR>
</table>

<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="15"></TD>
</TR>
</table>

<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="51" height="1"></TD>
<TD bgcolor="#FFFFFF" align="center">

<TABLE WIDTH=539 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor="#595959">
<TABLE WIDTH=100% BORDER=0 CELLPADDING=4 CELLSPACING=1>
<form method="post" name="form1" action="info_check.php">
<!--個人情報-->
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">お名前<BR><font color="#FD6000">※必須</font></span></TD>
<TD width="431" colspan="3" bgcolor="#FFFFFF"><span class="txt-m">
<INPUT maxLength=15 size=40 name="name" <?=value($inquiry->name)?>>
<?
if ($inquiry->error['name']) {
?>
<BR>
<font color="#CC0000"><?=$inquiry->error['name']?></font>
<?
}
?>
</span></TD>
</TR>
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">メールアドレス<BR><font color="#FD6000">※必須</font></span></TD>
<TD width="431" colspan="3" bgcolor="#FFFFFF">
<span class="txt-m">
<INPUT size=50 name="mail_addr" <?=value($inquiry->mail_addr)?>>&nbsp;<BR>
<INPUT size=50 name="mail_addr2" <?=value($inquiry->mail_addr2)?>>&nbsp;（確認用）
<?
if ($inquiry->error['mail_addr']) {
?>
<BR>
<font color="#CC0000"><?=$inquiry->error['mail_addr']?></font>
<?
}
?>
</span>
</TD>
</TR>
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">お問い合わせ内容<BR><font color="#FD6000">※必須</font></span></TD>
<TD width="431" colspan="3" bgcolor="#FFFFFF">

<span class="txt-m">
<select name="subject"> <? select_inquiry($inquiry->subject) ?></select>

<textarea name="content" cols=60 rows=15 class="txt-m"><?=htmlspecialchars($inquiry->content)?></textarea>
<?
if ($inquiry->error['inquiry']) {
?>
<BR>
<font color="#CC0000"><?=$inquiry->error['inquiry']?></font>
<?
}
?>
</span>

</TD>
</TR>
</table>
</TD>
</TR>
</TABLE>

</TD>
</TR>
</table>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="15"></TD>
</TR>
</table>

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD><img src="../../images/spacer.gif" width="51" height="30" border="0" ></TD>
<TD><input type="image" src="../../images/btn_soushin.gif" width="100" height="30" border="0" alt="送信"></TD>
</TR>
</form>
</TABLE>

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
