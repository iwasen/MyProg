<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");

// commitフラグをチェックして表示を変える
function commit_check($gm_use, $flag) {
	if ($flag)
		return $gm_use;
	else
		return "<input type=\"text\" size=6 maxlength=4 name=\"gm_use[]\" " . value(zero_to_null($gm_use)) . ">";
}
// ゼロ→null
function zero_to_null($num) {
	if ($num == 0)
		return '';
	else
		return $num;
}

// セッション処理
if (!$_SESSION['ss_result_input'])
	$_SESSION['ss_result_input'] = new result_input;
$reg = &$_SESSION['ss_result_input'];

$reg->get_ym();
$reg->get_member_info();
$reg->read_db();
$reg->get_last_day($reg->bd_y, $reg->bd_m);
$gm_use = explode(',', $reg->gm_use);

// やっぱり入力する
if ($inp == 'on' && $reg->commit_flag == 0) {
	$sql = "UPDATE t_base_data SET bd_gm_inp_cd=null WHERE bd_seq_no=" . $reg->bd_seq_no;
	db_exec($sql);
}

// 設定ページ表示確認
$reg->disp_page('gm');

// カレンダー処理
define('DAY_SEC', 24*60*60);

$today = getdate();

$year = $reg->bd_y;
$month = $reg->bd_m;

$d = strtotime("$year/$month/1");
$st = $d - date('w', $d) * DAY_SEC;

$d = strtotime("$year/" . ($month + 1) . "/1");
$ed = $d + (6 - date('w', $d)) * DAY_SEC;


?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form name="form1" method="post" action="result_gm_check.php">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr height=60>
		<td width="35" align="left" valign="top"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td width=644 align="center" valign="bottom">
			<table width=644 border=0 cellspacing=0 cellpadding=3>
				<tr>
					<td align="center" colspan=3>
						<img src="img/year/input/year/<?=$reg->bd_y?>.gif" border="0" width="112" height="28">
						<img src="img/year/input/month/<?=sprintf("%02d", $reg->bd_m)?>.gif" border="0" width="56" height="28">
						<img src="img/year/input/nyuuryoku.gif" border="0" width="129" height="28">
					</td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td align="center" colspan=3><img src="img/result_input/calendar/gomi_calender.gif"></td>
	</tr>
	<tr>
		<td align="center" colspan=3>
			<table border=0 cellspacing=0 cellpadding=0 bgcolor="#9999ff" width=90% height=350>
				<tr>
					<td align="center" valign="middle" colspan=3>
						<table border=1 cellspacing=0 cellpadding=0 bgcolor="white" width=99% height=346>
							<tr bgcolor="cornsilk">
								<th width="10%"><img src="img/result_input/calendar/week/sun.gif"></th>
								<th width="10%"><img src="img/result_input/calendar/week/mon.gif"></th>
								<th width="10%"><img src="img/result_input/calendar/week/thes.gif"></th>
								<th width="10%"><img src="img/result_input/calendar/week/wednes.gif"></th>
								<th width="10%"><img src="img/result_input/calendar/week/thurs.gif"></th>
								<th width="10%"><img src="img/result_input/calendar/week/fli.gif"></th>
								<th width="10%"><img src="img/result_input/calendar/week/satur.gif"></th>
							</tr>
<?
$d = $st;
$i = 0;
while ($d <= $ed) {
	$week = date('w', $d);
	if ($week == 0)
		echo "<tr>\n";

	if (date('m', $d) == $month) {
		switch ($week) {
		case 0:
			$color = 'red';
			$bgcolor = 'pink';
			break;
		case 6:
			$color = 'blue';
			$bgcolor = '#CCFFFF';
			break;
		default:
			$color = 'black';
			$bgcolor = '#CCFFCC';
			break;
		}
//		$input = "<input type=\"text\" size=6 maxlength=4 name=\"gm_use[]\" " . value(zero_to_null($gm_use[$i])) . ">kg";
		$input = commit_check($gm_use[$i], $reg->commit_flag) . "</td><td>kg";
		$i++;
	} else {
		$color = 'gray';
		$bgcolor = '#FFFFFF';
		$input = "<br>";
	}
	echo "<td width='10%' valign='top' bgcolor='$bgcolor'>\n";
	echo "<table border=0 cellpadding=1 cellspacing=0 width='100%'>\n";
	if (date('Ymd') == date('Ymd', $d))
		echo "<tr bgcolor='#ffcc99'>";
	else
		echo "<tr>";
	echo "<td align='center' colspan=2><font size='+1' color='$color'><b>" . (int)date('d', $d) . "</b></font></td></tr>\n";
	echo "<tr><td align='right' width=70%>$input</td></tr>\n";
	echo "</table>\n";
	echo "</td>\n";

	if ($week == 6)
		echo "</tr>";

	$d += DAY_SEC;
}
?>
						</table>
					</td>
				</tr>
			</table>
<?
if ($reg->commit_flag == 0) {
?>
			<table width=90%>
				<tr>
					<td align="right"><a href="result_gm_q2.php?cd=2"><img src="img/result_input/calendar/no_input.gif" alt="今月は入力しない" border=0></a></td>
				</tr>
			</table>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td align="center" colspan=3><hr width=95% size=1></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td width=644 align="center" valign="top">
			<a href="main.php"><img src="img/button/input/main.gif" border="0" width="79" height="44" alt="メインへ"></a>
			<a href="result_input.php"><img src="img/button/input/back5.gif" border=0 width=137 height=44 alt="入力画面に戻る"></a>
<?
if ($reg->commit_flag == 0) {
?>
			<input type="image" src="img/button/input/hozon.gif" width=172 height=44 alt="データを一時保存する">
<?
}
?>
		</td>
		<td width="35" height="35" align="right" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>