<?/******************************************************
' System :きかせて・netモニターＭｙページ
' Content:左メニュー
'******************************************************/

include("$top/../inc/common.php");
include("$top/../inc/database.php");
session_start();
header('Content-Type: text/html; charset=EUC-JP');
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
function monitor_menu($body_option='', $nocache=true, $onload='') {global $top;global $img;global $monitor_name;
?>
<HTML>
<HEAD>
<TITLE>きかせて・net</TITLE>
<? if ($nocache || $_SERVER["SERVER_PORT"] != 443) { ?>
<meta http-equiv="Pragma" content="no-cache">
<? } ?>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
<LINK href="<?=$top?>/monitor.css" rel=stylesheet>
<SCRIPT src="<?=$top?>/monitor.js" type=text/javascript></SCRIPT>
</head>
<BODY bgColor=#ffffff <?=$body_option?> onLoad="MM_preloadImages('<?=$img?>/myb_poichange_on.gif','<?=$img?>/myb_poiwhat_off.gif','<?=$img?>/myb_point_on.gif','<?=$img?>/myb_profile_on.gif','<?=$img?>/myb_news_on.gif','<?=$img?>/myb_rules_on.gif','<?=$img?>/myb_privacy_on.gif','<?=$img?>/myb_faq_on.gif','<?=$img?>/myb_poiwhat_on.gif','<?=$img?>/myb_quit_on.gif');<?=$onload?>">
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
		<TR>
		<TD class=leftmenu3 vAlign=top width=160>
			<TABLE cellSpacing=0 cellPadding=0 width=160 border=0>
				<TBODY>
<?
// モニター名取得
$sql = "SELECT mn_name1 FROM t_monitor WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
$monitor_name = db_fetch1($sql);
?>
					<TR vAlign=center align=middle>
						<TD class=myname background=<?=$img?>/my_namebg.gif height=40><?=htmlspecialchars($monitor_name)?>さん<BR>こんにちは</TD>
					</TR>
<?
$chara_image = $_SESSION['ss_chara_image'];
	if ($chara_image) {
?>

<?
	if ($chara_image == '999999.gif') {
?>
					<TR vAlign=center align=middle BACKGROUND="<?=$img?>/my_pictbg.gif">
						<TD vAlign=center align=middle background=<?=$img?>/my_pictbg.gif height=138><a href="m_charaselect.php"><IMG height=104 alt=あなたのぽいきゃら src="<?=$img?>/<?=$chara_image?>" width=69></a></TD>
					</TR>
<?
	} else {
?>
					<TR vAlign=center align=middle BACKGROUND="<?=$img?>/my_pictbg.gif">
						<TD vAlign=center align=middle background=<?=$img?>/my_pictbg.gif height=138><IMG height=104 alt=あなたのぽいきゃら src="<?=$img?>/<?=$chara_image?>" width=69></a></TD>
					</TR>
<?
	}
?>
					<TR vAlign=top align=middle>
						<TD class=poiname height=20><?=htmlspecialchars($_SESSION['ss_chara_name'])?></TD>
					</TR>
<?
}
?>
					<TR align=middle>
						<TD class=point>
							<A href="m_charanm.php"><IMG src="<?=$img?>/myb_poichange_off.gif" width=160 height=24 border=0 alt="ポイキャラの変更" onMouseOver="this.src='<?=$img?>/myb_poichange_on.gif'" onMouseOut="this.src='<?=$img?>/myb_poichange_off.gif'"></A>
							<A href="m_chara.php"><IMG src="<?=$img?>/myb_poiwhat_off.gif" width=160 height=24 border=0 alt="ポイントキャラって何？" onMouseOver="this.src='<?=$img?>/myb_poiwhat_on.gif'" onMouseOut="this.src='<?=$img?>/myb_poiwhat_off.gif'"></A>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
<?
// ポイント数取得
$sql = "SELECT mp_curr_point,mp_total_point FROM t_monitor_point WHERE mp_monitor_id={$_SESSION['ss_monitor_id']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$curr_point = number_format($fetch->mp_curr_point);
	$total_point = number_format($fetch->mp_total_point);
} else {
	$curr_point = 0;
	$total_point = 0;
}
?>
<?
$sql = "SELECT mn_name1,mp_curr_point,mp_enq_history,mp_point_period - interval '1 day' AS point_period"
		. " FROM t_monitor JOIN t_monitor_point ON mn_monitor_id=mp_monitor_id"
		. " WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	sscanf($fetch->point_period, "%d-%d-%d", &$year, &$month, &$day);
}
?>
							<IMG height=27 alt=ポイント src="<?=$img?>/my_sttl_point.gif" width=160>
							現在のポイント：<SPAN class=point_b><?=$curr_point?></SPAN>ポイント<BR>
							<? /* 有効期限：<SPAN class=point_b><?=$year?>年<?=$month?>月末</SPAN><BR> */ ?>
							<A href="m_point_list.php"><IMG src="<?=$img?>/myb_pointlog_off.gif" width=160 height=24 border=0 alt="ポイントの履歴" onMouseOver="this.src='<?=$img?>/myb_pointlog_on.gif'" onMouseOut="this.src='<?=$img?>/myb_pointlog_off.gif'"></A>
							<A href="m_point.php"><IMG src="<?=$img?>/myb_pointchange_off.gif" width=160 height=24 border=0 alt="ポイントの精算" onMouseOver="this.src='<?=$img?>/myb_pointchange_on.gif'" onMouseOut="this.src='<?=$img?>/myb_pointchange_off.gif'"></A>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=プロフィール src="<?=$img?>/my_sttl_profile.gif" width=160><BR>
							<A href="m_update.php"><IMG src="<?=$img?>/myb_profile_off.gif" width=160 height=24 border=0 alt="登録情報の確認・更新" onMouseOver="this.src='<?=$img?>/myb_profile_on.gif'" onMouseOut="this.src='<?=$img?>/myb_profile_off.gif'"></A>
