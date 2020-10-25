<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報変更　ID,Passwordチェック
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_change01.php');
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

// フォームからデータ取得＆チェック
$reg->mb_id = $mb_id;
$reg->mb_mail_addr = get_mail_addr($mb_mail_addr);
$reg->mb_password = get_password($mb_password);
$reg->mb_password2 = get_password($mb_password2);

if ($reg->mb_id == '')
	$msg[] = 'ＩＤが入力されていないようです。';
elseif (strlen($reg->mb_id) < 4)
	$msg[] = 'ＩＤは４文字以上を入力してください。';
elseif (strlen($reg->mb_id) > 20)
	$msg[] = 'ＩＤは２０文字以内で入力してください。';
elseif (!check_id($reg->mb_id))
	$msg[] = 'ＩＤは半角英数字で入力してください。';
elseif ($reg->mb_id_org != $reg->mb_id && check_mb_id($reg->mb_id))
	$msg[] = '入力されたＩＤはすでに登録されているようです。';

if ($reg->mb_mail_addr == '')
	$msg[] = 'メールアドレスが入力されていないようです。';
elseif (!check_mail_addr($reg->mb_mail_addr))
	$msg[] = 'メールアドレスが正しく入力されていないようです。';
elseif ($reg->mb_mail_addr_org != $reg->mb_mail_addr && check_regist($reg->mb_mail_addr))
	$msg[] = '入力されたメールアドレスはすでに登録されているようです。';

if ($reg->mb_password == '')
	$msg[] = 'パスワードが入力されていないようです。';
elseif ($reg->mb_password2 == '')
	$msg[] = 'パスワード確認が入力されていないようです。';
elseif ($reg->mb_password != $reg->mb_password2)
	$msg[] = 'パスワードとパスワード確認が一致していないようです。';
elseif (strlen($reg->mb_password) < 4)
	$msg[] = 'パスワードは４文字以上を入力してください。';
elseif (strlen($reg->mb_password) > 20)
	$msg[] = 'パスワードは２０文字以内で入力してください。';
elseif (!check_password($reg->mb_password))
	$msg[] = 'パスワードは半角英数字で入力してください。';

if (!$msg) {
	if ($reg->back_page)
		redirect("$reg->back_page");
	else
		redirect('member_change02.php');
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
					<td><img src="img/nyuukai/title/m_input01.gif" width="214" height="38" alt="さっそく登録しよう"></td>
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
				<td colspan="2" height="2" bgcolor="#FFCC66"><img src="img/spacer.gif" width="300" height="2"></td>
				<tr>
				<td>
					<font size="2"><br><?=disp_msg($msg, '・', '<br><br>')?><br></font>
				</td>
				</tr>
				<tr>
					<td colspan="2" height="2" bgcolor="#FFCC66"><img src="img/spacer.gif" width="300" height="2"></td>
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