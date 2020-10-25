<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');
include('../inc/point.php');

// 数字チェック
function check_num($s) {
	return ereg("^[0-9]+$", $s);
}

//=== メイン処理 ===
session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

// 入力データ取得
if ($submit1) {
	$dt['exchange_type'] = '0';
	$dt['point'] = trim($_POST['point']);
	$dt['bank'] = trim($_POST['bank']);
	$dt['bank_branch'] = trim($_POST['bank_branch']);
	$dt['bank_kana'] = trim($_POST['bank_kana']);
	$dt['bank_branch_kana'] = trim($_POST['bank_branch_kana']);
	$dt['acc_kind'] = trim($_POST['acc_kind']);
	$dt['acc_number'] = ToHankaku(trim($_POST['acc_number']));
	$dt['acc_name1'] = trim($_POST['acc_name1']);
	$dt['acc_name2'] = trim($_POST['acc_name2']);
	$dt['acc_name1_kana'] = trim($_POST['acc_name1_kana']);
	$dt['acc_name2_kana'] = trim($_POST['acc_name2_kana']);
} elseif ($submit2) {
	$dt['exchange_type'] = '1';
	$dt['point'] = trim($_POST['point']);
	$dt['name1'] = trim($_POST['name1']);
	$dt['name2'] = trim($_POST['name2']);
	$dt['zip1'] = ToHankaku(trim($_POST['zip1']));
	$dt['zip2'] = ToHankaku(trim($_POST['zip2']));
	$dt['addr_pref'] = trim($_POST['addr_pref']);
	$dt['addr_city'] = trim($_POST['addr_city']);
	$dt['addr_number'] = trim($_POST['addr_number']);
	$dt['addr_building'] = trim($_POST['addr_building']);
} else
	redirect('8c-2.php');

// ポイント不足チェック
$member = new CMember;
if (GetTotalPoint($member->member_id) < $dt['point'])
	$err[] = 'ポイントが不足しているようです。';
else {
	// 入力データチェック
	if ($submit1) {
		if ($dt['bank'] == '')
			$err[] = '銀行名が入力されていないようです。';
		if ($dt['bank_branch'] == '')
			$err[] = '銀行支店名が入力されていないようです。';
		if ($dt['bank_kana'] == '')
			$err[] = 'ギンコウ名が入力されていないようです。';
		if ($dt['bank_branch_kana'] == '')
			$err[] = 'ギンコウシテン名が入力されていないようです。';
		if ($dt['acc_number'] == '')
			$err[] = '口座番号が入力されていないようです。';
		elseif (!check_num($dt['acc_number']))
			$err[] = '口座番号は半角数字で入力してください。';
		if ($dt['acc_name1'] == '')
			$err[] = '口座名義の姓が入力されていないようです。';
		if ($dt['acc_name2'] == '')
			$err[] = '口座名義の名が入力されていないようです。';
		if ($dt['acc_name1_kana'] == '')
			$err[] = 'コウザメイギのセイが入力されていないようです。';
		if ($dt['acc_name2_kana'] == '')
			$err[] = 'コウザメイギのメイが入力されていないようです。';
	} elseif ($submit2) {
		if ($dt['name1'] == '')
			$err[] = 'お名前の姓が入力されていないようです。';
		if ($dt['name2'] == '')
			$err[] = 'お名前の名が入力されていないようです。';
		if ($dt['zip1'] == '' && $dt['zip2'] == '')
			$err[] = '郵便番号が入力されていないようです。';
		elseif (strlen($dt['zip1']) != 3 || strlen($dt['zip2']) != 4 || !check_num($dt['zip1']) || !check_num($dt['zip2']))
			$err[] = '郵便番号は３桁＋４桁の数字で入力してください。';
		if ($dt['addr_pref'] == '')
			$err[] = '都道府県が入力されていないようです。';
		if ($dt['addr_city'] == '')
			$err[] = '区市町村が入力されていないようです。';
		if ($dt['addr_number'] == '')
			$err[] = '番地が入力されていないようです。';
	}
}
?>
<html>

<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<title>MY ポイント交換確認</title>
</head>

<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table cellspacing="0" cellpadding="0" border="0" height="58">
	<tr height="48">
		<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
	</tr>
	<tr height="16">
		<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
	</tr>
