<?
include('../inc/common.php');
include('../inc/database.php');

//=== メイン処理 ===
if ($id == '')
	redirect('../index.php');

$sql = "SELECT KT_status,KT_mail_adr,KT_pr_id FROM T_KARI_TOUROKU WHERE KT_seq_no=$id";
$ds = CreateDynaset($sql, ORADYN_READONLY);
if ($ds->EOF())
	redirect('../index.php');

//インプレス対応 =====================
$sql = "SELECT PR_baitai_id FROM T_PR WHERE PR_pr_id='" . $ds->GetValue('KT_pr_id') . "'";
$ds2 = CreateDynaset($sql, ORADYN_READONLY);
if (!$ds2->EOF()) {
	if ($ds2->GetValue('PR_baitai_id') == BT_IMPRESS)
		redirect("../impress/12/12-1.php?id=$id");
}
//====================================

switch ($ds->GetValue('KT_status')) {
case '1':	//登録済み
	redirect('12a-2_error.html');
case '2':	//解除済み
	redirect('12b-2_error.html');
}

$sql = "SELECT MM_member_id FROM T_MEMBER WHERE MM_mail_adr=" . SqlStr($ds->GetValue('KT_mail_adr')) . " AND MM_status<>'9'";
$ds = CreateDynaset($sql, ORADYN_READONLY);
if (!$ds->EOF())
		redirect('12a-2_error.html');
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<meta HTTP-EQUIV="Pragma" Content="no-cache">
		<title></title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="52">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form method="post" action="12-1_check.php">
		<br>
		<input type="hidden" name="id" value="<?=$id?>">
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36">　<img height="16" width="50" src="../img/space.gif"></td>
				<td width="550" height="36" valign="top"><font color="#2f2180"><b>「はいめーる・net」への仮登録をありがとうございました。
					<hr>
					</b></font><font size="2"><br>
					こちらのページから、「登録」ボタンを押してください。<br>
					「登録」ボタンを押していただいた段階で、正式登録が完了いたします。<br>
					<br>
					<input type="submit" value="　登録する　" name="regist"><br>
					<br>
					<br>
					<br>
					「はいめーる・net」に登録した覚えのない方でこのメールを受け取った方は<br>
					お手数ですが下の「解除」ボタンから、仮登録を解除してください。<br>
					<br>
					<input type="submit" value="解除" name="cancel"><br>
					<br>
					<br>
					</font></td>
			</tr>
		</table>
		</form>
	</body>

</html>
