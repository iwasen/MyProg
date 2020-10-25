<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/regist3.php");

/******************************************************
' System :Eco-footprint 会員ページ
' Content:申込者情報入力
'******************************************************/

// 正常値(とりあえず未使用)
$wt_use_min = 10; $wt_use_max = 100;  $wt_price_min = 10; $wt_price_max = 300;
$gl_use_min = 0; $gl_use_max = 9999;  $gl_price_min = 50; $gl_price_max = 200;
$gm_use_min = 0; $gm_use_max = 600;
$el_use_min = 100; $el_use_max = 1000;  $el_price_min = 5; $el_price_max = 50;
$gs_use_min = 10; $gs_use_max = 500;  $gs_price_min = 10; $gs_price_max = 800;
$ol_use_min = 0; $ol_use_max = 9999;  $ol_price_min = 30; $ol_price_max = 100;

function set_flag($use_data,$sum_data=0,&$use_flag,&$sum_flag) {
	if ($use_data != '' && $sum_data != '') {
		$use_flag = 1;
		$sum_flag = 1;
	} elseif ($use_data != '') {
		$use_flag = 1;
		$sum_flag = 2;
	} elseif ($sum_data != '') {
		$use_flag = 3;
		$sum_flag = 1;
	} else {
		$use_flag = 4;
		$sum_flag = 4;
	}
}

// 入力月取得
function get_m($i) {
	global $year, $month;
	return (int)(date("m", mktime (0,0,0,$month + $i,1,$year)));
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist3']))
	redirect('fiducial_exp.php');
$reg = &$_SESSION['ss_regist3'];
$year = $reg->start_y;
$month = $reg->start_m;

//フォームからのデータ取得
$reg->wt_input_use = get_number($wt_use);
$reg->wt_input_sum = get_number($wt_sum);
$reg->gl_input_use = get_number($gl_use);
$reg->gl_input_sum = get_number($gl_sum);
$reg->gm_input_use = get_number($gm_use);

set_flag($reg->wt_input_use,$reg->wt_input_sum,$reg->wt_use_flag,$reg->wt_sum_flag);
set_flag($reg->gl_input_use,$reg->gl_input_sum,$reg->gl_use_flag,$reg->gl_sum_flag);
$reg->gm_use_flag = $reg->gm_input_use != '' ? '1' : '4';

for ($i = 1; $i <= 12; $i++) {
	$energy = &$reg->energy[get_m($i)];
	$energy->el_input_use = get_number($el_use[$i - 1]);
	$energy->el_input_sum = get_number($el_sum[$i - 1]);
	$energy->gs_input_use = get_number($gs_use[$i - 1]);
	$energy->gs_input_sum = get_number($gs_sum[$i - 1]);
	$energy->ol_input_use = get_number($ol_use[$i - 1]);
	$energy->ol_input_sum = get_number($ol_sum[$i - 1]);
set_flag($energy->el_input_use,$energy->el_input_sum,$energy->el_use_flag,$energy->el_sum_flag);
set_flag($energy->gs_input_use,$energy->gs_input_sum,$energy->gs_use_flag,$energy->gs_sum_flag);
set_flag($energy->ol_input_use,$energy->ol_input_sum,$energy->ol_use_flag,$energy->ol_sum_flag);
}

if ($reg->wt_input_use != '' && !check_num($reg->wt_input_use))
	$msg[] = '水道使用量が正しく入力されていないようです。';
if ($reg->wt_input_sum != '' && !check_num($reg->wt_input_sum))
	$msg[] = '水道請求額が正しく入力されていないようです。';

if ($reg->gl_input_use != '' && !check_num($reg->gl_input_use))
	$msg[] = 'ガソリン使用量が正しく入力されていないようです。';
if ($reg->gl_input_sum != '' && !check_num($reg->gl_input_sum))
	$msg[] = 'ガソリン請求額が正しく入力されていないようです。';

if ($reg->gm_input_use != '' && !check_num($reg->gm_input_use))
	$msg[] = 'ゴミの量が正しく入力されていないようです。';

for ($i = 1; $i <= 12; $i++) {
	$energy = &$reg->energy[$i];
	if ($energy->el_input_use != '' && !check_num($energy->el_input_use))
		$msg[] = '電気使用量が正しく入力されていないようです。';
	if ($energy->el_input_sum != '' && !check_num($energy->el_input_sum))
		$msg[] = '電気請求額が正しく入力されていないようです。';

	if ($energy->gs_input_use != '' && !check_num($energy->gs_input_use))
		$msg[] = 'ガス使用量が正しく入力されていないようです。';
	if ($energy->gs_input_sum != '' && !check_num($energy->gs_input_sum))
		$msg[] = 'ガス請求額が正しく入力されていないようです。';

	if ($energy->ol_input_use != '' && !check_num($energy->ol_input_use))
		$msg[] = '灯油使用量が正しく入力されていないようです。';
	if ($energy->ol_input_sum != '' && !check_num($energy->ol_input_sum))
		$msg[] = '灯油請求額が正しく入力されていないようです。';

	if ($msg)
		break;
}

if (!$msg)
	redirect('fiducial_check.php');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="90%">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"> <img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><div align="center"><img src="img/nyuukai/title/f_input01.gif" width="214" height="38"　 alt="さっそく入力しよう"></div></td>
				</tr>
			</table>
			<hr width="95%" size="1">
		</td>
		<td width="35" align="right" valign="top">
			<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>
		<td align="center" valign="top">
			<br><br><br><br>
			<img src="img/nyuukai/zou_img/m_error.gif" width="162" height="124" alt="あれ？">
			<br>
			<table border="0" width="350" cellspacing="10">
				<td colspan="2" height="2" bgcolor="#00CCFF"><img src="img/spacer.gif" width="300" height="2"></td>
				<tr>
				<td>
					<font size="2"><br><?=disp_msg($msg, '・', '<br><br>')?><br></font>
				</td>
				</tr>
				<tr>
					<td colspan="2" height="2" bgcolor="#00CCFF"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
			</table>
			<br>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><input type="image" width="99" height="40" border="0" alt="やり直す" src="img/nyuukai/button/naosu.gif" onclick="history.back()"></td>
				</tr>
				<tr>
					<td height="7" colspan="3"><img src="img/spacer.gif" width="208" height="7"></td>
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