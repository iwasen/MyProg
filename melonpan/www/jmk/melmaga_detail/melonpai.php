<?
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガナビ情報表示画面
'******************************************************/

$title_text = "メルマガナビ情報表示";
$title_color = $TC_MASTER;

$sql ="SELECT MELONPAI_ID, MELONPAI_PSWD, MELONPAI_NAME, MELONPAI_NIC, MELONPAI_MAIL_ADDR, MELONPAI_ZIP1, MELONPAI_ZIP2, MELONPAI_ADD, MELONPAI_TEL, MELONPAI_M_FLG, MELONPAI_POINT";
$sql = $sql . ", TO_CHAR(modify_dt, 'YYYY') as modi_year";
$sql = $sql . ", TO_CHAR(modify_dt, 'MM') as modi_month";
$sql = $sql . ", TO_CHAR(modify_dt, 'DD') as modi_day"; 
$sql = $sql . " FROM M_MELONPAI";
$sql = $sql . " WHERE MELONPAI_ID = $melonpai_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_pswd = $fetch->melonpai_pswd;
	$melonpai_name = $fetch->melonpai_name;
	$melonpai_nic = $fetch->melonpai_nic;
	$melonpai_mail_addr = $fetch->melonpai_mail_addr;
	$melonpai_zip1 = $fetch->melonpai_zip1;
	$melonpai_zip2 = $fetch->melonpai_zip2;
	$melonpai_add = $fetch->melonpai_add;
	$melonpai_tel = $fetch->melonpai_tel;
	$melonpai_m_flg = $fetch->melonpai_m_flg;
	$melonpai_point = $fetch->melonpai_point;
	$modi_year = $fetch->modi_year;
	$modi_month = $fetch->modi_month;
	$modi_day = $fetch->modi_day;

	if ($melonpai_nic == "") {
		$melonpai_nic = "<br>";
	}
	if ($melonpai_add == "") {
		$melonpai_add = "<br>";
	}
	if ($melonpai_tel == "") {
		$melonpai_tel = "<br>";
	}
	if ($melonpai_m_flg == "0") {
		$melonpai_m_flg = "活動中";
	}
	if ($melonpai_m_flg == "1") {
		$melonpai_m_flg = "休止中";
	}
	if ($melonpai_m_flg == "2") {
		$melonpai_m_flg = "停止";
	}

}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title>メルマガナビ情報</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>
<form method="post" name="form1" action="kanri_suuji2.php">
	<center>
		<table width='550'>
			<tr>
				<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
				</td>
			</tr>
			<tr>
				<td>■メルマガナビ登録情報</td>
			</tr>
		</table>
		<table width='550' border="1" CELLSPACING="0" CELLPADDING="3">
			<tr>
				<td width="35%" class="h6">氏　　名</td>
				<td><?= htmlspecialchars($melonpai_name) ?></td>
			</tr>
			<tr>
				<td class="h6"><nobr>メールアドレス</nobr></td>
				<td><?= replace_br(htmlspecialchars($melonpai_mail_addr)) ?></td>
			</tr>
			<tr>
				<td class="h6"><nobr>パスワード</nobr></td>
				<td><?= htmlspecialchars($melonpai_pswd) ?></td>
			</tr>
			<tr>
				<td class="h6"><nobr>ニックネーム</nobr></td>
				<td><?= replace_br(htmlspecialchars($melonpai_nic)) ?></td>
			</tr>
			<tr>
				<td class="h6">郵便番号</td>
				<td><?= $melonpai_zip1 ?>-<?= $melonpai_zip2 ?></td>
			</tr>
			<tr>
				<td class="h6">住　　所</td>
				<td><?= replace_br(htmlspecialchars($melonpai_add)) ?></td>
			</tr>
			<tr>
				<td class="h6">電話番号</td>
				<td><?= replace_br($melonpai_tel) ?></td>
			</tr>
			<tr>
				<td class="h6">活動状態</td>
				<td><?= $melonpai_m_flg ?></td>
			</tr>
			<tr>
				<td class="h6">ポイント</td>
				<td><?= number_format($melonpai_point) ?> ポイント</td>
			</tr>
			<tr>
				<td class="h6">登録日</td>
				<td name="modify_dt"><? print $modi_year . "年" . $modi_month . "月" . $modi_day . "日"; ?></td>
			</tr>
		</table><br>
	<input type="button" value="　閉じる　" onclick="window.close()"></form>
	</center>
</form>
</body>
</html>
