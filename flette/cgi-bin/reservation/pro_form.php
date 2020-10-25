#!/usr/local/bin/php4
<?
/******************************************************
' System :flette予約入力ページ
' Content:基本情報入力画面
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");
include("$inc/select.php");

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('timeout.php');
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
	document.form1.name1.value = "相沢";
	document.form1.name2.value = "伸一";
	document.form1.kana1.value = "アイザワ";
	document.form1.kana2.value = "シンイチ";
	document.form1.mail_addr.value = "aizawa@flette.xxxxx-xxxx.co.jp";
	document.form1.mail_addr2.value = "aizawa@flette.xxxxx-xxxx.co.jp";
	document.form1["contact_flag[0]"].checked = true;
	document.form1.submit();
}
//-->
</script>
</head>

<body BGCOLOR="#ffffff" TEXT="#333333" MARGINWIDTH="0" MARGINHEIGHT="0" TOPMARGIN="0" LEFTMARGIN="0" onload="test()">
<form method="post" name="form1" action="pro_check.php">

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
			<IMG SRC="../../images/reserve_h2_03.gif" WIDTH=145 HEIGHT=36></TD>
		<TD>
			<IMG SRC="../../images/reserve_h1_04.gif" WIDTH=145 HEIGHT=36></TD>
		<TD>
			<IMG SRC="../../images/reserve_h1_05.gif" WIDTH=130 HEIGHT=36></TD>
	</TR>
</TABLE>

	</TD>
	</TR>
	<TR><TD width="710" bgcolor="#FFFFFF" colspan="2"><img src="../../images/spacer.gif" width="710" height="1"></TD></TR>
	<TR>
	<TD bgcolor="#595959" height="24" valign="center" width="692"><span class="txt-m">&nbsp;<font color="#FFFFFF">３．基礎情報入力&nbsp;&nbsp;&nbsp;</font></span><span class="txt-m"><B><font color="#FFFFFF"><?=htmlspecialchars($reserve->course)?>&nbsp;<?=htmlspecialchars($reserve->shop)?></font></B></span></TD>
	<TD align="right" valign="top" width="18" bgcolor="#595959"><img src="../../images/corner_sttl_gr.gif" width="18" height="18"></TD>
	</TR>
	</TABLE>

</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR><TD ><img src="../../images/spacer.gif" width="1" height="15"></TD></TR>
</table>

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD><img src="../../images/spacer.gif" width="52" height="1" border="0" ></TD>
<TD><span class="txt-m">必要事項を入力の上「予約確認」ボタンをクリックしてください。</font></TD>
</TR>
</table>

<TABLE WIDTH=749 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="15"></TD>
</TR>
</table>

<TABLE WIDTH=740 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="51" height="1"></TD>
<TD bgcolor="#FFFFFF" align="center">

<TABLE WIDTH=689 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor="#595959">
<TABLE WIDTH=100% BORDER=0 CELLPADDING=4 CELLSPACING=1>

<!--個人情報-->
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">お名前<BR><font color="#FD6000">※必須</font></span></TD>
<TD width="581" colspan="3" bgcolor="#FFFFFF"><span class="txt-m">
姓&nbsp;&nbsp;：<INPUT maxLength=15 size=20 name="name1" <?=value($reserve->name_1)?>>&nbsp;&nbsp;&nbsp;
名&nbsp;&nbsp;：<INPUT maxLength=15 size=20 name="name2" <?=value($reserve->name_2)?>>
<?
if ($reserve->error['name']) {
?>
<br><font color="#CC0000"><?=$reserve->error['name']?></font>
<?
}
?>
</span></TD>
</TR>
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">お名前（フリガナ）<BR><font color="#FD6000">※必須</font></span></TD>
<TD width="581" colspan="3" bgcolor="#FFFFFF"><span class="txt-m">
セイ：<INPUT maxLength=15 size=20 name="kana1" <?=value($reserve->kana_1)?>>&nbsp;&nbsp;&nbsp;
メイ：<INPUT maxLength=15 size=20 name="kana2" <?=value($reserve->kana_2)?>>
<?
if ($reserve->error['kana']) {
?>
<br><font color="#CC0000"><?=$reserve->error['kana']?></font>
<?
}
?>
</span></TD>
</TR>
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">予約確認用<BR>メールアドレス<BR><font color="#FD6000">※必須</font></span></TD>
<TD width="581" colspan="3" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#DCDCD7" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">■</font></B></span>
<span class="txt-m"><B><font color="#000000">予約確認メール送付先となりますので、携帯メール以外をお勧めします。</font></B><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;※携帯メールの場合、文章が途中で切れる可能性があります。</span></TD>
	</TR>
	<TR>
	<TR><TD ><img src="../../images/spacer.gif" width="1" height="5"></TD></TR>
	<TD >
<span class="txt-m">
<INPUT size=50 name="mail_addr" <?=value($reserve->mail_addr)?>>&nbsp;<BR>
<INPUT size=50 name="mail_addr2" <?=value($reserve->mail_addr2)?>>&nbsp;（確認用）
<?
if ($reserve->error['mail_addr']) {
?>
<br><font color="#CC0000"><?=$reserve->error['mail_addr']?></font>
<?
}
?>
</span></TD>
	</TR>
	</TABLE>

</TD>
</TR>
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">希望連絡先<BR><font color="#FD6000">※必須</font></span></TD>
<TD width="581" colspan="3" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#DCDCD7" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">■</font></B></span>
<span class="txt-m"><B><font color="#000000">予約確定の連絡をさせて頂く連絡先となります。必ず１つ以上入力してください。</font></B></span></TD>
	</TR>
	<TR>
	<TR><TD ><img src="../../images/spacer.gif" width="1" height="5"></TD></TR>
	<TD >

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR><TD><span class="txt-m"><input type="checkbox" name="contact_flag[0]" <?=value_checked('1', $reserve->contact_flag[0])?>></TD>
		<TD colspan="2"><span class="txt-m">上記と同じメールアドレス</span></TD>
	</TR>
	<TR><TD><span class="txt-m"><input type="checkbox" name="contact_flag[1]" <?=value_checked('1', $reserve->contact_flag[1])?>></TD>
		<TD><span class="txt-m">メール</span></TD>
		<TD><span class="txt-m"><INPUT size=50 name="contact_mail" <?=value($reserve->contact_mail)?>></span></TD>
	</TR>
	<TR><TD><span class="txt-m"><input type="checkbox" name="contact_flag[2]" <?=value_checked('1', $reserve->contact_flag[2])?>></TD>
		<TD><span class="txt-m">携帯電話&nbsp;</span></TD>
		<TD><span class="txt-m"><INPUT size=20 name="contact_keitai" <?=value($reserve->contact_keitai)?>>&nbsp;（例：090-1234-5678）</span></TD>
	</TR>
	<TR><TD><span class="txt-m"><input type="checkbox" name="contact_flag[3]" <?=value_checked('1', $reserve->contact_flag[3])?>></TD>
		<TD><span class="txt-m">自宅電話&nbsp;</span></TD>
		<TD><span class="txt-m"><INPUT size=20 name="contact_jitaku" <?=value($reserve->contact_jitaku)?>>&nbsp;（例：03-1234-5678）</span></TD>
	</TR>
<?
if ($reserve->error['contact']) {
?>
	<TR><TD colspan="3"><span class="txt-m"><font color="#CC0000"><?=$reserve->error['contact']?></font></span></TD></TR>
<?
}
?>
	</TABLE>

	</TD>
	</TR>
	</TABLE>

</TD>
</TR>

<!--メールマガジン登録-->
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">希望連絡時間帯<BR></span></TD>
<TD width="581" colspan="3" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#DCDCD7" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">■</font></B></span>
<span class="txt-m"><B><font color="#000000">電話での連絡をご希望の場合、ご選択ください。</font></B></span></TD>
	</TR>
	<TR>
	<TR><TD ><img src="../../images/spacer.gif" width="1" height="5"></TD></TR>
	<TD >
<span class="txt-m">
<SELECT NAME="contact_time">
		<OPTION value="">--- 時間帯をお選びください ---
		<OPTION <?=value_selected('1', $reserve->contact_time)?>>特に希望なし
		<OPTION <?=value_selected('2', $reserve->contact_time)?>>午前中（10:00〜12:00）
		<OPTION <?=value_selected('3', $reserve->contact_time)?>>午後（12:00〜17:00）
		<OPTION <?=value_selected('4', $reserve->contact_time)?>>夜（17:00〜21:00）
		<OPTION <?=value_selected('5', $reserve->contact_time)?>>その他
		</SELECT><BR>
<INPUT size=50 name="contact_time_memo" <?=value($reserve->contact_time_memo)?>><BR>
※特別にご希望がある場合にご入力ください。
<?
if ($reserve->error['contact_time']) {
?>
<br><font color="#CC0000"><?=$reserve->error['contact_time']?></font>
<?
}
?>
</span></TD>
	</TR>
	</TABLE>

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
<TD><input type="image" src="../../images/btn_kakunin.gif" width="100" height="30" border="0" alt="予約確認"></TD>
<TD><img src="../../images/spacer.gif" width="15" height="30" border="0" ></TD>
<TD><a HREF="con_form.php"><img src="../../images/btn_back.gif" width="100" height="30" border="0" alt="戻る"></a></TD>
</TR>
</TABLE>

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
</form>
</BODY>
</HTML>
