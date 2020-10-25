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
$sql = "SELECT T.MAIL_ADDR,"
	. "to_char(M.MODIFY_DT, 'YYYY') as MF_YEAR,"
	. "to_char(M.MODIFY_DT, 'MM') as MF_MONTH,"
	. "to_char(M.MODIFY_DT, 'DD') as MF_DAY,"
	. "M.READER_PSWD,"
	. "to_char(READER_BIRTH, 'YYYY') as WK_YEAR,"
	. "to_char(READER_BIRTH, 'MM') as WK_MONTH,"
	. "to_char(READER_BIRTH, 'DD') as WK_DAY,"
	. "M.READER_SEX,"
	. "M.READER_MRG,"
	. "M.READER_ZIP1,"
	. "M.READER_ZIP2,"
	. "MZ.KEN,"
	. "MO.OCCUP,"
	. "M.PT_MELONPAN"
	. " FROM T_MAILADDR T, M_MELONPA M, M_MAG_OCCUP MO, M_ZIPCODE MZ"
	. " WHERE M.MAIL_ADDR = '$mail_addr' AND T.mail_addr = M.mail_addr AND"
	. " M.READER_OCCUP = MO.OCCUP_ID AND M.READER_ZIP1 = MZ.ZIPCODE";

$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, $i);
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
<title>Mｙめろんぱん情報</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>
<center>
<form method="post" name="form1" method="post">
<table width="400"><br>
	<tr>
		<td class="m0">■Myめろんぱん登録情報
			<table width="400" border="0" cellpadding="5" cellspacing="1">
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">Myめろんぱん登録日</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $mf_year . "年" . sprintf("%2d", $mf_month) . "月" . sprintf("%2d", $mf_day) . "日" ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">メールアドレス</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= htmlspecialchars($mail_addr) ?></td>
				</tr>
				<tr>
					<td width="120" bgcolor="#FFCC33" class="text9ptnoColor"><b><font color="#336600">パスワード</font></b></td>
					<td width="200" bgcolor="#FFFFCC"> <?= $reader_pswd ?></td>
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
					<td width="200" bgcolor="#FFFFCC"> <?= number_format($pt_melonpan) ?>　ポイント</td>
				</tr>
			</table>
		</td>
	</tr>
<?
} else {
	print "<center><br>■該当データが見つかりません。<br>";
}
?>

</table><br>
	<input type="button" value="　閉じる　" onclick="window.close()"></form>
</center>
</body>
</html>

