<?
$img = "$top/image";
$inc = "$top/inc";
function main($body_option='', $nocache=true){global $img; global $top;
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>�������ơ�net</TITLE>
<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
<LINK href="<?=$top?>/monitor.css" rel=stylesheet>
<SCRIPT src="<?=$top?>/monitor.js" type=text/javascript></SCRIPT>
<META content="MSHTML 6.00.2800.1515" name=GENERATOR></HEAD>
<? if ($nocache || $_SERVER["SERVER_PORT"] != 443) { ?>
<meta http-equiv="Pragma" content="no-cache">
<? } ?>
<BODY bgColor=#ffffff <?=$body_option?> onload="MM_preloadImages('image/myb_poichange_on.gif','image/myb_poiwhat_off.gif','image/myb_point_on.gif','image/myb_profile_on.gif','image/myb_news_on.gif','image/myb_rules_on.gif','image/myb_privacy_on.gif','image/myb_faq_on.gif','image/myb_poiwhat_on.gif','image/myb_quit_on.gif')">
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
		<TR>
		<TD class=leftmenu3 vAlign=top width=160>
			<TABLE cellSpacing=0 cellPadding=0 width=160 border=0>
				<TBODY>
					<TR vAlign=center align=middle>
						<TD class=myname background=<?=$img?>/my_namebg.gif height=60>���Ĥ���<BR>����ˤ���</TD>
					</TR>
<?
$chara_image = $_SESSION['ss_chara_image'];
	if ($chara_image) {
?>

<?
	if ($chara_image == '999999.gif') {
?>
					<TR vAlign=center align=middle BACKGROUND="image/my_pictbg.gif">
						<TD vAlign=center align=middle background=<?=$img?>/my_pictbg.gif height=138><a href="m_charaselect.php"><IMG height=104 alt=���ʤ��Τݤ������ src="<?=$img?>/<?=$chara_image?>" width=69></a></TD>
					</TR>
<?
	} else {
?>
					<TR vAlign=center align=middle BACKGROUND="image/my_pictbg.gif">
						<TD vAlign=center align=middle background=<?=$img?>/my_pictbg.gif height=138><IMG height=104 alt=���ʤ��Τݤ������ src="<?=$img?>/<?=$chara_image?>" width=69></a></TD>
					</TR>
<?
	}
?>
					<TR vAlign=top align=middle>
						<TD class=poiname height=20><!-- �ݤ�������̾��ɽ�� --><?=htmlspecialchars($_SESSION['ss_chara_name'])?></TD>
					</TR>
<?
}
?>
					<TR align=middle>
						<TD class=point>
							<A onmouseover="MM_swapImage('Image3','','image/myb_poichange_on.gif',1)"  onmouseout=MM_swapImgRestore() href="��"><IMG height=24 alt=�ݤ��������ѹ� src="<?=$img?>/myb_poichange_off.gif" width=160 border=0 name=Image3></A><BR>
							<A onmouseover="MM_swapImage('Image4','','image/myb_poiwhat_on.gif',1)" onmouseout=MM_swapImgRestore() href="��"><IMG height=24 alt=�ݤ������äƲ� src="<?=$img?>/myb_poiwhat_off.gif" width=160 border=0 name=Image4></A><BR>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=�ݥ���� src="<?=$img?>/my_sttl_point.gif" width=160>
							<!-- �ݥ����ɽ�� -->���ߤΥݥ���ȡ�<SPAN class=point_b>1,280</SPAN>�ݥ����<BR>�߷ץݥ���ȡ�<SPAN class=point_b>20,280</SPAN>�ݥ����<BR>
							<A onmouseover="MM_swapImage('Image6','','image/myb_point_on.gif',1)" onmouseout=MM_swapImgRestore() href="��">
							<IMG height=24 alt=�ݥ���Ȥ��������� src="<?=$img?>/myb_point_off.gif" width=160 border=0 name=Image6></A>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=�ץ�ե����� src="<?=$img?>/my_sttl_profile.gif" width=160><BR>
							<A onmouseover="MM_swapImage('Image7','','image/myb_profile_on.gif',1)" onmouseout=MM_swapImgRestore() href="��"><IMG height=24 alt=��Ͽ����γ�ǧ������ src="<?=$img?>/myb_profile_off.gif" width=160 border=0 name=Image7></A><BR>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=��̳�ɤ�� src="<?=$img?>/my_sttl_jimukyoku.gif" width=160><BR>
							<A onmouseover="MM_swapImage('Image11','','image/myb_news_on.gif',1)" onmouseout=MM_swapImgRestore() href="��"><IMG height=24 alt=���Τ餻 src="<?=$img?>/myb_news_off.gif" width=160 border=0 name=Image11></A><BR>
							<A onmouseover="MM_swapImage('Image12','','image/myb_rules_on.gif',1)" onmouseout=MM_swapImgRestore() href="��"><IMG height=24 alt=���� src="<?=$img?>/myb_rules_off.gif" width=160 border=0 name=Image12></A><BR>
							<A onmouseover="MM_swapImage('Image13','','image/myb_privacy_on.gif',1)" onmouseout=MM_swapImgRestore() href="��"><IMG height=24 alt=�ץ饤�Х����ݥꥷ�� src="<?=$img?>/myb_privacy_off.gif" width=160 border=0 name=Image13></A><BR>
							<A onmouseover="MM_swapImage('Image14','','image/myb_faq_on.gif',1)" onmouseout=MM_swapImgRestore() href="��"><IMG height=24 alt=�ƣ��� src="<?=$img?>/myb_faq_off.gif" width=160 border=0 name=Image14></A><BR>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=��� src="<?=$img?>/my_sttl_quit.gif" width=160><BR>
							<A onmouseover="MM_swapImage('Image8','','image/myb_quit_on.gif',1)" onmouseout=MM_swapImgRestore() href="��"><IMG height=24 alt=���Ϥ����餫�� src="<?=$img?>/myb_quit_off.gif" width=160 border=0 name=Image8></A>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
						</TD>
					</TR>
				</TBODY>
			</TABLE>
		</TD>
<?=contents();?>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
<?
}
?>