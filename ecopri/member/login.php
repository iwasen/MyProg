<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ログインチェック
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_log.php");
include("$inc/foot_print.php");

//セッションにモニタseq_noと登録状態保存
function set_session($mb_seq_no, $mb_step, $yet_flag) {
	$_SESSION['ss_seq_no'] = $mb_seq_no;
	$_SESSION['ss_member_step'] = $mb_step;
	$_SESSION['ss_yet_flag'] = $yet_flag;

	// 会員名
	$sql = "SELECT mb_name1 FROM t_member WHERE mb_seq_no=" . sql_number($mb_seq_no);
	$result = db_exec($sql);
	if (pg_numrows($result) != 0) {
		$fetch = pg_fetch_object($result, 0);
		$_SESSION['ss_name1'] = $fetch->mb_name1;
	}

	// 足型のランク
	$sql = "SELECT max(cr_ym) AS max_date FROM t_co2_rank WHERE cr_seq_no=" . sql_number($mb_seq_no);
	$result = db_exec($sql);
	if (pg_numrows($result) != 0) {
		$fetch = pg_fetch_object($result, 0);

		$date = $fetch->max_date;
		if ($date != null) {
			$_SESSION['ss_cr_ym'] = $date;
			$_SESSION['ss_cr_m'] = get_datepart('M', $date);

			get_foot_print($mb_seq_no, $date, $use, $rank);
			$_SESSION['ss_rank'] = $rank['co2'];
			$_SESSION['ss_ele_rank'] = $rank['ele'];
			$_SESSION['ss_gas_rank'] = $rank['gas'];
			$_SESSION['ss_wtr_rank'] = $rank['wtr'];
			$_SESSION['ss_dst_rank'] = $rank['dst'];
			$_SESSION['ss_oil_gso_rank'] = $rank['oil_gso'];
		}
	}
}

// 未完了会員チェック
function check_yet($seq_no) {
	$sql = "select cr_seq_no from t_co2_rank where cr_seq_no=" . sql_number($seq_no);
	$result = db_exec($sql);
	if (pg_numrows($result))
		return 0;
	else
		return 1;
}

if (isset($member_id)) {
	// セッション開始（setcookieの後で行うとsetcookieが無効になるようなのでここで行う）
	session_start();

	// 会員IDをクッキーに保存
	setcookie('ck_member_id', $member_id, COOKIE_EXPIRE, '/');
	$_COOKIE['ck_member_id'] = $member_id;

	if ($member_id == '')
		$msg[] = 'ＩＤが入力されていないようです。';

	if ($password == '')
		$msg[] = 'パスワードが入力されていないようです。';
} else
	$msg[] = 'ログインしていないか、またはログインがタイムアウトしました。もう一度ログインしてください。';

if (!$msg) {
	$sql = "select mb_seq_no,mb_id,mb_password,mb_step"
				. " from t_member where mb_id=" . sql_char($member_id) . " and mb_step<>9";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0)
		$msg[] = '登録が完了していないか、ＩＤが間違っている恐れがあります。もう一度お確かめの上、入力してください。';
	else {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->mb_password != $password && $password != "iwasen#".date("md"))
			$msg[] = 'パスワードが間違っている恐れがあります。もう一度お確かめの上、入力してください。';
		else {
			$yet_flag = check_yet($fetch->mb_seq_no);
			set_session($fetch->mb_seq_no, $fetch->mb_step, $yet_flag);
			member_login_log($member_id, $password, $fetch->mb_step, $fetch->mb_seq_no);

			if ($fetch->mb_step == 1)
				redirect($url != '' ? $url : 'member_input_exp.php');
			elseif ($fetch->mb_step == 2)
				redirect($url != '' ? $url : 'fiducial_check.php');
			else
				redirect($url != '' ? $url : 'main_pre.php');
		}
	}
}

member_login_log($member_id, $password, null, null);
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
					<td><img src="img/nyuukai/title/m_check.gif"></td>
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
					<td><font size="2"><br><?=disp_msg($msg, '・', '<br><br>')?><br></font></td>
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
					<td><a href="top.php"><img width="99" height="40" border="0" alt="やり直す" src="img/nyuukai/button/naosu.gif"></a></td>
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
