#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteͽ�����ϥڡ���
' Content:�������Ƴ�ǧ����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");

// ���å���󤫤�ͽ��������
session_start();
if ($reserve == null)
	redirect('timeout.php');
?>
<HTML lang="ja">
<HEAD>
<TITLE>FLETTE / ��ͽ��</TITLE>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="../../css/flette.css" type="text/css">
<script language="JavaScript" src="../../js/fjs.js" type="text/JavaScript"></script>
<script type="text/javascript">
<!--
function test() {
	location.href = "thanks.php";
}
//-->
</script>
</head>

<body BGCOLOR="#ffffff" TEXT="#333333" MARGINWIDTH="0" MARGINHEIGHT="0" TOPMARGIN="0" LEFTMARGIN="0" onload="test()">

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
<TD ><A HREF="javascript:parent.close();"><img src="../../images/btn_close.gif" width="70" height="20" border="0" alt="������ɥ����Ĥ���"></A></TD>
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
			<IMG SRC="../../images/reserve_h2_04.gif" WIDTH=145 HEIGHT=36></TD>
		<TD>
			<IMG SRC="../../images/reserve_h1_05.gif" WIDTH=130 HEIGHT=36></TD>
	</TR>
</TABLE>

	</TD>
	</TR>
	<TR><TD width="710" bgcolor="#FFFFFF" colspan="2"><img src="../../images/spacer.gif" width="710" height="1"></TD></TR>
	<TR>
	<TD bgcolor="#595959" height="24" valign="center" width="692"><span class="txt-m">&nbsp;<font color="#FFFFFF">����ͽ��ɬ�ܹ��ܳ�ǧ&nbsp;&nbsp;&nbsp;</font></span><span class="txt-m"><B><font color="#FFFFFF"><?=htmlspecialchars($reserve->course)?>&nbsp;<?=htmlspecialchars($reserve->shop)?></font></B></span></TD>
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
<TD><span class="txt-m">�ʲ������ƤǤ�����Ǥ��礦����<BR>
����ǧ�ξ塢��ͽ�󿽹��ץܥ���򥯥�å����Ƥ���������<BR>
�����򤹤���ϡ��������������ܱ�ü�Ρ֢佤������פ������ϲ��̤���äƽ������Ƥ���������<BR></font></TD>
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

<?
// ͽ���˾������
for ($i = 0; $i < $reserve->choice_num; $i++) {
	$choice = &$reserve->choice[$i];
	$week = date('w', strtotime($choice->date));
?>
<!--�裱��˾-->
<TR>
<?
	if ($i == 0) {
?>
<TD width="105" bgcolor="#DCDCD7" rowspan="<?=$reserve->choice_num?>"><span class="txt-m">ͽ������</span></TD>
<?
	}
?>
<TD width="85" bgcolor="#FFFFFF" align="center"><span class="txt-m">��&nbsp;<?=mb_convert_kana($i + 1, 'N', 'EUC-JP')?>&nbsp;��˾</span></TD>
<TD width="95" bgcolor="#FFFFFF"><span class="txt-m"><?=$choice->date?>(<?=decode_week($week)?>)<BR><?=$choice->start_time?>��<?=$choice->end_time?></span></TD>
<TD width="128" bgcolor="#FFFFFF"><span class="txt-m"><?=htmlspecialchars($choice->room)?></span></TD>
<TD width="190" bgcolor="#FFFFFF"><span class="txt-m">�����󥻥顼�����̡�<?=decode_sex($choice->sex)?></span></TD>
<?
	if ($i == 0) {
?>
<TD width="80" bgcolor="#FFFFFF"rowspan="<?=$reserve->choice_num?>"><span class="txt-m"><a href="sche_form.php" class="link-txt">�佤������</a></span></TD>
<?
	}
?>
</TR>
<?
}
?>
<!--��������-->
<TR>
<TD width="105" bgcolor="#DCDCD7" rowspan="3"><span class="txt-m">��������</span></TD>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#DCDCD7" height="22" colspan="2"><span class="txt-14">&nbsp;<B><font color="#FD6000">��</font></B></span>
<span class="txt-m"><B><font color="#000000">���ä����������ƤˤĤ��ơ��ʲ��˳��������Τ��������ޤ��������Ӳ�������</font></B></span></TD>
	</TR>
	<TR><TD><img src="../../images/spacer.gif" width="1" height="4"></TD></TR>
	</TABLE>
