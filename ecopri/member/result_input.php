<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:実績入力
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");

// ゼロ→null
function zero_to_null($num) {
	if ($num == 0)
		return '';
	else
		return $num;
}

// commitフラグをチェックして表示を変える
function commit_check($column, $column_name, $flag, $size = null, $maxlength = null) {
	if ($flag)
		echo number_format($column);
	else
		echo "<input type=\"text\" size=$size maxlength=$maxlength name=\"$column_name\" " . value($column) . ">";
}

// 設定ページ表示確認
$sql = "SELECT mb_seq_no FROM t_member where mb_gm_flag IS NULL AND mb_seq_no=" . $_SESSION['ss_seq_no'];
if (db_fetch1($sql))
	redirect('result_input_set.php');

// セッション処理
if (!$_SESSION['ss_result_input']) {
	$_SESSION['ss_result_input'] = new result_input;
$reg = &$_SESSION['ss_result_input'];

} else
	$reg = &$_SESSION['ss_result_input'];

// 処理月取得
$reg->get_ym();

// データ読み込み
$reg->read_db();

// 会員の情報取得
$reg->get_member_info();

// デイリーデータ集計
$reg->daily_count($_SESSION['ss_seq_no']);

?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form name="form1" method="post" action="result_input_check.php">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
		<br>
			<table border="0" align="center">
				<tr align="center" valign="middle">
					<td align="center" align="center" width=90><img src="img/year/input/year/<?=$reg->bd_y?>.gif" border="0" width=128 height=28></td>
					<td align="center" width=63><img src="img/year/input/month/<?=sprintf("%02d", $reg->bd_m)?>.gif" border="0" width=56 height=28></td>
					<td align="center" width=143><img src="img/year/input/nyuuryoku.gif" border="0" width=129 height=28></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">
<?
if ($reg->commit_flag == 0) {
?>
			<table width="599" border=0 cellspacing="3" cellpadding="2">
				<tr>
					<td align="center">
					<font size=2>
					<font color="blue"><b><?=(int)$reg->bd_m?>月</font><font color="navy">に届いた検針票の値をご入力ください。</font><font color="deeppink">（検針月で集計しております）</b></font>
					</td>
				</tr>
			</table>
<?
}
$bd_ym = $reg->bd_y."-".sprintf("%02d", $reg->bd_m)."-01";
if ($reg->commit_flag == 0 && $bd_ym == $reg->sys_ym && $bd_ym == add_date(date("Y-m-01"), 0, -1, 0)) {
?>
			<table width="599" border="1" cellspacing="3" cellpadding="4" bordercolor="#FFCCFF">
				<tr>
					<td width ="50" bgcolor="#CCFFFF"><img src="img/result_input/eco_zou.gif" border="0" width="50" height="30"></td>
					<td bgcolor="#FFCCFF" width="528">
					<font color="#FF0066" size=2>
					&nbsp;<font color="red"><b>月が変わりました。<br>
					&nbsp;<?=(int)$reg->bd_m?>月分のデータを全て入力して"10日"までに集計ボタンを押してください。</b></font>
					</font>
					</td>
				</tr>
			</table>
<?
}
?>
			<table border=0 cellspacing=0 cellpadding=0 width=599>
				<tr>
					<td align="right"><a href="result_input_set.php"><img src="img/result_input/energy.gif" border=0 alt="入力項目の設定変更"></a></td>
				</tr>
			</table>
			<table border="0" width="500" bgcolor="#bfddf2">
				<tr>
					<td align="center" colspan=4 height=60><img src="img/result_input/title01.gif" width=465 height=40></td>
				</tr>
<?
// 電気入力
?>
				<tr>
					<td align="right" width=150><img src="img/character/icon_denki.gif" width="140" height="51"></td>
					<td align="right" width=120><?=commit_check($reg->el_use,'el_use',$reg->commit_flag,8,4)?>&nbsp;kwh&nbsp;&nbsp;</td>
					<td align="right" width=160><?=commit_check($reg->el_sum,'el_sum',$reg->commit_flag,10,6)?>&nbsp;円</td>
					<td width=70></td>
				</tr>
