<?
/******************************************************
' System :「きゃららFactory」クレオキャンペーン
' Content:入力画面
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "../inc";
include("$inc/regist.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('index.php');
$reg = &$_SESSION['ss_regist'];

// 登録済みチェック
function check_regist($addr, $week_no) {
	$sql = "SELECT df_mail_addr FROM d_fude01 WHERE df_mail_addr=". sql_char($addr) . " AND df_week_no=" . $week_no;
	$result = db_exec($sql);
	return pg_numrows($result);
}

// ＩＤ有効性チェック
function check_id($id) {
	if (substr($id, 0, 2) == 'CE' && ereg("^[0-9]+$", substr($id, 2, 10)))
		return true;
}

// フォームからデータ取得
$reg->df_creo_id = $df_creo_id;
$reg->df_name1 = $df_name1;
$reg->df_name2 = $df_name2;
$reg->df_name1_kana = get_name_kana($df_name1_kana);
$reg->df_name2_kana = get_name_kana($df_name2_kana);
$reg->df_mail_addr = get_mail_addr(strtolower($df_mail_addr));

$reg->get_week_no(date("Ymd"));
if (!$reg->week_no)
	redirect('index.php');
	
if ($reg->df_creo_id == '')
	$msg[] = '会員番号が入力されていないようです。';
elseif (strlen($reg->df_creo_id) != 12)
	$msg[] = '会員番号が正しくないようです。';
elseif (!check_id($reg->df_creo_id))
	$msg[] = '会員番号が正しくないようです。';

if ($reg->df_name1 == '')
	$msg[] = 'お名前（姓）が入力されていないようです。';

if ($reg->df_name2 == '')
	$msg[] = 'お名前（名）が入力されていないようです。';

if ($reg->df_name1_kana == '')
	$msg[] = 'フリガナ（姓）が入力されていないようです。';

if ($reg->df_name2_kana == '')
	$msg[] = 'フリガナ（名）が入力されていないようです。';

if ($reg->df_mail_addr == '')
	$msg[] = 'メールアドレスが入力されていないようです。';
elseif (!check_mail_addr($reg->df_mail_addr))
	$msg[] = 'メールアドレスが正しく入力されていないようです。';
elseif (check_regist($reg->df_mail_addr, $reg->week_no))
	$msg[] = '入力されたメールアドレスはすでに登録されているようです。';

if (!$msg)
	redirect('confirm.php');

?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>「にがおえ」キャンペーン♪</title>

<script language="JavaScript">
<!--
mac=(navigator.appVersion.indexOf('Mac') != -1)?true:false;
ie=(navigator.appName.charAt(0) == "M")?true:false;
ns=(navigator.appName.charAt(0) == "N")?true:false;

if(mac)	{
	fName = "css/mac.css";
}
else	{
	if(ie)	{
	fName = "css/win_ie.css";
	}
	else if(ns)	{
	fName = "css/win_ns.css";
	}
}

if(fName != null)	{
	document.write("<link rel=stylesheet href='"+fName+"' type='text/css'>");
}
//-->
</script>



</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<br>
<table>
	<tr>
		<td>
			<table border="0" width="400" cellspacing="10">
				<td colspan="2" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="400" height="2"></td>
					<tr>
						<td align="center">
							<font size="2" color="red"><br>※ ご確認お願いします<br></font>
						</td>
					</tr>
					<tr>
						<td>
							<font size="2"><br><?=disp_msg($msg, '　・', '<br><br>')?><br></font>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="400" height="2"></td>
					</tr>
				</td>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="image" src="img/back.gif" onclick="history.back()" width=106 height=46 alt="戻る">
		</td>
	</tr>
<table>
</div>
</body>
</html>