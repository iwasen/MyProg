<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:先月の実績入力
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");

// セッション処理
if (!isset($_SESSION['ss_result_input']))
	redirect('result_input.php');
$reg = &$_SESSION['ss_result_input'];

// フォームからデータ取得
$reg->el_use = get_number($el_use);
$reg->el_sum = get_number($el_sum);
$reg->gs_use = get_number($gs_use);
$reg->gs_sum = get_number($gs_sum);
$reg->wt_use_input = get_number($wt_use_input);
$reg->wt_sum_input = get_number($wt_sum_input);
//$reg->bd_y = get_number($bd_y);
//$reg->bd_m = get_number($bd_m);

// 正常値チェックなし
if (!$reg->el_use && $reg->el_sum)
	$msg[] = '電気使用量を入力して下さい。';
if ($reg->el_use && !$reg->el_sum)
	$msg[] = '電気金額を入力して下さい。';
if ($reg->el_use && !check_num($reg->el_use))
	$msg[] = '電気使用量は数値のみで入力して下さい。';
if ($reg->el_sum && !check_num($reg->el_sum))
	$msg[] = '電気金額は数値のみで入力して下さい。';

if (!$reg->gs_use && $reg->gs_sum)
	$msg[] = 'ガス使用量を入力して下さい。';
if ($reg->gs_use && !$reg->gs_sum)
	$msg[] = 'ガス金額を入力して下さい。';
if ($reg->gs_use && !check_num($reg->gs_use))
	$msg[] = 'ガス使用量は数値のみで入力して下さい。';
if ($reg->gs_sum && !check_num($reg->gs_sum))
	$msg[] = 'ガス金額は数値のみで入力して下さい。';

if (!$reg->wt_use_input && $reg->wt_sum_input)
	$msg[] = '水道使用量を入力して下さい。';
if ($reg->wt_use_input && !$reg->wt_sum_input)
	$msg[] = '水道金額を入力して下さい。';
if ($reg->wt_use_input && !check_num($reg->wt_use_input))
	$msg[] = '水道使用量は数値のみで入力して下さい。';
if ($reg->wt_sum_input && !check_num($reg->wt_sum_input))
	$msg[] = '水道金額は数値のみで入力して下さい。';

if (!$msg)
	redirect('result_input_confirm.php');


?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top"><br>
		<br>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">
			<table border="0"　align="center" width="330">
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
			</table><br>
		</td>
	</tr>
	<tr>
		<td align="center" colspan=3><hr width="95%" size="1"></td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><input type="image" src="img/button/yarinaosu.gif" border="0" width="87" height="30" onclick="history.back()"></td>
					<td width="10"></td>
				</tr>
				<tr align="center">
					<td height="7" colspan="5"><br></td>
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