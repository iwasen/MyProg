<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報入力
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/fiducial1.php");
include("$inc/mypage.php");

// 表示色セット
function set_color($data,$flag,$eng1,$eng2,$usesum) {
	if ($eng1 == $eng2) {
		if ($usesum == 'use')
			return "<input type='text' name='edit_use[]' size=7 maxlength=8 " . value($data) . ">";
		if ($usesum == 'sum')
			return "<input type='text' name='edit_sum[]' size=7 maxlength=8 " . value($data) . ">";
	} elseif ($flag == 1)
		return "<font color=\"blue\">" . number_format($data) . "</font>";
	else
		return "<font color=\"red\">" . number_format($data) . "</font>";
}

// ページ確認
$date = add_date(date("Y-m-d"), 0, -2, 0);
$sql = "SELECT mb_regist_date FROM t_member WHERE mb_seq_no=" . $_SESSION['ss_seq_no'] . " AND mb_regist_date <" . sql_date($date);
if (db_fetch1($sql))
	redirect('fiducial_change2.php');

// セッション処理
if (!isset($_SESSION['ss_fiducial1']))
	$_SESSION['ss_fiducial1'] = new fiducial1;
$reg = &$_SESSION['ss_fiducial1'];

$reg->set_start_ym();
$year = $reg->start_y;
$month = $reg->start_m;

$reg->read_db1();
?>
<html>
<head>
<title>☆★☆xxxゾウさんClub☆★☆</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<form name="form1" method="post" action="fiducial_edit_check1.php">
<div align="center">
<table width="714" height="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
		<br>

			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><img src="img/fiducial/titleA_03.gif" width=431 height=69 border=0 alt="過去の数値"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td></td>
		<td align="center" valign="top">
		<hr size="1">
		</td>
		<br>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<table border="1" width="650" bordercolor="#33ff00" cellpadding="0" cellspacing="1">
				<tr align="center" bgcolor="#eeffee">
					<td width=110><font size=2><b>マンスリーデータ<br></b></font><font size=1>月単位で<br>発生するデータ</font></td>
					<td colspan="2" width=180 valign="center">
						<table border=0 cellspacing=0 cellpadding=0 width=70%>
							<tr>
								<td width=65 align="center"><img src="img/fiducial/icon/icon_denki.gif" border="0" width="128" height="51"></td>
							</tr>
<?
if ($en == 'el') {
?>
							<tr>
								<td align="right" valign="top" height=25>
								<input type="hidden" name="en" value='el'>
								<input type="image" src="img/fiducial/henkou1.gif" width=76 height=20 alt="変更を送信">
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
					<td colspan="2" width=180>
						<table border=0 cellspacing=0 cellpadding=0 width=70%>
							<tr>
								<td width=65 align="center"><img src="img/fiducial/icon/icon_gas.gif" border="0" width="128" height="51"></td>
							</tr>
<?
if ($en == 'gs') {
?>
							<tr>
								<td align="right" valign="top" height=25>
								<input type="hidden" name="en" value='gs'>
								<input type="image" src="img/fiducial/henkou1.gif" width=76 height=20 alt="変更を送信">
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
					<td colspan="2" width=180>
						<table border=0 cellspacing=0 cellpadding=0 width=70%>
							<tr>
								<td width=65 align="center"><img src="img/fiducial/icon/icon_suidou.gif" border="0" width="128" height="51"></td>
							</tr>
<?
if ($en == 'wt') {
?>
							<tr>
								<td align="right" valign="top" height=25>
								<input type="hidden" name="en" value='wt'>
								<input type="image" src="img/fiducial/henkou1.gif" width=76 height=20 alt="変更を送信">
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
<?
for ($i = 0; $i < 12; $i++) {
	$eng = &$reg->eng[$i];
?>

				<tr align="right">
					<td width=110 align="center"><?=$eng->bd_y."/".sprintf("%02d", $eng->bd_m)?></td>
<!--					<td width=110 align="center"><?=//get_y($i)."/".sprintf("%02d", get_m($i))?></td>-->
					<td width=90><font size=2><?=set_color($eng->el_use,$eng->el_use_flag,'el',$en,'use')?>&nbsp;kwh&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->el_sum,$eng->el_sum_flag,'el',$en,'sum')?>&nbsp;円&nbsp;</td>
					<td width=90><font size=2><?=set_color($eng->gs_use,$eng->gs_use_flag,'gs',$en,'use')?>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->gs_sum,$eng->gs_sum_flag,'gs',$en,'sum')?>&nbsp;円&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->wt_use,$eng->wt_use_flag,'wt',$en,'use')?>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->wt_sum,$eng->wt_sum_flag,'wt',$en,'sum')?>&nbsp;円&nbsp;</font></td>
				</tr>