<?
// ガス入力
if ($reg->mb_gas_kind_cd != 3) {
?>
				<tr>
					<td align="right"><img src="img/character/icon_gas.gif" width="140" height="51"></td>
					<td align="right"><?=commit_check($reg->gs_use,'gs_use',$reg->commit_flag,8,4)?>&nbsp;m3&nbsp;&nbsp;&nbsp;</td>
					<td align="right"><?=commit_check($reg->gs_sum,'gs_sum',$reg->commit_flag,10,6)?>&nbsp;円</td>
					<td></td>
				</tr>
<?
}
// 水道入力
if ((($reg->bd_m % 2) == ($reg->mb_wt_month % 2) && ($reg->commit_flag == 0)) || ($reg->commit_flag == 1)) {
?>
				<tr>
					<td align="right" rowspan=2><img src="img/character/icon_suidou1.gif" width="140" height="51"></td>
					<td align="right"><?=commit_check($reg->wt_use,'wt_use_input',$reg->commit_flag,8,4)?>&nbsp;m3&nbsp;&nbsp;&nbsp;</td>
					<td align="right"><?=commit_check($reg->wt_sum,'wt_sum_input',$reg->commit_flag,10,6)?>&nbsp;円</td>
					<td></td>
				</tr>
				<tr valign="top">
					<td colspan=3 align="center">
<?
if ($reg->commit_flag == 0) {
?>
						<font size=2 color="#ff8008"><b>※割り算して1ヶ月分をご入力ください。<br>(使用量は上水道のみ)</b></font>
<?
}
?>
					</td>
				</tr>
<?
} else {
?>
				<tr>
					<td align="right" rowspan=2><img src="img/character/icon_suidou2.gif" width="140" height="51"></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr><td></td></tr>
<?
}
if ($reg->commit_flag == 0) {
?>
				<tr>
					<td align="center" colspan=4 height=50><input type="image" src="img/result_input/hozon.gif" width=278 height=34 alt="一時保存する"></d>
				</tr>
<?
}
?>
			</table>
			<table border=0 cellspacing=0 cellpadding=0 width=500 bgcolor="#f8f17d">
				<tr>
					<td colspan=4 align="center" height=60><img src="img/result_input/title02.gif" width=465 height=40></td>
				</tr>
				<tr>
					<td></td>
					<td align="center" colspan=2><b><font color="steelblue" size=2>カレンダーの合計値</font></b></td>
					<td></td>
				</tr>
