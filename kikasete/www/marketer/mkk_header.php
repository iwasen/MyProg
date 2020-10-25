<?
/******************************************************
' System :きかせて・netマーケター有料Ｍｙページ
' Content:共通ヘッダ、フッタ
'******************************************************/

define('PG_NULL', 0);
define('PG_HOME', 1);
define('PG_CONTACT', 2);
define('PG_MAP', 3);
define('PG_KIKASETE', 4);
define('PG_SERVICE', 5);
define('PG_PRICE', 6);
define('PG_REGIST', 7);
define('PG_SURVEY', 8);
define('PG_BUSINESS', 9);
define('PG_CENTER', 10);
define('PG_HINT', 11);

header('Content-Type: text/html; charset=EUC-JP');

function marketer_header($title, $curr_page, $body_option='') {
	global $my_agent_id;

	// エージェントID取得
	$sql = "SELECT mr_agent_id"
			. " FROM t_marketer"
			. " WHERE mr_marketer_id={$_SESSION['ss_owner_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$my_agent_id = $fetch->mr_agent_id;
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>きかせて・net−<?=$title?></title>
<link rel="stylesheet" href="kikasete.css">
<!-- hint 追加 -->
<script language="JavaScript" type="text/javascript" src="mm_menu.js"></script>
<!-- hint 追加 -->

<SCRIPT LANGUAGE="JavaScript">
<!--
function new_window(url, width, height) {
	var attributes = "toolbar=no,location=no,status=0,scrollbars=1,resizable=1,width=";
	attributes += width + ",height=" + height;
	window.open(url, 'mainhelp', attributes);
}
// -->
</SCRIPT>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <?=$body_option?>>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="4"><img src="images/common/spacer.gif" width="788" height="1" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="images/common/spacer.gif" width="10" height="88" border="0"></td>
		<td>
			<img src="images/mkk_header1.gif" width="510" height="88" border="0"></td>
		<td>
			<img src="images/common/spacer.gif" width="10" height="88" border="0"></td>
		<td border="0" width="190" height="88">
			<table bordercolor="#333366" border="1" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td bgcolor="#EFF7F8" colspan="6" border="0">
									<img src="images/common/spacer.gif" width="184" height="5" border="0"></td>
								<td border="0">
									<img src="images/common/spacer.gif" width="1" height="5" border="0"></td>
							</tr>
							<tr>
								<td bgcolor="#EFF7F8" colspan="2" rowspan="2" border="0">
									<img src="images/common/spacer.gif" width="73" height="8" border="0"></td>
								<td colspan="3" border="0">
									<img src="images/message_home_top.gif" width="96" height="4" border="0"></td>
								<td bgcolor="#EFF7F8" rowspan="6" border="0">
									<img src="images/common/spacer.gif" width="15" height="79" border="0"></td>
								<td border="0">
									<img src="images/common/spacer.gif" width="1" height="4" border="0"></td>
							</tr>
							<tr>
								<td rowspan="4" border="0">
									<img src="images/message_home_left.gif" width="11" height="70" border="0"></td>
								<td rowspan="3" border="0">
									<a href="mkk_message.php"><img src="images/message<?=$my_agent_id?>.gif" width="81" height="66" border="0"></a></td>
								<td rowspan="4" border="0">
									<img src="images/message_home_right.gif" width="4" height="70" border="0"></td>
								<td border="0">
									<img src="images/common/spacer.gif" width="1" height="4" border="0"></td>
							</tr>
							<tr>
								<td bgcolor="#EFF7F8" rowspan="4" border="0">
									<img src="images/common/spacer.gif" width="13" height="71" border="0"></td>
								<td border="0">
									<a href="mkk_message.php"><img src="images/agent<?=$my_agent_id?>.jpg" width="60" height="60" border="0"></a></td>
								<td border="0">
									<img src="images/common/spacer.gif" width="1" height="60" border="0"></td>
							</tr>
							<tr>
								<td bgcolor="#EFF7F8" rowspan="3" border="0">
									<img src="images/common/spacer.gif" width="60" height="11" border="0"></td>
								<td border="0">
									<img src="images/common/spacer.gif" width="1" height="2" border="0"></td>
							</tr>
							<tr>
								<td border="0">
									<img src="images/message_home_bottom.gif" width="81" height="4" border="0"></td>
								<td border="0">
									<img src="images/common/spacer.gif" width="1" height="4" border="0"></td>
							</tr>
							<tr>
								<td bgcolor="#EFF7F8" colspan="3" border="0">
									<img src="images/common/spacer.gif" width="96" height="5" border="0"></td>
								<td border="0">
									<img src="images/common/spacer.gif" width="1" height="5" border="0"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="2" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="10" height="8" border="0"></td>
		<td>
			<table width="778" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="10" height="10"><img src="images/<?=page_img1(PG_HOME, $curr_page)?>" width="10" height="10" border="0"></td>
					<td width="2" height="1"><img src="images/common/spacer.gif" width="3" height="1" border="0"></td>
					<td width="40" height="15"><a href="mkk_index.php"><img src="images/home.gif" width="40" height="15" border="0"></a></td>
					<td width="4" height="1"><img src="images/common/spacer.gif" width="3" height="1" border="0"></td>
					<td width="10" height="10"><img src="images/<?=page_img1(PG_CONTACT, $curr_page)?>" width="10" height="10" border="0"></td>
					<td width="2" height="1"><img src="images/common/spacer.gif" width="3" height="1" border="0"></td>
					<td width="70" height="15"><a href="mkk_contact.php"><img src="images/contact.gif" width="70" height="15" border="0"></a></td>
					<td width="4"><img src="images/common/spacer.gif" width="1" height="1" border="0"></td>
					<td width="10" height="10"><img src="images/<?=page_img1(PG_MAP, $curr_page)?>" width="10" height="10" border="0"></td>
					<td width="2" height="1"><img src="images/common/spacer.gif" width="3" height="1" border="0"></td>
					<td width="60" height="15"><a href="mkk_map.php"><img src="images/map.gif" width="60" height="15" border="0"></a></td>
					
          <td width="556" align="right"><a href="mk_logout.php">ログアウト</a></td>
				</tr>
				<tr>
					<td width="778" height="2" colspan="12"><img src="images/common/spacer.gif" width="1" height="2" border="0"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="4"><img src="images/common/spacer.gif" width="10" height="24" border="0"></td>
		<td width="778" colspan="6"><img src="images/bline.gif" width="778" height="2" border="0"></td>
	</tr>
	<tr>
		<td width="778" colspan="6"><img src="images/oline.gif" width="778" height="2" border="0"></td>
	</tr>
	<tr>
		<td><a href="mkk_about.php"><img src="images/<?=page_img2(PG_KIKASETE, $curr_page)?>" width="132" height="18" border="0"></a></td>
		<td><a href="mkk_service.php"><img src="images/<?=page_img2(PG_SERVICE, $curr_page)?>" width="104" height="18" border="0"></a></td>
		<td><a href="mkk_survey.php"><img src="images/<?=page_img2(PG_SURVEY, $curr_page)?>" width="120" height="18" border="0"></a></td>
		<td><a href="mkk_hint.php"><img src="images/<?=page_img2(PG_HINT, $curr_page)?>" width="95" height="18" border="0"></a></td>
		<td><a href="mkk_kkstfrom.php"><img src="images/<?=page_img2(PG_CENTER, $curr_page)?>" width="80" height="18" border="0"></a></td>
		<td><img src="images/menu1_shim.gif" width="179" height="18" border="0"></td>
	</tr>
	<tr>
		<td width="778" colspan="11"><img src="images/bline.gif" width="778" height="2" border="0"></td>
	</tr>
</table>
<?
}

