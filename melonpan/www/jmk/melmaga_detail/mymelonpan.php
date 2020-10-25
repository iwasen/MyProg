<?php
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:Myめろんぱん情報表示
'******************************************************/
$mail_addr = trim ($mail_addr);

$sql = "SELECT M.MAIL_ADDR,"
  . "to_char(M.MODIFY_DT, 'YYYY') as MF_YEAR,"
  . "to_char(M.MODIFY_DT, 'MM') as MF_MONTH,"
  . "to_char(M.MODIFY_DT, 'DD') as MF_DAY,"
	. "M.READER_PSWD,"
  . "to_char(READER_BIRTH, 'YYYY') as WK_YEAR,"
  . "to_char(READER_BIRTH, 'MM') as WK_MONTH,"
	. "to_char(READER_BIRTH, 'DD') as WK_DAY,"
	. "M.MAIL_ADDR,"
	. "M.READER_SEX,"
	. "M.READER_MRG,"
	. "M.READER_ZIP1,"
	. "M.READER_ZIP2,"
	. "MZ.KEN,"
	. "MO.OCCUP,"
	. "M.PT_MELONPAN"
	. " FROM M_MELONPA M, M_MAG_OCCUP MO, M_ZIPCODE MZ"
	. " WHERE M.reader_id = $reader_id AND M.READER_OCCUP = MO.OCCUP_ID AND M.READER_ZIP1 = MZ.ZIPCODE";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_addr = $fetch->mail_addr;
	$mf_year = $fetch->mf_year;
	$mf_month = $fetch->mf_month;
	$mf_day = $fetch->mf_day;
	$reader_pswd = $fetch->reader_pswd;
	$wk_year = $fetch->wk_year;
	$wk_month = $fetch->wk_month;
	$wk_day = $fetch->wk_day;
	$reader_sex = $fetch->reader_sex;
	$reader_mrg = $fetch->reader_mrg;
	$reader_zip1 = $fetch->reader_zip1;
	$reader_zip2 = $fetch->reader_zip2;
	$ken = $fetch->ken;
	$occup = $fetch->occup;
	$pt_melonpan = $fetch->pt_melonpan;

	if ($reader_sex == 1) {
		$seibetsu = "男性";
	} elseif ($reader_sex == 2){
		$seibetsu = "女性";
	} else {
		$seibetsu = " - ";
	}
	if ($reader_mrg == 1) {
		$mikikon = "独身";
	} elseif ($reader_mrg == 2)  {
		$mikikon = "既婚";
	} else {
		$mikikon = " - ";
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>Myめろんぱん情報</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=650,height=700");
  win.focus();
}
//-->
</SCRIPT>

</head>
<body>
<form method="post" name="form1" method="post" style="margin:0">
<center>
<table width="400">
	<tr>
		<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small>
		</td>
	</tr>
	<tr>
		<td>■Myめろんぱん登録情報
			<table width="400" border="1" cellpadding="3" cellspacing="0">
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">Myめろんぱん登録日</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?=  $mf_year . "年" . sprintf("%2d", $mf_month) . "月" . sprintf("%2d", $mf_day) . "日" ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">メールアドレス</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= htmlspecialchars($mail_addr) ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">パスワード</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= htmlspecialchars($reader_pswd) ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">生年月日</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?=  $wk_year . "年" . sprintf("%2d", $wk_month) . "月" . sprintf("%2d", $wk_day) . "日" ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">性　別</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $seibetsu ?> </td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">未既婚</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $mikikon ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">居住地</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $reader_zip1 . "-" . $reader_zip2 . " " . $ken ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">職　業</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $occup ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">ポイント</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $pt_melonpan ?>　ポイント</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="400"><br>
	<tr>
		<td　colspan=2>■購読メルマガ</td>
	</tr>
	<tr>
		<td bgcolor="#FFCC33" align="center"><font size=-1><nobr>ﾒﾙﾏｶﾞID</nobr></font></td>
		<td bgcolor="#FFCC33" align="center"><font size=-1><nobr>メルマガ名</nobr></font></td>
	</tr>
<?
	$sql = "SELECT TM.mag_id, MM.mag_nm FROM T_MAILADDR TM,M_MAGAZINE MM WHERE TM.mail_addr='$mail_addr' AND TM.mag_id=MM.mag_id ORDER BY mag_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
?>
	<tr>
		<td bgcolor="#FFFFCC" align="center"><font size=-1><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= $mag_id ?></a></font></td>
		<td bgcolor="#FFFFCC" align="left"><font size=-1><?= htmlspecialchars($mag_nm) ?></font></td>
	</tr>
<?
	}
} else {
	print "<center><br>■該当データが見つかりません。<br>";
}
?>

</table>
	<input type="button" value="閉じる" onclick="window.close()">
</center>
</form>
</body>
</html>