<div align="left" style="line-height:12px;">　定期的な更新をお願いします。<? /* <br>　更新いただいた月の翌月から数えて<br>　１２ヶ月後の月末までポイントの<br>　有効期限が延長になります。*/ ?></div>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=事務局より src="<?=$img?>/my_sttl_jimukyoku.gif" width=160><BR>
							<!-- <A href="●"><IMG src="<?=$img?>/myb_news_off.gif" width=160 height=24 border=0 alt="お知らせ" onmouseover="this.src='<?=$img?>/myb_news_on.gif'" onmouseout="this.src='<?=$img?>/myb_news_off.gif'"></A> -->
							<A href="rules.php"><IMG src="<?=$img?>/myb_rules_off.gif" width=160 height=24 border=0 alt="規約" onMouseOver="this.src='<?=$img?>/myb_rules_on.gif'" onMouseOut="this.src='<?=$img?>/myb_rules_off.gif'"></A>
							<A href="privacy.php"><IMG src="<?=$img?>/myb_privacy_off.gif" width=160 height=24 border=0 alt="プライバシーポリシー" onMouseOver="this.src='<?=$img?>/myb_privacy_on.gif'" onMouseOut="this.src='<?=$img?>/myb_privacy_off.gif'"></A>
							<A href="faq.php"><IMG src="<?=$img?>/myb_faq_off.gif" width=160 height=24 border=0 alt="FAQ" onMouseOver="this.src='<?=$img?>/myb_faq_on.gif'" onMouseOut="this.src='<?=$img?>/myb_faq_off.gif'"></A>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=退会 src="<?=$img?>/my_sttl_quit.gif" width=160><BR>
							<A onMouseOver="MM_swapImage('Image8','','<?=$img?>/myb_quit_on.gif',1)" onmouseout=MM_swapImgRestore() href="m_resign.php"><IMG height=24 alt=退会はこちらから src="<?=$img?>/myb_quit_off.gif" width=160 border=0 name=Image8></A>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
						</TD>
					</TR>
				</TBODY>
			</TABLE>
		</TD>
		<TD vAlign=top align=middle width=10><IMG height=1 src="<?=$img?>/spacer.gif" width=10></TD>
		<TD vAlign=top align=middle>
<?
}
?>