<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/regist2.php");

/******************************************************
' System :Eco-footprint 会員ページ
' Content:申込者情報入力
'******************************************************/

// 車所有null→0
function null_to_0($num) {
	if ($num == '')
		return 0;
	else
		return $num;
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_input_exp.php');
$reg = &$_SESSION['ss_regist2'];

// フォームからデータ取得
$reg->keitai_cd = $keitai_cd;
//$reg->keitai_etc = $keitai_etc;
$reg->sozai_cd = $sozai_cd;		// 1:はい　2：いいえ
$reg->hebel_flag = $hebel_flag;		//　1：はい　0：いいえ
$reg->chikunen_cd = $chikunen_cd;
$reg->room_cd = $room_cd;
$reg->space_cd = $space_cd;
$reg->boiler_3cd = $boiler_3cd;	// 3:ガス式　1:電気式　5：灯油式
$reg->boil_gas_flag = $boil_gas_flag;	// 1：はい　2：いいえ　3：わからない
$reg->boil_ele_flag = $boil_ele_flag;
$reg->boiler_etc = $_etc;		//　廃止
$reg->gas_kind_cd = $gas_kind_cd;
$reg->gas_type_cd = $gas_type_cd;
$reg->water_month = $water_month;
$reg->car_flag = $car_flag;	//1:あり　0：なし
$reg->gcar_num = null_to_0(get_number($gcar_num));
$reg->dcar_num = null_to_0(get_number($dcar_num));
$reg->bike_num = null_to_0(get_number($bike_num));
$reg->ol_flag = $ol_flag;

if ($reg->gas_kind_cd != '1')
	$reg->gas_type_cd = '';
/*
if ($reg->gas_type_cd == '99')
	$reg->gas_type_cd = '13';
*/
if ($reg->boiler_3cd == '1') {
	switch ($reg->boil_ele_flag) {
	case 1:
		$reg->boiler_cd = 2;
		break;
	case 2:
		$reg->boiler_cd = 1;
		break;
	case 3:
		$reg->boiler_cd = 6;
		break;
	}
	$reg->boil_gas_flag = '';
} elseif ($reg->boiler_3cd == '3') {
	switch ($reg->boil_gas_flag) {
	case 1:
		$reg->boiler_cd = 4;
		break;
	case 2:
		$reg->boiler_cd = 3;
		break;
	case 3:
		$reg->boiler_cd = 7;
		break;
	}
	$reg->boil_ele_flag = '';
} elseif ($reg->boiler_3cd == '5') {
	$reg->boiler_cd = 5;
	$reg->boil_ele_flag = '';
	$reg->boil_gas_flag = '';
}

if ($reg->keitai_cd == '')
	$msg[] = '住居形態が選択されていないようです。';

if ($reg->sozai_cd == '')
	$msg[] = '木造（はい/いいえ）が選択されていないようです。';

if ($reg->hebel_flag == '')
	$msg[] = 'ﾍｰﾍﾞﾙﾊｳｽ（はい/いいえ）が選択されていないようです。';

if ($reg->room_cd == '')
	$msg[] = '間取りが選択されていないようです。';

if ($reg->boiler_3cd == '')
	$msg[] = '給湯器タイプが選択されていないようです。';
elseif ($reg->boiler_3cd == '3' && $reg->boil_gas_flag == '')
	$msg[] = '潜熱回収型(はい/いいえ/わからない)が選択されて<br>　いないようです。';
elseif ($reg->boiler_3cd == '1' && $reg->boil_ele_flag == '')
	$msg[] = 'ヒートポンプ式(はい/いいえ/わからない)が選択されて<br>　いないようです。';

if ($reg->boiler_3cd == '3' && $reg->gas_kind_cd == '3')
	$msg[] = 'ガス給湯器をご使用の方は、ガス種を選択して下さい。';
elseif ($reg->gas_kind_cd == '')
	$msg[] = 'ガス種が選択されていないようです。';

if ($reg->gas_kind_cd == '1' && $reg->gas_type_cd == '')
	$msg[] = '都市ガスの方はガスタイプを選択して下さい。<br>　（わからない場合は、「後で入力する」を選択して下さい。）';

if ($reg->water_month == '')
	$msg[] = '水道請求月が選択されていないようです。';

if ($reg->car_flag == '')
	$msg[] = '車所有(はい/いいえ)が選択されていないようです。';
elseif ($reg->car_flag == '1' && ($reg->gcar_num <=0 && $reg->dcar_num <= 0 && $reg->bike_num <= 0))
	$msg[] = '車の所有台数が入力されていないようです。';

if ($reg->ol_flag == '')
	$msg[] = '灯油使用(あり/なし)が選択されていないようです。';
elseif ($reg->ol_flag == '0' && $reg->boiler_cd == '5')
	$msg[] = '灯油給湯器をご使用の場合は、灯油使用「あり」を選択<br>　して下さい。';

if (!$msg)
	redirect('member_input05.php');

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
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
					<td align="center"><img src="img/nyuukai/title/m_input04_01.gif" width="246" height="37" alt="我が家の住宅に関して"></td>
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
			<img src="img/nyuukai/zou_img/m_error.gif" width="162" height="124" alt="これであってる？">
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