<?
// ゴミ入力
if ($reg->mb_gm_flag == 1) {
	if ($reg->gm_inp_cd == '') {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_gomi.gif" width="141" height="51"></td>
					<td align="right"><?=number_format($reg->gm_use)?>&nbsp;kg&nbsp;&nbsp;</td>
					<td align="right"></td>
					<td align="center"><a href="result_gm.php"><img src="img/result_input/carendar.gif" width=156 height=26 border=0></a></td>
				</tr>
<?
	} else {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_gomi.gif" width="141" height="51"></td>
					<td align="center" colspan=2><nobr><font size=2 color="green"><?=$reg->gm_inp_cd==1?'使用しなかった':'今月は入力しない'?></font></nobr></td>
					<td align="center"><a href="result_gm.php?inp=on"><img src="img/result_input/no_carendar.gif" width=156 height=26 border=0></a></td>
				</tr>
<?
	}
}
// 灯油入力
if ($reg->mb_ol_flag == 1) {
	if($reg->ol_inp_cd == '') {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_touyu.gif" width="141" height="51"></td>
					<td align="right"><?=number_format($reg->ol_use)?>&nbsp;L&nbsp;&nbsp;</td>
					<td align="right"><?=number_format($reg->ol_sum)?>&nbsp;円</td>
					<td align="center"><a href="result_ol.php"><img src="img/result_input/carendar.gif" width=156 height=26 border=0></a></td>
				</tr>
<?
	} else {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_touyu.gif" width="141" height="51"></td>
					<td align="center" colspan=2><nobr><font size=2 color="green"><?=$reg->ol_inp_cd==1?'使用しなかった':'使用したが入力しない'?></font></nobr></td>
					<td align="center"><a href="result_ol.php?inp=on"><img src="img/result_input/no_carendar.gif" width=156 height=26 border=0></a></td>
				</tr>
<?
	}
}
// ガソリン入力
if ($reg->mb_gl_flag == 1) {
	if ($reg->gl_inp_cd == '') {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_gasoline.gif" width="141" height="51"></td>
					<td align="right"><?=number_format($reg->gl_use)?>&nbsp;L&nbsp;&nbsp;</td>
					<td align="right"><?=number_format($reg->gl_sum)?>&nbsp;円</td>
					<td align="center"><a href="result_gl.php"><img src="img/result_input/carendar.gif" width=156 height=26 border=0></a></td>
				</tr>
<?
	} else {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_gasoline.gif" width="141" height="51"></td>
					<td align="center" colspan=2><nobr><font size=2 color="green"><?=$reg->gl_inp_cd==1?'使用しなかった':'使用したが入力しない'?></font></nobr></td>
					<td align="center"><a href="result_gl.php?inp=on"><img src="img/result_input/no_carendar.gif" width=156 height=26 border=0></a></td>
				</tr>
<?
	}
}
?>
				<tr>
					<td width=150></td>
					<td width=90></td>
					<td width=90></td>
					<td width=170></td>
				</tr>
			</table>
			<br>
<?
// 集計ボタン表示
if ($reg->commit_flag == 0) {
?>
			<table border=0 width=599>
				<tr>
					<td width=150></td>
					<td width=239 align="center" valign="bottom">
<?
	// 集計ボタン表示
	$commit_check = '';
	if ($reg->el_use <= 0)
		$commit_check = 1;
	if ($reg->mb_gas_kind_cd != 3 && $reg->gs_use <= 0)
		$commit_check = 1;
	if (($reg->bd_m % 2) == ($reg->mb_wt_month % 2) && $reg->wt_use <= 0)
		$commit_check = 1;
	if ($reg->mb_gm_flag == 1 && $reg->gm_inp_cd == '' && $reg->gm_use <= 0)
		$commit_check = 1;
	if ($reg->mb_ol_flag == 1 && $reg->ol_inp_cd == '' && $reg->ol_use <= 0)
		$commit_check = 1;
	if ($reg->mb_gl_flag == 1 && $reg->gl_inp_cd == '' && $reg->gl_use <= 0)
		$commit_check = 1;
	if ($reg->sys_ym < "$reg->bd_y-" . sprintf("%02d", $reg->bd_m) . "-01")
		$commit_check = 1;
	if ($commit_check != 1) {
?>
						<a href="warning.php"><img src="img/result_input/syuukei_on.gif" width=94 height=94 border=0 alt="集計する"></a><br>
<?
	} else {
?>
						<img src="img/result_input/syuukei_off.gif" width=94 height=94 border=0 alt="集計しない"><br>
<?
	}
?>
					</td>
					<td width=210 valign="middle">
						<img src="img/result_input/zou/03.gif" width=210 height=93>
					</td>
				</tr>
				<tr>
					<td colspan=3 align="center"><font color="red" size=1><b>※ 全ての数値を一時保存すると”集計ボタン”が押せるようになります。</b></font></td>
				</tr>
			</table>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td align="center" colspan=3><hr width="95%" size="1"></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>

		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center">
					<a href="main.php"><img src="img/button/input/main.gif" border="0" width="79" height="44" alt="メインへ"></a>
					</td>
				</tr>
				<tr>
					<td height="7">　</td>
				</tr>
			</table>
		</td>

		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>