<?
$sql = "SELECT cs_counseling_id,cs_level_no,cs_parent_counseling_id,cs_content"
		. " FROM m_counseling"
		. " WHERE cs_course_id=" . sql_number($reserve->course_id)
		. " ORDER BY cs_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$counseling_ary[$fetch->cs_level_no][$fetch->cs_parent_counseling_id][$fetch->cs_counseling_id] = $fetch->cs_content;
}


if (is_array($counseling_ary[1][0])) {
	$count1 = 0;
	foreach ($counseling_ary[1][0] as $counseling_id => $content) {
		if ($count1 != 0) {
?>
	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD ><img src="../../images/spacer.gif" width="1" height="10"></TD>
	</TR>
	</TABLE>
<?
		}
?>
	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#595959" height="22"><span class="txt-m">&nbsp;<font color="#FFFFFF">��<?=mb_convert_kana($count1 + 1, 'N', 'EUC-JP')?>��<?=htmlspecialchars($content)?></font></span></TD>
	<TD valign="top" bgcolor="#595959" align="right"><img src="../../images/corner_sttl_gr.gif" width="18" height="18"></TD>
	</TR>
	</TABLE>
<?
		if (is_array($counseling_ary[2][$counseling_id])) {
			foreach ($counseling_ary[2][$counseling_id] as $counseling_id => $content) {
?>
	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD ><img src="../../images/spacer.gif" width="1" height="5"></TD>
	</TR>
	</TABLE>
	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="100%">
<?
				if ($content != '') {
?>
	<TR>
	<TD width="15" ><span class="txt-14"></span></TD>
	<TD width="460"><span class="txt-14">��&nbsp;<?=htmlspecialchars($content)?>&nbsp;��</span></TD>
	</TR>
<?
				}
?>
	<TR>
	<TD width="15" ><span class="txt-14"></span></TD>
	<TD width="460"><span class="txt-m">
<?
				if (is_array($counseling_ary[3][$counseling_id])) {
					foreach ($counseling_ary[3][$counseling_id] as $counseling_id => $content) {
						if (in_array($counseling_id, $reserve->counseling_id))
							echo '��', htmlspecialchars($content), "<br>\n";
						else
							echo '<font color="gray">��', htmlspecialchars($content), "</font><br>\n";
					}
				}
?>
</span></TD>
	</TR>
	</TABLE>
<?
			}
		}
		$count1++;
	}
}
?>
</TD>
<TD width="80" bgcolor="#FFFFFF"rowspan="3"><span class="txt-m"><a href="con_form.php" class="link-txt">�佤������</a></span></TD>
</TR>

<TR>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#DCDCD7" colspan="2" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">��</font></B></span>
<span class="txt-m"><B><font color="#000000">�嵭��¾���ܤ������ä����������Ƥ��������ޤ����顢����ͳ�ˤ��񤭲�������</font></B></span></TD>
	</TR>
	</TABLE>

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="100%">
	<TR>
	<TD width="15" ><span class="txt-14"></span></TD>
	<TD width="460"><span class="txt-m"><span class="txt-m"><?=nl2br(htmlspecialchars($reserve->comment))?></span></TD>
	</TR>
	</TABLE>

</TD>
</TR>
<TR>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#DCDCD7" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">��</font></B></span>
<span class="txt-m"><B><font color="#000000">�����˥ե�åƤ����ѤΤ����ͤΤߤ���������������</font></B></span></TD>
	</TR>
	</TABLE>

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="100%">
	<TR>
	<TD width="15" ><span class="txt-14"></span></TD>
	<TD width="460"><span class="txt-m"><?=decode_change($reserve->change)?></span></TD>
	</TR>
	</TABLE>

</TD>
</TR>

<!--�Ŀ;���-->
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">��̾��<BR><font color="#FD6000">��ɬ��</font></span></TD>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="100%">
	<TR>
	<TD width="540"><span class="txt-m">
��&nbsp;��<?=htmlspecialchars($reserve->name_1)?>&nbsp;&nbsp;&nbsp;
̾&nbsp;��<?=htmlspecialchars($reserve->name_2)?><BR></span></TD>
	</TR>
	</TABLE>

</TD>
<TD width="80" bgcolor="#FFFFFF"rowspan="5"><span class="txt-m"><a href="pro_form.php" class="link-txt">�佤������</a></span></TD>
</TR>
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">��̾���ʥեꥬ�ʡ�<BR><font color="#FD6000">��ɬ��</font></span></TD>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="100%">
	<TR>
	<TD width="540"><span class="txt-m">
