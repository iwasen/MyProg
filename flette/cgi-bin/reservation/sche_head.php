#!/usr/local/bin/php4
<?
/******************************************************
' System :flette予約入力ページ
' Content:予約日時選択画面
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");

// 希望枠編集
function choice_value(&$reserve, $index) {
	$choice = &$reserve->choice[$index];
	if ($choice->room_id == 0)
		return '';
	else
		return "$choice->room_id,$choice->date,$choice->start_time,$choice->end_time";
}

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('timeout.php');

// 当日
$now = getdate();
$today = mktime(0, 0, 0, $now['mon'], $now['mday'], $now['year']);

// 部屋情報取得
$sql = "SELECT rm_room_id,rm_room_name,rm_room_cd,rm_link_url"
		. " FROM m_room"
		. " WHERE rm_shop_id=" . sql_number($reserve->shop_id)
		. " ORDER BY rm_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
$room_name_ary = array();
for ($i = 0; $i < $nrow; $i++) {
	$room_ary[] = pg_fetch_object($result, $i);
}
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
function erase() {
	var f = document.form1;
	for (i = 0; i < 3; i++)
		f["choice[]"][i].value = "";
	set_select();
}
function reserve(room_id, date, start_time, end_time) {
	var f = document.form1;
	var p = room_id + "," + date + "," + start_time + "," + end_time;
	for (i = 0; i < 3; i++) {
		if (f["choice[]"][i].value == p)
			return;
		if (f["choice[]"][i].value == "")
			break;
	}
	if (i < 3) {
		f["choice[]"][i].value = p;
		set_select();
	} else
		alert("予約希望日時は３つまで選択できます。");
}
function set_select() {
	var f = document.form1;
	var r = Array();
<?
foreach ($room_ary as $fetch) {
?>
	r[<?=$fetch->rm_room_id?>] = "<?=$fetch->rm_room_name?>";
<?
}
?>
	for (i = 0; i < 3; i++) {
		if (f["choice[]"][i].value != "") {
			a = f["choice[]"][i].value.split(",");
			d = new Date(a[1]);
			w = new Array("日", "月", "火", "水", "木", "金", "土");
			f.select.options[i].text = d.getYear() + "年" + (d.getMonth() + 1) + "月" + d.getDate() + "日（" + w[d.getDay()] + "） " + a[2] + "〜" + a[3] + "　" + r[a[0]];
		} else
			f.select.options[i].text = "";
	}
}
function change_ym() {
	var f = document.form1;
	if (f.ym.selectedIndex != 0) {
		f.next.value = "sche_form.php";
		f.submit();
	}
}
function appl() {
	var f = document.form1;
	f.next.value = "con_form.php";
	f.submit();
}
function test() {
	reserve(1,'2004/06/06','00:05','02:05');
	appl();
}
//-->
</script>
</head>

<body BGCOLOR="#ffffff" TEXT="#333333" MARGINWIDTH="0" MARGINHEIGHT="0" TOPMARGIN="0" LEFTMARGIN="0" onload="set_select();test()">

<form method="post" name="form1" target="_top" action="sche_check.php">
<input type="hidden" name="year" <?=value($reserve->year)?>>
<input type="hidden" name="month" <?=value($reserve->month)?>>
<input type="hidden" name="choice[]" <?=value(choice_value($reserve, 0))?>>
<input type="hidden" name="choice[]" <?=value(choice_value($reserve, 1))?>>
<input type="hidden" name="choice[]" <?=value(choice_value($reserve, 2))?>>
<input type="hidden" name="next">

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 >
<TR>
<TD align="left" background="../../images/header_bg.gif"><img src="../../images/header.gif" width="750" height="30" border="0"></TD>
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
			<IMG SRC="../../images/reserve_h2_01.gif" WIDTH=145 HEIGHT=36></TD>
		<TD>
			<IMG SRC="../../images/reserve_h1_02.gif" WIDTH=145 HEIGHT=36></TD>
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
	<TD bgcolor="#595959" height="24" valign="center" width="692"><span class="txt-m">&nbsp;<font color="#FFFFFF">１．予約日時選択&nbsp;&nbsp;&nbsp;</font></span><span class="txt-m"><B><font color="#FFFFFF"><?=htmlspecialchars($reserve->course)?>&nbsp;<?=htmlspecialchars($reserve->shop)?></font></B></span></TD>
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

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width="740">
<TR>
<TD rowspan="2"><img src="../../images/spacer.gif" width="40" height="1" border="0" ></TD>
<TD colspan="2"><span class="txt-m">ご予約希望の日時をお選びの上、「次へ」ボタンをクリックしてください。<BR>
ご希望日時は<B>３つまで選択可能</B>です。表示されている時間帯が空き時間となりますのでご選択ください。<BR></font></TD>
</TR>
<TR>
<TD><span class="txt-m">▼選択した日時が表示されます。</span><BR>
<span class="txt-m">
<SELECT name="select" wrap="soft" cols="70" size=3 style="width:340">
	<OPTION class="txt-m"></OPTION>
	<OPTION class="txt-m"></OPTION>
	<OPTION class="txt-m"></OPTION></SELECT></span></TD>
<TD valign="bottom" align="right">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
	<TD colspan="4" align="center"><span class="txt-m"><font color="#CC0000"><?=$reserve->error['reserve_select']?></font></span></TD>
	</TR>
	<TR>
	<TD><img src="../../images/spacer.gif" width="10" height="30" border="0" ></TD>
	<TD><a href="javascript:void(0)" onclick="appl()"><img src="../../images/btn_next.gif" width="100" height="30" border="0" alt="次へ"></a></TD>
	<TD><img src="../../images/spacer.gif" width="15" height="30" border="0" ></TD>
	<TD><a href="javascript:void(0)" onclick="erase()"><img src="../../images/btn_clear.gif" width="100" height="30" border="0" alt="クリア"></a></TD>
	</TR>
	</TABLE>

</TD>
</TR>
</TABLE>

<TABLE WIDTH=750 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="10"></TD>
</TR>
</table>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD valign="left">

<TABLE WIDTH=740 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor="#FFFFFF"><img src="../../images/spacer.gif" width="30" height="1"></TD>
<TD bgcolor="#595959" valign="center">

	<TABLE WIDTH=710 BORDER=0 CELLPADDING=0 CELLSPACING=1>
	<TR><TD>

	<TABLE WIDTH=100% BORDER=0 CELLPADDING=4 CELLSPACING=0>
	<TR>
	<TD width="40%" bgcolor="#DCDCD7"></TD>
	<TD align="center" valign="center" width="20%" bgcolor="#F3F3DC"><B><span class="txt-14"><?=$reserve->year?>年&nbsp;<?=$reserve->month?>月</span></B></TD>
	<TD width="40%" align="right"bgcolor="#DCDCD7">
		<span class="txt-s">
		<SELECT NAME="ym" onchange="change_ym()">
		<OPTION value="">--- 年月をお選びください ---</OPTION>
<?
for ($i = 0, $y = (int)date('Y', $today), $m = (int)date('n', $today); $i < 3; $i++) {
	if ($y != $reserve->year || $m != $reserve->month) {
?>
		<OPTION value="<?=$y?>/<?=$m?>"><?=$y?>年<?=$m?>月</OPTION>

<?
	}
	if (++$m > 12) {
		$m = 1;
		$y++;
	}
}
?>
		</SELECT>
		</SPAN>
	</TD>
	</TR>
	</TABLE>

	</TD>
	</TR>
	</TABLE>

</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="5"></TD>
</TR>
</table>

<TABLE WIDTH=740 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="30" height="1"></TD>
<TD bgcolor="#FFFFFF" align="center">

<TABLE WIDTH=710 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor="#595959">
<TABLE WIDTH=100% BORDER=0 CELLPADDING=3 CELLSPACING=1>
<TR>
<TD bgcolor="#FFFFFF" height="22"><span class="txt-s">
<?
foreach ($room_ary as $fetch) {
?>
&nbsp;<B><?=$fetch->rm_room_cd?></B>&nbsp;：&nbsp;<a href="Javascript:;" onClick="MM_openBrWindow('<?=$fetch->rm_link_url?>','room<?=$fetch->rm_room_id?>','width=590,height=460')" class="link-txt"><?=htmlspecialchars($fetch->rm_room_name)?></a>
&nbsp;
<?
}
?>
</span></TD>
</TR>
<TR>
<TD bgcolor="#FFFFFF" height="22"><span class="txt-s">
&nbsp;<img src="../../images/m.gif" border="0" height="9" height="9">：男性カウンセラーが在室です。&nbsp;
<img src="../../images/w.gif" border="0" height="9" height="9">：女性カウンセラーが在室です。</span></TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>
</form>

</BODY>
</HTML>
