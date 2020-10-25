<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:先月の実績入力完了
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/point.php");
include("$inc/cnv_eng_co2.php");
include("$inc/cnv_unit.php");
include("$inc/inp_close.php");
include("$inc/rank.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");
include("$inc/foot_print.php");

// セッション処理
if (!isset($_SESSION['ss_result_input']))
	redirect('result_input.php');
$reg = &$_SESSION['ss_result_input'];

// デイリーデータ集計
$reg->daily_count($_SESSION['ss_seq_no']);

// 「今月は入力しない」データをゼロに
if ($reg->mb_gm_flag == 0 || $reg->gm_inp_cd != '')
	$reg->gm_use = 0;
if ($reg->mb_ol_flag == 0 || $reg->ol_inp_cd != '') {
	$reg->ol_use = 0;
	$reg->ol_sum = 0;
}
if ($reg->mb_gl_flag == 0 || $reg->gl_inp_cd != '') {
	$reg->gl_use = 0;
	$reg->gl_sum = 0;
}

// データ保存
$bd_seq_no = $reg->save_db($_SESSION['ss_seq_no'], 1);

// 入力完了処理（ランク決定、ポイント付与）
inp_close($bd_seq_no, 1);

// 未完了フラグ変更
$_SESSION['ss_yet_flag'] = 0;

// トップページ情報変更
$sql = "SELECT max(cr_ym) AS max_date FROM t_co2_rank WHERE cr_seq_no=" . sql_number($_SESSION['ss_seq_no']);
$result = db_exec($sql);
if (pg_numrows($result) != 0) {
	$fetch = pg_fetch_object($result, 0);

	$date = $fetch->max_date;
	if ($date != null) {
		$_SESSION['ss_cr_ym'] = $date;
		$_SESSION['ss_cr_m'] = get_datepart('M', $date);

		get_foot_print($_SESSION['ss_seq_no'], $date, $use, $rank);
		$_SESSION['ss_rank'] = $rank['co2'];
		$_SESSION['ss_ele_rank'] = $rank['ele'];
		$_SESSION['ss_gas_rank'] = $rank['gas'];
		$_SESSION['ss_wtr_rank'] = $rank['wtr'];
		$_SESSION['ss_dst_rank'] = $rank['dst'];
		$_SESSION['ss_oil_gso_rank'] = $rank['oil_gso'];
	}
}

// セッション変数削除
unset($_SESSION['ss_result_input']);
session_unregister('ss_result_input');
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="99%">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
		<br>
		</td>
		<td width="35" align="right" valign="top"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>
		<td align="center" valign="top">
			<table width="90%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center" valign="middle">
					<img src="img/result_input/result_fix.gif" border="0" width="620" height="405">
					</td>
				</tr>
			</table>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><a href="anime.php"><img src="img/button/anime.gif" border="0" width="92" height="30"></a></td>
					<td width="10"><br></td>
					<td><a href="main.php"><img src="img/button/main.gif" border="0" width="61" height="30"></a></td>
				</tr>
				<tr align="center">
					<td height="7" colspan="3"><br></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</div>

</body>
</html>