<?
}
?>
			</table>
		</td>
	</tr>
	<tr><td><br></td></tr>
	<tr>
		<td></td>
		<td>
			<table border="1" width="650" bordercolor="#cc66ff" cellpadding="0" cellspacing="1">
				<tr align="center" bgcolor="#ffeeff">
					<td width=110><font size=2><b>デイリーデータ<br></b></font><font size=1>日単位で<br>発生するデータ</font></td>
					<td width=180 valign="center">
						<table border=0 cellspacing=0 cellpadding=0 width=70%>
							<tr>
								<td width=65 align="center"><img src="img/fiducial/icon/icon_gomi.gif" border="0" width="128" height="51"></td>
							</tr>
<?
if ($en == 'gm') {
?>
							<tr>
								<td align="right" valign="top" height=25>
								<input type="hidden" name="en" value='gm'>
								<input type="image" src="img/fiducial/henkou1.gif" width=76 height=20 alt="変更を送信">
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
					<td colspan="2" width=180 valign="center">
						<table border=0 cellspacing=0 cellpadding=0 width=70%>
							<tr>
								<td width=65 align="center"><img src="img/fiducial/icon/icon_touyu.gif" border="0" width="128" height="51"></td>
							</tr>
<?
if ($en == 'ol') {
?>
							<tr>
								<td align="right" valign="top" height=25>
								<input type="hidden" name="en" value='ol'>
								<input type="image" src="img/fiducial/henkou1.gif" width=76 height=20 alt="変更を送信">
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
					<td colspan="2" width=180 valign="center">
						<table border=0 cellspacing=0 cellpadding=0 width=70%>
							<tr>
								<td width=65 align="center"><img src="img/fiducial/icon/icon_gasoline.gif" border="0" width="128" height="51"></td>
							</tr>
<?
if ($en == 'gl') {
?>
							<tr>
								<td align="right" valign="top" height=25>
								<input type="hidden" name="en" value='gl'>
								<input type="image" src="img/fiducial/henkou1.gif" width=76 height=20 alt="変更を送信">
								</td>
							</tr>
<?
}
?>
						</table>
					</td>

				</tr>
<?
for ($i = 0; $i < 12; $i++) {
	$eng = &$reg->eng[$i];
?>
				<tr align="right">
					<td width=110 align="center"><?=$eng->bd_y."/".sprintf("%02d", $eng->bd_m)?></td>
					<td width=180><font size=2><font color="red"><?=set_color($eng->gm_use,$eng->gm_use_flag,'gm',$en,'use')?></font>&nbsp;kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->ol_use,$eng->ol_use_flag,'ol',$en,'use')?>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->ol_sum,$eng->ol_sum_flag,'ol',$en,'sum')?>&nbsp;円&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->gl_use,$eng->gl_use_flag,'gl',$en,'use')?>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->gl_sum,$eng->gl_sum_flag,'gl',$en,'sum')?>&nbsp;円&nbsp;</font></td>
				</tr>
<?
}
?>
			</table>
			<br>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><hr size="1"></td>
		<td></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center"><img src="img/button/input/back4.gif" width=137 height=44 border=0 alt="変更しないで戻る" onclick="history.back()"></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</div>
</body>