function marketer_menu(){
?>
<table width="150" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="150" border="0" cellspacing="0" cellpadding="1" bgcolor="#666699">
				<tr>
					<td align="center"><img src="images/mku_mymenu.gif" width="140" height="15" border="0"></a></td>
				</tr>
				<tr>
					<td>
						<table width="148" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
<?
if (check_permission(PMR_MY_ENQUETE)) {
?>
							<tr>
								<td>□　<a href="mkk_myenq.php">Ｍｙアンケート</a></td>
							</tr>
<?
}
if (check_permission(PMR_MY_MONITOR)) {
?>
							<tr>
								<td>□　<a href="mp_pjt_list.php">Ｍｙパートナー</a></td>
							</tr>
<?
}
if (check_permission(PMR_MY_SEARCH)) {
?>
							<tr>
								<td>□　<a href="mkk_mysearch.php">Ｍｙサーチ</a></td>
							</tr>
<?
}
if (check_permission(PMR_MY_AGENT)) {
?>
							<tr>
								<td>□　<a href="mkk_myagent.php">Ｍｙエージェント</a></td>
							</tr>
<?
}
if (check_permission(PMR_ENQ_RESULT)) {
?>
							<tr>
								<td>□　<a href="mkk_enq.php">アンケート結果</a></td>
							</tr>
<?
}
if (check_permission(PMR_REGIST_INF)) {
?>
							<tr>
								<td>□　<a href="mkk_update.php">登録情報の更新</a></td>
							</tr>
<?
}
if (check_permission(PMR_ASSISTANT)) {
?>
							<tr>
								<td>□　<a href="mkk_assistant.php">アシスタントの設定</a></td>
							</tr>
<?
}
if (check_permission(PMR_CHG_AGENT)) {
?>
							<tr>
								<td>□　<a href="mkk_achange.php">エージェントの変更</a></td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<br>
<table width="150" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="150" border="0" cellspacing="0" cellpadding="1" bgcolor="#666699">
				<tr>
					<td align="center"><img src="images/useperiod.gif" width="140" height="15" border="0"></td>
				</tr>
				<tr>
					<td>
						<table width="148" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
							<tr>
<?
// 登録日取得
$sql = "SELECT mr_regist_date FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$start_y = get_datepart('Y', $fetch[0]);
	$start_m = get_datepart('M', $fetch[0]);
}
$end_y = date('Y');
$end_m = (int)date('m');
?>
								<td align="center"><?=$start_y?>年<?=$start_m?>月〜<?=$end_y?>年<?=$end_m?>月</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
}

