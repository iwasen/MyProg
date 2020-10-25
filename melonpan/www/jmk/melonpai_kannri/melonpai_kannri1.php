<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガナビ管理
'******************************************************/

$title_text = $TT_melonpai_kannri;
$title_color = $TC_DOKUSHA;

if (!$info_year) {
	$info_year = date("Y");
}
if (!$info_month) {
	$info_month = date("m");
}
$yyyymm =  $info_year . sprintf("%02d", $info_month);

$sub1 = "SELECT MELONPAI_ID AS CHO_WAIT_ID, COUNT(CHO_ID) AS CHO_WAIT FROM T_CHOICE WHERE cho_flg='0' AND TO_CHAR(MODIFY_DT, 'YYYYMM')='$yyyymm' GROUP BY MELONPAI_ID";
$sub2 = "SELECT MELONPAI_ID AS CHO_END_ID, COUNT(CHO_ID) AS CHO_END FROM L_MELONPAI_LOG WHERE TO_CHAR(MODIFY_DT, 'YYYYMM')='$yyyymm' AND LOG_PRO_NO=7 GROUP BY MELONPAI_ID";
$sub3 = "SELECT LOG.MELONPAI_ID AS REC_WAIT_ID, COUNT(REC.RECOMEND_ID) AS REC_WAIT FROM T_RECOMEND REC, L_MELONPAI_LOG LOG WHERE TO_CHAR(REC.MODIFY_DT, 'YYYYMM')='$yyyymm' AND  REC.OPEN_FLG='0' AND REC.RECOMEND_ID=LOG.RECOMEND_ID GROUP BY MELONPAI_ID";
$sub4 = "SELECT MELONPAI_ID AS REC_END_ID, COUNT(RECOMEND_ID) AS REC_END FROM L_MELONPAI_LOG WHERE TO_CHAR(MODIFY_DT, 'YYYYMM')='$yyyymm' AND (LOG_PRO_NO=5 OR LOG_PRO_NO=6) GROUP BY MELONPAI_ID";
$sub5 = "SELECT MELONPAI_ID AS OSUSUME_END_ID, COUNT(LOG_NUM) AS OSUSUME_END FROM L_MELONPAI_LOG WHERE  TO_CHAR(MODIFY_DT, 'YYYYMM')='$yyyymm' AND (LOG_PRO_NO = 1 or LOG_PRO_NO = 2 ) GROUP BY MELONPAI_ID";
$sub6 = "SELECT MELONPAI_ID AS ABUNAI_END_ID, COUNT(LOG_NUM) AS ABUNAI_END FROM L_MELONPAI_LOG WHERE TO_CHAR(MODIFY_DT, 'YYYYMM')='$yyyymm' AND (LOG_PRO_NO = 3 or LOG_PRO_NO = 4 ) GROUP BY MELONPAI_ID";

