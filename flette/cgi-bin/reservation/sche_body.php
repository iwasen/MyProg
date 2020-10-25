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

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('timeout.php');

// 当日
$now = getdate();
$today = mktime(0, 0, 0, $now['mon'], $now['mday'], $now['year']);

// 翌々日
$date2 = $today + DAY_SEC * 2;
?>
<HTML lang="ja">
<HEAD>
<TITLE>FLETTE / ご予約</TITLE>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="../../css/flette.css" type="text/css">
<script language="JavaScript" src="../../js/fjs.js" type="text/JavaScript"></script>
</head>

<body BGCOLOR="#ffffff" TEXT="#333333" MARGINWIDTH="0" MARGINHEIGHT="0" TOPMARGIN="0" LEFTMARGIN="0">

<!--main start-->

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="3"></TD>
</TR>
</table>

<TABLE WIDTH=740 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="30" height="1"></TD>
<TD bgcolor="#FFFFFF" align="center">

<TABLE WIDTH=710 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor="#595959">
<TABLE WIDTH=100% BORDER=0 CELLPADDING=2 CELLSPACING=1>
<?
// 部屋情報取得
$sql = "SELECT rm_room_id,rm_room_name,rm_room_cd FROM m_room WHERE rm_shop_id=$reserve->shop_id AND rm_status=1 ORDER BY rm_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$room_ary[$i] = pg_fetch_object($result, $i);
}

// 枠情報取得
$sql = "SELECT wk_room_id,to_char(wk_date,'DD') AS wk_day,wk_start_time,wk_end_time,sum(wk_sex_cd) AS wk_sex_cd2"
		. " FROM t_waku"
		. " JOIN m_room ON rm_room_id=wk_room_id"
		. " WHERE rm_shop_id=$reserve->shop_id AND rm_status=1 AND wk_course_id=$reserve->course_id AND to_char(wk_date,'YYYYMM')=" . sql_char(sprintf('%04d%02d', $reserve->year, $reserve->month))
		. " GROUP BY wk_room_id,wk_date,wk_start_time,wk_end_time"
		. " ORDER BY wk_date,wk_start_time";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$start_time = substr($fetch->wk_start_time, 0, 5);
	$end_time = substr($fetch->wk_end_time, 0, 5);

	$sex = '';
	if ($fetch->wk_sex_cd2 & 1)
		$sex .= '<img src="../../images/m.gif" border="0" height="9" height="9">';
	if ($fetch->wk_sex_cd2 & 2)
		$sex .= '<img src="../../images/w.gif" border="0" height="9" height="9">';

	$waku_ary[(int)$fetch->wk_day][$fetch->wk_room_id][$i]->start_time = $start_time;
	$waku_ary[(int)$fetch->wk_day][$fetch->wk_room_id][$i]->end_time = $end_time;
	$waku_ary[(int)$fetch->wk_day][$fetch->wk_room_id][$i]->sex = $sex;
}

$d = strtotime("$reserve->year/$reserve->month/1");
$st = $d - date('w', $d) * DAY_SEC;
$ed = strtotime("$reserve->year/" . ($reserve->month + 1) . "/1");

for ($i = 0, $dw = $st; $dw < $ed; $i++, $dw += DAY_SEC * 7) {
	if ($i % 2 == 0) {
?>
<!--曜日-->
<TR>
<TD width="29" bgcolor="#DCDCD7"><span class="txt-m"></span></TD>
<TD width="96" bgcolor="#FFE6E6" align="center"><span class="txt-s">日</span></TD>
<TD width="96" bgcolor="#DCDCD7" align="center"><span class="txt-s">月</span></TD>
<TD width="96" bgcolor="#DCDCD7" align="center"><span class="txt-s">火</span></TD>
<TD width="96" bgcolor="#DCDCD7" align="center"><span class="txt-s">水</span></TD>
<TD width="96" bgcolor="#DCDCD7" align="center"><span class="txt-s">木</span></TD>
<TD width="96" bgcolor="#DCDCD7" align="center"><span class="txt-s">金</span></TD>
<TD width="96" bgcolor="#DBEDFF" align="center"><span class="txt-s">土</span></TD>
</TR>
<?
	}
?>
<!--week start-->
<TR>
<TD width="29" bgcolor="#F3F3DC"><span class="txt-m"></span></TD>
<?
	for ($j = 0, $dd = $dw; $j < 7; $j++, $dd += DAY_SEC) {
		if (date('m', $dd) == $reserve->month) {
			if ($dd == $today) {
?>
<TD width="96" bgcolor="#FFFF99" align="center"><span class="txt-m"><B><?=(int)date('d', $dd)?></B></span></TD>
<?
			} else {
?>
<TD width="96" bgcolor="#F3F3DC" align="center"><span class="txt-m"><B><?=(int)date('d', $dd)?></B></span></TD>
<?
			}
		} else {
?>
<TD width="96" bgcolor="#F3F3DC" align="center"></TD>
<?
		}
	}
?>
</TR>
<?
	for ($j = 0; $j < count($room_ary); $j++) {
		$room_id = $room_ary[$j]->rm_room_id;
?>
<TR>
<TD width="29" bgcolor="#DCDCD7" align="center"><span class="txt-m"><B><?=$room_ary[$j]->rm_room_cd?></B></span></TD>
<?
		for ($k = 0, $dd = $dw; $k < 7; $k++, $dd += DAY_SEC) {
			if (date('m', $dd) == $reserve->month) {
				if ($dd == $today) {
?>
<TD width="96" bgcolor="#ffff99"><span class="txt-s"><br><br></span></TD>
<?
				} elseif ($dd < $date2) {
?>
<TD width="96" bgcolor="#EEEEEE"><span class="txt-s"><br><br></span></TD>
<?
				} else {
?>
<TD width="96" bgcolor="#FFFFFF" valign="top"><span class="txt-s">
<?
					$day = (int)date('d', $dd);
					if (is_array($waku_ary[$day][$room_id])) {
						foreach ($waku_ary[$day][$room_id] as $waku) {
?>
<a href="javascript:void(0)" onclick="parent.head.reserve(<?=$room_id?>,'<?=date('Y/m/d', $dd)?>','<?=$waku->start_time?>','<?=$waku->end_time?>')" class="link-txt"><?=$waku->start_time?>〜<?=$waku->end_time?>&nbsp;<?=$waku->sex?></a><BR>
<?
						}
					}
?>
</span></TD>
<?
				}
			} else {
?>
<TD width="96" bgcolor="#FFFFFF" valign="top"><span class="txt-s">
<?
			}
	}
?>
</TR>
<?
	}
}
?>
<!--week end-->

</table>
</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="10"></TD>
</TR>
</table>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD height="30" valign="top" align="left">

	<TABLE WIDTH=740 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TD align="right"><A HREF="javascript:parent.close();"><img src="../../images/btn_close.gif" width="70" height="20" border="0" alt="ウインドウを閉じる"></A></TD>
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