������<?=htmlspecialchars($reserve->kana_1)?>&nbsp;&nbsp;&nbsp;
�ᥤ��<?=htmlspecialchars($reserve->kana_2)?><BR></span></TD>
	</TR>
	</TABLE>

</TD>
</TR>

<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">ͽ���ǧ��<BR>�᡼�륢�ɥ쥹<BR><font color="#FD6000">��ɬ��</font></span></TD>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#DCDCD7" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">��</font></B></span>
<span class="txt-m"><B><font color="#000000">ͽ�����Ƴ�ǧ�᡼��������Ȥʤ�ޤ��Τǡ����ӥ᡼��ʳ������Ϥ��Ƥ���������</font></B></span></TD>
	</TR>
	<TR>
	<TR><TD ><img src="../../images/spacer.gif" width="1" height="5"></TD></TR>
	<TD >

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="100%">
	<TR>
	<TD width="15" ><span class="txt-14"></span></TD>
	<TD width="460"><span class="txt-m"><?=htmlspecialchars($reserve->mail_addr)?><BR></span></TD>
	</TR>
	</TABLE>

	</TD>
	</TR>
	</TABLE>

</TD>
</TR>
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">��˾Ϣ����<BR><font color="#FD6000">��ɬ��</font></span></TD>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#DCDCD7" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">��</font></B></span>
<span class="txt-m"><B><font color="#000000">ͽ������Ϣ��򤵤���ĺ��Ϣ����Ȥʤ�ޤ���ɬ�����İʾ����Ϥ��Ƥ���������</font></B></span></TD>
	</TR>
	<TR>
	<TR><TD ><img src="../../images/spacer.gif" width="1" height="5"></TD></TR>
	<TD >

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="100%">
	<TR>
	<TD width="15" ><span class="txt-14"></span></TD>
	<TD width="460">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<?
if ($reserve->contact_flag[0]) {
?>
	<TR><TD colspan="2"><span class="txt-m">�嵭��Ʊ���᡼�륢�ɥ쥹</span></TD>
	</TR>
<?
}
if ($reserve->contact_flag[1]) {
?>
	<TR><TD><span class="txt-m">�᡼��</span></TD>
		<TD><span class="txt-m">��<?=htmlspecialchars($reserve->contact_mail)?></span></TD>
	</TR>
<?
}
if ($reserve->contact_flag[2]) {
?>
	<TR><TD><span class="txt-m">��������&nbsp;</span></TD>
		<TD><span class="txt-m">��<?=htmlspecialchars($reserve->contact_keitai)?></span></TD>
	</TR>
<?
}
if ($reserve->contact_flag[3]) {
?>
	<TR><TD><span class="txt-m">��������&nbsp;</span></TD>
		<TD><span class="txt-m">��<?=htmlspecialchars($reserve->contact_jitaku)?></span></TD>
	</TR>
<?
}
?>
	</TABLE>

	</TD>
	</TR>
	</TABLE>

	</TD>
	</TR>
	</TABLE>

</TD>
</TR>

<!--��˾Ϣ�������-->
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">��˾Ϣ�������<BR></span></TD>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD bgcolor="#DCDCD7" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">��</font></B></span>
<span class="txt-m"><B><font color="#000000">���äǤ�Ϣ��򤴴�˾�ξ�硢�����򤯤�������</font></B></span></TD>
	</TR>
	<TR>
	<TR><TD ><img src="../../images/spacer.gif" width="1" height="5"></TD></TR>
	<TD >

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="100%">
	<TR>
	<TD width="15" ><span class="txt-14"></span></TD>
	<TD width="460"><span class="txt-m">
<?=decode_contact_time($reserve->contact_time)?><?=$reserve->contact_time && $reserve->contact_time_memo ? '&nbsp;��' : ''?><?=htmlspecialchars($reserve->contact_time_memo)?>
</span></TD>
	</TR>
	</TABLE>

	</TD>
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
<TD><a href="thanks.php"><img src="../../images/btn_reserve.gif" width="100" height="30" border="0" alt="ͽ�󿽹�"></TD>
<TD><img src="../../images/spacer.gif" width="15" height="30" border="0" ></TD>
<TD><a HREF="pro_form.php"><img src="../../images/btn_back.gif" width="100" height="30" border="0" alt="���"></a></TD>
</TR>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD height="30" valign="top" align="left">

	<TABLE WIDTH=740 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TD align="right"><A HREF="javascript:window.close();"><img src="../../images/btn_close.gif" width="70" height="20" border="0" alt="������ɥ����Ĥ���"></A></TD>
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
