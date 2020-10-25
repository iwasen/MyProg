<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:先月の実績入力最終確認
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");

// セッション処理
if (!isset($_SESSION['ss_result_input']))
	redirect('result_input.php');
$reg = &$_SESSION['ss_result_input'];

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form name="form1" method="post" action="warning_check.php">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top"><br><br></td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

		<tr>
	<td><br></td>
		<td align="center" valign="top">

			<table width="599" border=0 cellspacing=0 cellpadding="0">
				<tr>
					<td align="center"><img src="img/result_input/title/soushin.gif"></td>
				</tr>

			</table>
			<table width="599" border="0" cellspacing="0" cellpadding="0" bordercolor="#ffff" height=320>
				<tr>
					<td align="center"><img src="img/help/warning.gif" border="0" width="514" height="263"></td>
				</tr>
			</table>


			<table border="0" width="500" bgcolor="#bfddf2">
				<tr>
					<td align="center" colspan=4 height=60><img src="img/result_input/title01.gif" width=465 height=40></td>
				</tr>
				<tr>
					<td align="right" width=150><img src="img/character/icon_denki.gif" width="140" height="51"></td>
					<td align="right" width=120><?=number_format($reg->el_use)?>&nbsp;kwh&nbsp;&nbsp;&nbsp;</td>
					<td align="right" width=160><?=number_format($reg->el_sum)?>&nbsp;円</td>
					<td width=70></td>
				</tr>
<?
if ($reg->mb_gas_kind_cd != 3) {
?>
				<tr>
					<td align="right"><img src="img/character/icon_gas.gif" width="140" height="51"></td>
					<td align="right"><?=number_format($reg->gs_use)?>&nbsp;m3&nbsp;&nbsp;&nbsp;</td>
					<td align="right"><?=number_format($reg->gs_sum)?>&nbsp;円</td>
					<td></td>
				</tr>
<?
}
if ((($reg->bd_m % 2) == ($reg->mb_wt_month % 2) && ($reg->commit_flag == 0)) || ($reg->commit_flag == 1)) {
?>
				<tr>
					<td align="right"><img src="img/character/icon_suidou1.gif" width="140" height="51"></td>
					<td align="right"><?=number_format($reg->wt_use_input)?>&nbsp;m3&nbsp;&nbsp;&nbsp;</td>
					<td align="right"><?=number_format($reg->wt_sum_input)?>&nbsp;円</td>
					<td></td>
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
				</tr>
<?
if ($reg->mb_gm_flag == 1) {
	if ($reg->gm_inp_cd == '') {
?>
				<tr>
					<td width=150 align="right" height=55 valign="top"><img src="img/character/icon_gomi.gif" width="141" height="51"></td>
					<td width=120 align="right"><?=number_format($reg->gm_use)?>&nbsp;kg&nbsp;&nbsp;</td>
					<td width=160 align="right"></td>
					<td width=70></td>
				</tr>
<?
	} else {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_gomi.gif" width="141" height="51"></td>
					<td align="center" colspan=2><nobr><font size=2 color="green"><?=$reg->gm_inp_cd==1?'使用しなかった':'今月は入力しない'?></font></nobr></td>
					<td align="center"></td>
				</tr>
<?
	}
}
if ($reg->mb_ol_flag == 1) {
	if ($reg->ol_inp_cd == '') {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_touyu.gif" width="141" height="51"></td>
					<td align="right"><?=number_format($reg->ol_use)?>&nbsp;L&nbsp;&nbsp;</td>
					<td align="right"><?=number_format($reg->ol_sum)?>&nbsp;円</td>
					<td></td>
				</tr>
<?
	} else {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_touyu.gif" width="141" height="51"></td>
					<td align="center" colspan=2><nobr><font size=2 color="green"><?=$reg->ol_inp_cd==1?'使用しなかった':'使用したが入力しない'?></font></nobr></td>
					<td align="center"></td>
				</tr>
<?
	}
}
if ($reg->mb_gl_flag == 1) {
	if ($reg->gl_inp_cd == '') {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_gasoline.gif" width="141" height="51"></td>
					<td align="right"><?=number_format($reg->gl_use)?>&nbsp;L&nbsp;&nbsp;</td>
					<td align="right"><?=number_format($reg->gl_sum)?>&nbsp;円</td>
					<td></td>
				</tr>
<?
	} else {
?>
				<tr>
					<td align="right" height=55 valign="top"><img src="img/character/icon_gasoline.gif" width="141" height="51"></td>
					<td align="center" colspan=2><nobr><font size=2 color="green"><?=$reg->gl_inp_cd==1?'使用しなかった':'使用したが入力しない'?></font></nobr></td>
					<td align="center"></td>
				</tr>
<?
	}
}
?>
				<tr>
					<td width=150></td>
					<td width=120></td>
					<td width=160></td>
					<td width=70></td>
				</tr>
			</table><br>
		</td>
	</tr>
	<tr>
		<td align="center" colspan=3><input type="image" src="img/result_input/result_input_<?=sprintf("%02d",$reg->bd_m)?>.gif" width="312" height="40">
		<hr width="95%" size="1"></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><a href="javascript:history.back()"><img src="img/button/input/syuusei.gif" border="0" width="99" height="44" border=0></a></td>
					<td width="10"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</div>
</form>
</body>
</html>