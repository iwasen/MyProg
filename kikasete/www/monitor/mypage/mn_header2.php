<?/******************************************************
' System :きかせて・netモニターＭｙページ
' Content:左メニュー
'******************************************************/

include("$top/../inc/common.php");
include("$top/../inc/database.php");
session_start();
header('Content-Type: text/html; charset=EUC-JP');
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
function monitor_menu($body_option='', $nocache=true) {global $top;global $img;
?>
<HTML>
<HEAD>
<TITLE>きかせて・net</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
<LINK href="<?=$top?>/monitor.css" rel=stylesheet>
<SCRIPT src="<?=$top?>/monitor.js" type=text/javascript></SCRIPT>
</head>
<? if ($nocache || $_SERVER["SERVER_PORT"] != 443) {
?>
<meta http-equiv="Pragma" content="no-cache">
<?
}
?>
<BODY bgColor=#ffffff <?=$body_option?> onload="MM_preloadImages('image/myb_poichange_on.gif','image/myb_poiwhat_off.gif','image/myb_point_on.gif','image/myb_profile_on.gif','image/myb_news_on.gif','image/myb_rules_on.gif','image/myb_privacy_on.gif','image/myb_faq_on.gif','image/myb_poiwhat_on.gif','image/myb_quit_on.gif')">
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
						<TD class=myname background=<?=$img?>/my_namebg.gif height=60><?=htmlspecialchars($monitor_name)?>さん<BR>こんにちは</TD>
					</TR>
<?
$chara_image = $_SESSION['ss_chara_image'];
	if ($chara_image) {
?>

<?
	if ($chara_image == '999999.gif') {
?>
					<TR vAlign=center align=middle BACKGROUND="image/my_pictbg.gif">
						<TD vAlign=center align=middle background=<?=$img?>/my_pictbg.gif height=138><a href="m_charaselect.php"><IMG height=104 alt=あなたのぽいきゃら src="<?=$img?>/<?=$chara_image?>" width=69></a></TD>
					</TR>
<?
	} else {
?>
					<TR vAlign=center align=middle BACKGROUND="image/my_pictbg.gif">
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
							<A onmouseover="MM_swapImage('Image3','','image/myb_poichange_on.gif',1)"  onmouseout=MM_swapImgRestore() href="m_charanm.php"><IMG height=24 alt=ぽいきゃらの変更 src="<?=$img?>/myb_poichange_off.gif" width=160 border=0 name=Image3></A><BR>
							<A onmouseover="MM_swapImage('Image4','','image/myb_poiwhat_on.gif',1)" onmouseout=MM_swapImgRestore() href="m_chara.php"><IMG height=24 alt=ぽいきゃらって何 src="<?=$img?>/myb_poiwhat_off.gif" width=160 border=0 name=Image4></A><BR>
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
							<IMG height=27 alt=ポイント src="<?=$img?>/my_sttl_point.gif" width=160>
							現在のポイント：<SPAN class=point_b><?=$curr_point?></SPAN>ポイント<BR>累計ポイント：<SPAN class=point_b><?=$total_point?></SPAN>ポイント<BR>
							<A onmouseover="MM_swapImage('Image6','','image/myb_point_on.gif',1)" onmouseout=MM_swapImgRestore() href="m_point_list.php">
							<IMG height=24 alt=ポイントの履歴・精算 src="<?=$img?>/myb_point_off.gif" width=160 border=0 name=Image6></A>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=プロフィール src="<?=$img?>/my_sttl_profile.gif" width=160><BR>
							<A onmouseover="MM_swapImage('Image7','','image/myb_profile_on.gif',1)" onmouseout=MM_swapImgRestore() href="m_update.php"><IMG height=24 alt=登録情報の確認・更新 src="<?=$img?>/myb_profile_off.gif" width=160 border=0 name=Image7></A><BR>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=事務局より src="<?=$img?>/my_sttl_jimukyoku.gif" width=160><BR>
							<A onmouseover="MM_swapImage('Image11','','image/myb_news_on.gif',1)" onmouseout=MM_swapImgRestore() href="●"><IMG height=24 alt=お知らせ src="<?=$img?>/myb_news_off.gif" width=160 border=0 name=Image11></A><BR>
							<A onmouseover="MM_swapImage('Image12','','image/myb_rules_on.gif',1)" onmouseout=MM_swapImgRestore() href="●"><IMG height=24 alt=規約 src="<?=$img?>/myb_rules_off.gif" width=160 border=0 name=Image12></A><BR>
							<A onmouseover="MM_swapImage('Image13','','image/myb_privacy_on.gif',1)" onmouseout=MM_swapImgRestore() href="●"><IMG height=24 alt=プライバシーポリシー src="<?=$img?>/myb_privacy_off.gif" width=160 border=0 name=Image13></A><BR>
							<A onmouseover="MM_swapImage('Image14','','image/myb_faq_on.gif',1)" onmouseout=MM_swapImgRestore() href="●"><IMG height=24 alt=ＦＡＱ src="<?=$img?>/myb_faq_off.gif" width=160 border=0 name=Image14></A><BR>
							<IMG src="header_my_data/spacer.gif" height="5" width="1"><BR>
							<IMG height=27 alt=退会 src="<?=$img?>/my_sttl_quit.gif" width=160><BR>
							<A onmouseover="MM_swapImage('Image8','','image/myb_quit_on.gif',1)" onmouseout=MM_swapImgRestore() href="m_resign.php"><IMG height=24 alt=退会はこちらから src="<?=$img?>/myb_quit_off.gif" width=160 border=0 name=Image8></A>
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