</table><br>
<?
if ($err) {
?>
<form>
<table width="604" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="50" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
		<td width="561" align="left" valign="top"><font color="#2f2180"><b>正しく入力されていない項目があるようです。
			<hr>
			</b></font><font size="2">
<?
	foreach ($err as $err_msg) {
?>
			<br>・<?=htmlspecialchars($err_msg)?><br>
<?
	}
?>
		</font></td>
	</tr>
	<tr>
		<td width="50"></td>
		<td width="561"><br>
			<br>
			<hr>
			<input type="button" value="　戻　る　" onclick="history.back()"> </td>
	</tr>
</table>
</form>
<?
} else {
?>
<b><table border="0" cellpadding="0" cellspacing="0" width="632">
	<tr height="36">
		<td width="20" height="36"><img height="16" width="20" src="../img/space.gif"></td>
		<td height="36" align="left" valign="top"><b><img height="26" width="435" src="img/8a-3_title.gif" alt="登録情報の確認"></b></td>
	</tr>
	<tr>
		<td width="20"></td>
		<td><font color="#2f2180"><b>ポイント交換申請内容をご確認ください。<br>
			<br>
			</b></font><font size="2">やりなおす場合は「戻る」ボタンを、これでよろしければ「申請する」ボタンを押してください。<br>
			<br>
			</font>
			<table width="579" border="0" cellspacing="2" cellpadding="0">
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">交換ポイント</font></td>
					<td width="433" bgcolor="#fff6ea"><?=number_format($dt['point'])?> ポイント</td>
				</tr>
			</table>
			<br>
<?
	if ($submit1) {
?>
			<table width="579" border="0" cellspacing="2" cellpadding="0">
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">銀行名</font></td>
					<td width="433" bgcolor="#fff6ea"><?=htmlspecialchars($dt['bank'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">ギンコウメイ</font></td>
					<td width="433" bgcolor="#fff6ea"><?=htmlspecialchars($dt['bank_kana'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">支店名</font></td>
					<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><?=htmlspecialchars($dt['bank_branch'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">シテンメイ</font></td>
					<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><?=htmlspecialchars($dt['bank_branch_kana'])?> </td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">口座の種類</font></td>
					<td width="433" align="left" valign="top" bgcolor="#fff6ea"><?=htmlspecialchars($dt['acc_kind'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">口座番号</font></td>
					<td width="433" align="left" valign="top" bgcolor="#fff6ea"><?=htmlspecialchars($dt['acc_number'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">口座名義</font></td>
					<td width="433" bgcolor="#fff6ea"><?=htmlspecialchars($dt['acc_name1'])?> <?=htmlspecialchars($dt['acc_name2'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">コウザメイギ</font></td>
					<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><?=htmlspecialchars($dt['acc_name1_kana'])?> <?=htmlspecialchars($dt['acc_name2_kana'])?></td>
				</tr>
			</table>
<?
	} elseif ($submit2) {
?>
			<table width="579" border="0" cellspacing="2" cellpadding="0">
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">お名前</font></td>
					<td width="433" bgcolor="#fff6ea"><?=htmlspecialchars($dt['name1'])?> <?=htmlspecialchars($dt['name2'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">郵便番号</font></td>
					<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><?=htmlspecialchars($dt['zip1'])?>-<?=htmlspecialchars($dt['zip2'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">都道府県</font></td>
					<td width="433" align="left" valign="top" bgcolor="#fff6ea"><?=htmlspecialchars($dt['addr_pref'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">区市町村</font></td>
					<td width="433" bgcolor="#fff6ea"><?=htmlspecialchars($dt['addr_city'])?></td>
				</tr>
				<tr>
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">番地</font></td>
					<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><?=htmlspecialchars($dt['addr_number'])?></td>
				</tr>
				<tr> 
					<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">ビル・アパート・マンション名</font></td>
					<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><?=htmlspecialchars($dt['addr_building'])?></td>
				</tr>
			</table>
<?
	}
?>
			<br>
			<form method="post" name="form1" action="8c-2-2.php">
			<div align="center">
			<input type="submit" name="submit" value="申請する">
			　 　　 
			<input type="button" value="戻る" onclick="history.back()">
			</div>
			<input type="hidden" name="param" value="<?=htmlspecialchars(serialize($dt))?>">
			</form>
		</td>
	</tr>
</table></b><br>
<br>
<br>
<br>
<?
}
?>
</body>

</html>