$sql = "SELECT MELONPAI_ID, MELONPAI_NAME, MELONPAI_M_FLG, MELONPAI_POINT"
	. ", CHO_WAIT, CHO_END, REC_WAIT, REC_END, OSUSUME_END, ABUNAI_END"
	. " FROM M_MELONPAI LEFT JOIN (" . $sub1 . ") as CW ON MELONPAI_ID=CHO_WAIT_ID"
	. " LEFT JOIN (" . $sub2 . ") as CE ON MELONPAI_ID=CHO_END_ID"
	. " LEFT JOIN (" . $sub3 . ") as RW ON MELONPAI_ID=REC_WAIT_ID"
	. " LEFT JOIN (" . $sub4 . ") as RE ON MELONPAI_ID=REC_END_ID"
	. " LEFT JOIN (" . $sub5 . ") as OE ON MELONPAI_ID=OSUSUME_END_ID"
	. " LEFT JOIN (" . $sub6 . ") as AE ON MELONPAI_ID=ABUNAI_END_ID"
	. " ORDER BY MELONPAI_ID";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_log(id) {
	location.href = "melonpai_kannri2.php?melonpai_id=" + id;
}
function OnClick_melonpai(melonpai_id) {
  var win;
  win = window.open("../melmaga_detail/melonpai.php?melonpai_id=" + melonpai_id, "info_melonpai", "scrollbars=yes,resizable=yes,width=650,height=400");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
<table border=0 cellspacing=0 cellpadding=0 width='90%'>
	<tr>
		<td>■メルマガナビ作業状況一覧　　
			<select name="info_year"><? select_year(2000, '', date($info_year)); ?></select>年
			<select name="info_month" onchange="submit()"><? select_month('', date($info_month)); ?></select>月</td>
		<td align="right">登録者：<span id="rec_count"></span>名 <input type="button" value=" 戻る " onclick="location.href='../menu.php'"></td>
			<table border=1 cellspacing=0 cellpadding=3 width='90%'>
				<tr bgcolor="#99ccff">
					<th width="3%" align="center" colspan="1">状況</th>
					<th width="20%" align="center" colspan="1">氏名</th>
					<th width="20%" align="center" colspan="4">みつくろい</th>
					<th width="20%" align="center" colspan="4">推薦文</th>
					<th width="10%" align="center" colspan="2">オススメ</th>
					<th width="10%" align="center" colspan="2">あぶない</th>
					<th width="7%" align="center" colspan="1"><nobr>ﾎﾟｲﾝﾄ<br>残数</nobr></t>
					<th width="10%" align="center" colspan="1">詳細</th>
				</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$melonpai_id = $fetch->melonpai_id;
	$melonpai_m_flg = $fetch->melonpai_m_flg;
	$melonpai_name = $fetch->melonpai_name;
	$cho_wait = $fetch->cho_wait;
	$cho_end = $fetch->cho_end;
	$rec_wait = $fetch->rec_wait;
	$rec_end = $fetch->rec_end;
	$osusume_end = $fetch->osusume_end;
	$abunai_end = $fetch->abunai_end;
	$melonpai_point = $fetch->melonpai_point;
	if ($melonpai_m_flg == '0') {
		$melonpai_m_flg = "<font color=\"#00CC00\">●</font>";
	}
	if ($melonpai_m_flg == '1') {
		$melonpai_m_flg = "<font color=\"#FF9900\">▲</font>";
	}
	if ($melonpai_m_flg == '2') {
		$melonpai_m_flg = "<font color=\"#CC0000\">×</font>";
	}
?>
				<tr>
					<td class="tc0" align="center"><?= $melonpai_m_flg ?></td>
					<td class="tc1" align="center"><a href='JavaScript:OnClick_melonpai("<?= $melonpai_id ?>")'><?= htmlspecialchars($melonpai_name) ?></a></td>
					<td class="tc0" width="3%" align="center">待</td>
					<td class="tc1" align="right"><?= replace_zero($cho_wait) ?></td>
					<td class="n5" width="3%" align="center">済</td>
					<td class="tc1" align="right"><?= replace_zero($cho_end) ?></td>
					<td class="tc0" width="3%" align="center">待</td>
					<td class="tc1" align="right"><?= replace_zero($rec_wait) ?></td>
					<td class="n5" width="3%" align="center">済</td>
					<td class="tc1" align="right"><?= replace_zero($rec_end) ?></td>
					<td class="n5" width="3%" align="center">済</td>
					<td class="tc1" align="right"><?= replace_zero($osusume_end) ?></td>
					<td class="n5" width="3%" align="center">済</td>
					<td class="tc1" align="right"><?= replace_zero($abunai_end) ?></td>
					<td class="tc1" align="right"><?= number_format($melonpai_point) ?></td>
					<td class="tc1" align="center"><input type="button" name="shousai" value="詳細表示" onclick='JavaScript:OnClick_log("<?= $melonpai_id ?>")'></td>
				</tr>
<?php
}
?>
			</table>
		</td>
	<tr>
</table>
</form>
</center>

<SCRIPT LANGUAGE=javascript>
<!--
document.all.rec_count.innerHTML = <?= $nrow ?>;
//-->
</SCRIPT>


<? footer_jmk(0); ?>

</body>
</html>
