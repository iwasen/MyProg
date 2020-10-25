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

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_input_exp.php');
$reg = &$_SESSION['ss_regist2'];


// 登録済みチェック
function check_regist($addr) {
	$sql = "SELECT mb_mail_addr FROM t_member WHERE mb_mail_addr=". sql_char(strtolower($addr)) . " AND mb_step<>9";
	$result = db_exec($sql);
	return pg_numrows($result);
}
// ID重複チェック
function check_mb_id($id) {
	$sql = "SELECT mb_id FROM t_member WHERE mb_id='$id' AND mb_step<>9";
	$result = db_exec($sql);
	return pg_numrows($result);
}

// フォームからデータ取得
//$reg->partner = $partner;
$reg->partner_age = get_number($partner_age);

for ($i = 1; $i <= 14; $i++) {
	$family = &$reg->family[$i];
	$family->family_rel = get_number($family_rel[$i - 1]);
	$family->family_age = get_number($family_age[$i - 1]);
}

if ($family_rel[0] == '1' && $family_age[0] == '')
	$msg[] = '配偶者様の年齢を入力して下さい。。';

for ($i = 1; $i < 12; $i++) {
	if ($family_rel[$i] == '' && $family_age[$i] >0)
		$msg[] = 'ご家族の続柄を選択して下さい。';
	if ($family_rel[$i] > 0 && $family_age[$i] == '')
		$msg[] = 'ご家族の年齢を記入して下さい。';
	if ($msg)
		break;
}
if (!$msg) {
	if ($reg->back_page)
		redirect("$reg->back_page");
	else
		redirect('member_input04.php');
}
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
					<td align="center"><img src="img/nyuukai/title/m_input03_01.gif" width="269" height="37" alt="我が家の家族構成　その2"></td>
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