function page_img1($page, $current_page) {
	return ($page == $current_page) ? 'redsquare.gif' : 'square.gif';
}

function page_img2($page, $current_page) {
	switch ($page) {
	case PG_KIKASETE:
		$img = 'mk_kikasete';
		break;
	case PG_SERVICE:
		$img = 'mk_service';
		break;
	case PG_PRICE:
		$img = 'mk_price';
		break;
	case PG_REGIST:
		$img = 'mk_kregist';
		break;
	case PG_SURVEY:
		$img = 'mk_survey';
		break;
	case PG_BUSINESS:
		$img = 'mk_business';
		break;
	case PG_CENTER:
		$img = 'mk_kkstfrom';
		break;
	case PG_HINT:
		$img = 'mk_hint';
		break;
	}

	if ($page == $current_page)
		$img .= '_b';

	return $img . '.gif';
}

function marketer_footer() {
?>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="15" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" height="1"></td>
	</tr>
	<tr>
		<td>
			<div align="center">
			<table width="778" border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td align="center">
					<a href="mkk_index.php"><font size="2" class="navi2">HOME</a><font size="2" class="navi2">｜
					<a href="mkk_map.php"><font size="2" class="navi2">Site Map</a><font size="2" class="navi2">｜
					<a href="mkk_contact.php"><font size="2" class="navi2">Contact Us</a><font size="2" class="navi2">｜
					<a href="mkk_about.php"><font size="2" class="navi2">きかせて・net について</a>
					</td>
				</tr>
				<tr>
					<td align="center">
					<a href="mkk_service.php"><font size="2" class="navi2">ご提供サービス</a><font size="2" class="navi2">｜
					<a href="mkk_survey.php"><font size="2" class="navi2">きかせて・net 調べ</a><font size="2" class="navi2">｜
					<a href="mkk_kkstfrom.php"><font size="2" class="navi2">事務局より</a>
					</td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="2" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10" rowspan="3"><img src="images/common/spacer.gif" width="10" height="1"></td>
		<td colspan="2"><img src="images/bline.gif" width="778" height="3"></td>
	</tr>
	<tr>
		<td colspan="2"><img src="images/oline.gif" width="778" height="3"></td>
	</tr></table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10" rowspan="3"><img src="images/common/spacer.gif" width="10" height="1"></td>
	</tr>
	<tr>
		<td width="20%">
		<div align="left"><img src="images/kkst_color_m.gif" width="135" height="45"></div>
		</td>
		<td width="60%"><font size="2" class="copyright">Copyright (C) 2000-2004 
		<a href="http://www.xxxxxxx.co.jp/" target="_blank"><font size="2" class="copyright">xx xxxxx Inc.</a><font size="2" class="copyright">
		. All rights reserved .<br>
		このホームページに使われている画像・写真・記事等は、全て株式会社○○○○○○に帰属します。<br>
		無断で複製・再利用することは法律により禁じられています。</td>
		<td width="20%">
		<div align="left"><img src="images/human_J_color.gif" width="134" height="42"></div>
		</td>
	</tr>
</table>
</body>
</html>
<?
}

