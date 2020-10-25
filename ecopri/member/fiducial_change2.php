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
function set_color($data,$flag) {
	if ($flag == 1)
		return "<font color=\"blue\">" . number_format($data) . "</font>";
	elseif ($flag == 6)
		return "<font color=\"green\">" . number_format($data) . "</font>";
	else
		return "<font color=\"red\">" . number_format($data) . "</font>";
}

// ページ確認
$date = add_date(date("Y-m-d"), 0, -2, 0);
$sql = "SELECT mb_regist_date FROM t_member WHERE mb_seq_no=" . $_SESSION['ss_seq_no'] . " AND mb_regist_date >=" . sql_date($date);
if (db_fetch1($sql))
	redirect('fiducial_change1.php');

// セッション処理
//if (!isset($_SESSION['ss_fiducial1']))
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

<div align="center">
<form method="post" action="fiducial_edit2.php">
<table width="714" height="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
		<br>

			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><img src="img/fiducial/titleA_03.gif" width=431 height=69 border=0 alt="過去の数値"></td>
				</tr>
				<tr align="center">
					<td>
					<font size=1><br>
					過去のエネルギー使用量を入れるとこんないいことが！<br>
					⇒ <a href="iikoto.php" target="_blank">詳しくはコチラ</a><br><br>
					</font>
					</td>
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
					<td colspan="2" width=180 valign="center"><img src="img/character/denki.gif" border="0" width="62" height="72"></td>
					<td colspan="2" width=180 valign="center"><img src="img/character/gas.gif" border="0" width="62" height="72"></td>
					<td colspan="2" width=180 valign="center"><img src="img/character/suidou.gif" border="0" width="62" height="72"></td>
				</tr>
<?
for ($i = 0; $i < 12; $i++) {
	$eng = &$reg->eng[$i];
?>

				<tr align="right">
					<td width=110 align="center"><?=$eng->bd_y."/".sprintf("%02d", $eng->bd_m)?></td>
					<td width=90><font size=2><?=set_color($eng->el_use,$eng->el_use_flag)?>&nbsp;kwh&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->el_sum,$eng->el_sum_flag)?>&nbsp;円&nbsp;</td>
					<td width=90><font size=2><?=set_color($eng->gs_use,$eng->gs_use_flag)?>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->gs_sum,$eng->gs_sum_flag)?>&nbsp;円&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->wt_use,$eng->wt_use_flag)?>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->wt_sum,$eng->wt_sum_flag)?>&nbsp;円&nbsp;</font></td>
				</tr>
<?
}
?>
			</table>
			<table>
				<tr>
					<td><font size=2>*入力した数値を<font color="blue">”青”</font>、推定使用量を<font color="red">”赤”</font>で表示しています。</font></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><br></td></tr>
	<tr>
		<td></td>
		<td>
			<table border="1" width="650" bordercolor="#cc66ff" cellpadding="0" cellspacing="1">
				<tr align="center" bgcolor="#ffeeff">
					<td width=110><font size=2><b>デイリーデータ<br></b></font><font size=1>日単位で<br>発生する>データ</font></td>
					<td width=180 valign="center"><img src="img/character/gomi.gif" border="0" width="62" height="72"></td>
					<td colspan="2" width=180 valign="center"><img src="img/character/touyu.gif" border="0" width="62" height="72"></td>
					<td colspan="2" width=180 valign="center"><img src="img/character/gasoline.gif" border="0" width="62" height="72"></td>
				</tr>
<?
for ($i = 0; $i < 12; $i++) {
	$eng = &$reg->eng[$i];
?>

				<tr align="right">
					<td width=110 align="center"><?=$eng->bd_y."/".sprintf("%02d", $eng->bd_m)?></td>
					<td width=180><font size=2><font color="red"><?=set_color($eng->gm_use,$eng->gm_use_flag)?></font>&nbsp;kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->ol_use,$eng->ol_use_flag)?>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->ol_sum,$eng->ol_sum_flag)?>&nbsp;円&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->gl_use,$eng->gl_use_flag)?>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->gl_sum,$eng->gl_sum_flag)?>&nbsp;円&nbsp;</font></td>
				</tr>
<?
}
?>
			</table>
			<table>
				<tr>
					<td><font size=2>*入力した数値を<font color="blue">”青”</font>、推定使用量を<font color="red">”赤”</font>で表示しています。</font></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><br></td></tr>
	<tr>
		<td colspan=3 align="center">
			<table border=1 bordercolor="dodgerblue" cellspacing=0 cellpadding=0 width=350 height=40>
				<tr>
					<td align="center">
						<table border=0 cellspacing=0 cellpadding=0 width=330>
							<tr>
								<td>
								<font size=2>
								<select name="edit_no">
<?
for ($i = 0; $i < 12; $i++) {
	$eng = &$reg->eng[$i];
?>
								<option value="<?=$i?>"><?=$eng->bd_y . "/" . sprintf("%02d", $eng->bd_m)?>
<?
}
?>
								</select><b>月分 を修正する</b>
								</td>
								<td><input type="image" src="img/fiducial/syuuseibutton.gif" width=117 height=28 border=0 alt="修正する"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=3 align="center" height=20><font size=1 color="#404040">* 大量のデータ変更が生じた場合（取り寄せた領収書が遅く届いたなど）は事務局にご連絡ください。</font></td>
	</tr>
	<tr><td colspan=3 height=10></td></tr>
	<tr>
		<td></td>
		<td align="right">
			<table width=220 border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td align="right">
						<img src="img/fiducial/text/<?=sprintf("%02d", $reg->bd_m)?>.gif" width=220 height=17 border=0 alt="データ入力するにはココ">
					</td>
				</tr>
				<tr>
					<td align="center"><a href="result_input.php"><img src="img/fiducial/text/nyuuryoku.gif" width=96 height=25 border=0 alt="数値入力"></a></td>
				</tr>
			</table>
		</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td><hr size="1"></td>
		<td></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center"><a href="main.php"><img src="img/button/input/main.gif" width=79 height=44 border=0 alt="メインへ"></a></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
