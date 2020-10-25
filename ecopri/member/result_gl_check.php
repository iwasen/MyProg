<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:今日の実績入力（ガソリン）
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_result_input']))
	redirect('result_gm.php');
$reg = &$_SESSION['ss_result_input'];

// フォームからデータ取得＆チェック
for ($i = 0; $i <= $reg->last_day; $i++) {
	$gl_use[$i] = get_number($gl_use[$i]);
	$gl_sum[$i] = get_number($gl_sum[$i]);
	if (($gl_use[$i] && !$gl_sum[$i]) || (!$gl_use[$i] && $gl_sum[$i]))
		$msg[] = $i + 1 . "日の使用量又は金額が入力されていません。";
	if ($gl_use[$i] == '')
		$gl_use[$i] = 0;
	if ($gl_sum[$i] == '')
		$gl_sum[$i] = 0;
	if (!is_numeric($gl_use[$i]) || !is_numeric($gl_sum[$i])) {
		$msg[] = $i + 1 . "日の入力に数字以外の文字が含まれています。";
	}
}

$reg->gl_use = implode(",", $gl_use);
$reg->gl_sum = implode(",", $gl_sum);

if (!$msg)
	redirect('result_gl_ok.php');

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
<!--
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="img/nyuukai/title/m_check.gif"></td>
				</tr>
			</table>
			<hr width="95%" size="1">
-->
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