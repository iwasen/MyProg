#!/usr/local/bin/php4
<?
/******************************************************
' System :flette予約入力ページ
' Content:相談内容入力画面
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");
include("$inc/select.php");

// 第n希望選択
function select_choice($choice_num, $selected) {
	for ($i = 0; $i < $choice_num; $i++)
		option_tag($i, $selected, mb_convert_kana($i + 1, 'N', 'EUC-JP'));
}

// カウンセラーの性別選択
function select_sex($selected) {
	option_tag('3', $selected, '特に希望しない');
	option_tag('1', $selected, '男性');
	option_tag('2', $selected, '女性');
}

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
function onsubmit_form1(f) {
	if (f["choice[]"].length) {
		for (i = 0; i < f["choice[]"].length; i++) {
			for (j = i + 1; j < f["choice[]"].length; j++) {
				if (f["choice[]"][i].selectedIndex == f["choice[]"][j].selectedIndex) {
					alert("ご希望順位が正しく選択されていないようです。");
					f["choice[]"][j].focus();
					return false;
				}
			}
		}
	}
	return true;
}
function test() {
	document.form1.submit();
}
//-->
</script>
</head>

<body BGCOLOR="#ffffff" TEXT="#333333" MARGINWIDTH="0" MARGINHEIGHT="0" TOPMARGIN="0" LEFTMARGIN="0" onload="test()">
<form method="post" name="form1" action="con_check.php" onsubmit="return onsubmit_form1(this)">

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
			<IMG SRC="../../images/reserve_h2_02.gif" WIDTH=145 HEIGHT=36></TD>
		<TD>
			<IMG SRC="../../images/reserve_h1_03.gif" WIDTH=145 HEIGHT=36></TD>
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
	<TD bgcolor="#595959" height="24" valign="center" width="692"><span class="txt-m">&nbsp;<font color="#FFFFFF">２．相談内容入力&nbsp;&nbsp;&nbsp;</font></span><span class="txt-m"><B><font color="#FFFFFF"><?=htmlspecialchars($reserve->course)?>&nbsp;<?=htmlspecialchars($reserve->shop)?></font></B></span></TD>
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
<TD><span class="txt-m">必要事項を入力の上「次へ」ボタンをクリックしてください。</font></TD>
</TR>
</table>

<TABLE WIDTH=749 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="15"></TD>
</TR>
</table>

<TABLE WIDTH=735 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="51" height="1"></TD>
<TD bgcolor="#FFFFFF" align="center">

<TABLE WIDTH=684 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor="#595959">
<TABLE WIDTH=100% BORDER=0 CELLPADDING=4 CELLSPACING=1>

<!--第１希望-->
<TR>
<TD width="105" bgcolor="#DCDCD7" rowspan="<?=$reserve->choice_num?>">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="92">
	<TR><TD><span class="txt-m">予約日時</span></TD></TR>
	</TABLE>

</TD>
<?
for ($i = 0; $i < $reserve->choice_num; $i++) {
	$choice = &$reserve->choice[$i];
	$week = date('w', strtotime($choice->date));

	$sql = sprintf("SELECT sum(wk_sex_cd) as sex_cd"
			. " FROM t_waku"
			. " WHERE wk_room_id=%s AND wk_course_id=%s AND wk_date=%s AND wk_start_time=%s AND wk_end_time=%s",
			sql_number($choice->room_id),
			sql_number($reserve->course_id),
			sql_date($choice->date),
			sql_date($choice->start_time),
			sql_date($choice->end_time));
	$sex_cd = db_fetch1($sql);

	if ($i != 0) {
?>
<TR>
<?
	}
?>
<TD width="105" bgcolor="#FFFFFF" align="center"><span class="txt-m">第&nbsp;
<SELECT name="choice[]"><? select_choice($reserve->choice_num, $i) ?></SELECT>&nbsp;希望
</span></TD>
<TD width="105" bgcolor="#FFFFFF"><span class="txt-m"><?=$choice->date?>(<?=decode_week($week)?>)<BR><?=$choice->start_time?>〜<?=$choice->end_time?></span></TD>
<TD width="103" bgcolor="#FFFFFF"><span class="txt-m"><?=htmlspecialchars($choice->room)?></span></TD>
<TD width="255" bgcolor="#FFFFFF"><span class="txt-m">カウンセラーの性別：
<?
	if ($sex_cd == 1) {
?>
			男性<input type="hidden" name="sex[]" value="1">
<?
	} elseif ($sex_cd == 2) {
?>
			女性<input type="hidden" name="sex[]" value="2">
<?
	} else {
?>
			<select name="sex[]"><? select_sex($choice->sex) ?></select>
<?
	}
?>
</span></TD>
</TR>
<?
}
?>

<!--相談内容-->
<TR>
<TD width="105" bgcolor="#DCDCD7" rowspan="3">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="92">
	<TR><TD><span class="txt-m">相談内容</span></TD></TR>
	</TABLE>

</TD>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="576">
	<TR>
	<TD bgcolor="#DCDCD7" height="22" colspan="2"><span class="txt-14">&nbsp;<B><font color="#FD6000">■</font></B></span>
<span class="txt-m"><B><font color="#000000">お話ししたい内容について、以下に該当するものがございましたお選び下さい。</font></B></span></TD>
	</TR>
	<TR><TD colspan="2"><img src="../../images/spacer.gif" width="1" height="4"></TD></TR>
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
	<TD ><img src="../images/spacer.gif" width="1" height="10"></TD>
	</TR>
	</TABLE>
<?
		}
?>
	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="576">
	<TR>
	<TD bgcolor="#595959" height="22"><span class="txt-m">&nbsp;<font color="#FFFFFF">（<?=mb_convert_kana($count1 + 1, 'N', 'EUC-JP')?>）<?=htmlspecialchars($content)?></font></span></TD>
	<TD valign="top" bgcolor="#595959" align="right"><img src="../../images/corner_sttl_gr.gif" width="18" height="18"></TD>
	</TR>
	</TABLE>
<?
		if (is_array($counseling_ary[2][$counseling_id])) {
			$count2 = 0;
			foreach ($counseling_ary[2][$counseling_id] as $counseling_id => $content) {
?>
	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%">
	<TR>
	<TD ><img src="../../images/spacer.gif" width="1" height="5"></TD>
	</TR>
	</TABLE>
	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="576">
<?
				if ($content != '') {
?>
	<TR>
	<TD width="12" ><span class="txt-14"></span></TD>
	<TD colspan="4" width="562"><span class="txt-14">【&nbsp;<?=htmlspecialchars($content)?>&nbsp;】</span></TD>
	</TR>
<?
				}

				if (is_array($counseling_ary[3][$counseling_id])) {
					$count3 = 0;
					foreach ($counseling_ary[3][$counseling_id] as $counseling_id => $content) {
						if ($count3 % 2 == 0) {
?>
	<TR>
	<TD width="12" ><span class="txt-14"></span></TD>
<?
						}
?>
	<TD width="10"><span class="txt-m"><input type="checkbox" name="counseling_id[]" <?=value($counseling_id)?> <?=checked(in_array($counseling_id, $reserve->counseling_id))?>></span></TD>
	<TD width="263"><span class="txt-m"><?=htmlspecialchars($content)?></span></TD>
<?
						if ($count3 % 2 == 1) {
?>
	</TR>
<?
						}
						$count3++;
					}
					if ($count3 % 2 == 1) {
?>
	<TD width="10"></TD>
	<TD width="263"></TD>
	</TR>
<?
					}
				}
?>
	</TABLE>
<?
				$count2++;
			}
		}
		$count1++;
	}
}
?>
</TD>
</TR>
<TR>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="576">
	<TR>
	<TD bgcolor="#DCDCD7" colspan="2" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">■</font></B></span>
<span class="txt-m"><B><font color="#000000">上記の他、詳しくお話ししたい内容がございましたら、ご自由にお書き下さい。</font></B></span></TD>
	</TR>
	<TR><TD colspan="2"><img src="../../images/spacer.gif" width="1" height="1"></TD></TR>
	<TR>
	<TD width="4%"><span class="txt-14"></span></TD>
	<TD width="96%"><span class="txt-m"><textarea name="comment" wrap="soft" cols="50" rows=5><?=htmlspecialchars($reserve->comment)?></textarea></span></TD>
	</TR>
	</TABLE>

</TD>
</TR>
<TR>
<TD colspan="4" bgcolor="#FFFFFF">

	<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 width="576">
	<TR>
	<TD bgcolor="#DCDCD7" height="22"><span class="txt-14">&nbsp;<B><font color="#FD6000">■</font></B></span>
<span class="txt-m"><B><font color="#000000">以前にフレッテをご利用のお客様のみご回答ください。</font></B></span></TD>
	</TR>
	</TABLE>

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 width="576">
	<TR>
	<TD colspan="2" nowrap width="571"><span class="txt-m">フレッテを以前にご利用されたことのある方は、
前回のセッションを行ったカウンセラーを担当とさせて頂いております。今回、別のカウンセラーをご希望の場合は下記にチェックをお願いいたします。<BR>
（チェックがない場合は、前回と同じカウンセラーを勧めさせて頂きます。）<BR></span></TD>
	</TR>
	<TR>
	<TD width="14"><span class="txt-m"><input type="checkbox" name="change" <?=value_checked(1, $reserve->change)?>></span></TD>
	<TD width="550"><span class="txt-m">別のカウンセラーを希望する<BR></span></TD>
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
<TD><input type="image" src="../../images/btn_next.gif" width="100" height="30" border="0" alt="次へ"></TD>
<TD><img src="../../images/spacer.gif" width="15" height="30" border="0" ></TD>
<TD><a HREF="sche_form.php"><img src="../../images/btn_back.gif" width="100" height="30" border="0" alt="戻る"></a></TD>
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
