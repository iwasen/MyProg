<?
$top = './..';
$inc = "$top/../inc";
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_header.php");

// 登録済みチェック
function check_regist($addr) {
	$sql = "SELECT mn_mail_addr FROM t_monitor WHERE mn_mail_addr_low=". sql_char(strtolower($addr)) . " AND mn_status<>9";
	$result = db_exec($sql);
	return pg_numrows($result);
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('regist2.php');
$reg = &$_SESSION['ss_regist'];

// フォームからデータ取得
$reg->name1 = get_name($name1);
$reg->name2 = get_name($name2);
$reg->name1_kana = get_name_kana($name1_kana);
$reg->name2_kana = get_name_kana($name2_kana);
$reg->birthday_y = get_birthday($birthday_y);
$reg->birthday_m = get_birthday($birthday_m);
$reg->birthday_d = get_birthday($birthday_d);
$reg->sex = $sex;
$reg->mikikon = $mikikon;
$reg->mail_addr = get_mail_addr($mail_addr);
$reg->mail_addr2 = get_mail_addr($mail_addr2);
$reg->password = get_password($password);
$reg->password2 = get_password($password2);
$reg->html_mail = $html_mail;
$reg->haimail_flag = $haimail_flag;
$reg->jitaku_zip1 = get_zip($jitaku_zip1);
$reg->jitaku_zip2 = get_zip($jitaku_zip2);
$reg->jitaku_area = $jitaku_area;
$reg->shokugyou_cd = $shokugyou_cd;
$reg->gyoushu_cd = $gyoushu_cd;
$reg->shokushu_cd = $shokushu_cd;
$reg->kinmu_zip1 = get_zip($kinmu_zip1);
$reg->kinmu_zip2 = get_zip($kinmu_zip2);
$reg->kinmu_area = $kinmu_area;

// 内容チェック
if ($reg->name1 == '')
	$msg[] = 'お名前（漢字）の姓が入力されていないようです。';

if ($reg->name2 == '')
	$msg[] = 'お名前（漢字）の名が入力されていないようです。';

if ($reg->name1_kana == '')
	$msg[] = 'お名前（フリガナ）の姓が入力されていないようです。';
elseif (!check_kana($reg->name1_kana))
	$msg[] = 'お名前（フリガナ）の姓はカタカナで入力してください。';

if ($reg->name2_kana == '')
	$msg[] = 'お名前（フリガナ）の名が入力されていないようです。';
elseif (!check_kana($reg->name2_kana))
	$msg[] = 'お名前（フリガナ）の名はカタカナで入力してください。';

$birthday_ok = true;
if ($reg->birthday_y == '') {
	$msg[] = '生年月日の年が入力されていないようです。';
	$birthday_ok = false;
} elseif (!check_num($reg->birthday_y, 1900, date('Y'))) {
	$msg[] = '生年月日の年が正しく入力されていないようです。';
	$birthday_ok = false;
}

if ($reg->birthday_m == '') {
	$msg[] = '生年月日の月が入力されていないようです。';
	$birthday_ok = false;
} elseif (!check_num($reg->birthday_m, 1, 12)) {
	$msg[] = '生年月日の月が正しく入力されていないようです。';
	$birthday_ok = false;
}

if ($reg->birthday_d == ''){
	$msg[] = '生年月日の日が入力されていないようです。';
	$birthday_ok = false;
} elseif (!check_num($reg->birthday_d, 1, 31)) {
	$msg[] = '生年月日の日が正しく入力されていないようです。';
	$birthday_ok = false;
}

if ($birthday_ok && !checkdate($reg->birthday_m, $reg->birthday_d, $reg->birthday_y))
	$msg[] = '生年月日が正しく入力されていないようです。';

if ($reg->mikikon == '')
	$msg[] = '未既婚が選択されていないようです。';

if ($reg->sex == '')
	$msg[] = '性別が選択されていないようです。';

if ($reg->mail_addr == '')
	$msg[] = 'メールアドレスが入力されていないようです。';
elseif ($reg->mail_addr2 == '')
	$msg[] = 'メールアドレス確認が入力されていないようです。';
elseif ($reg->mail_addr != $reg->mail_addr2)
	$msg[] = 'メールアドレスとメールアドレス確認が一致していないようです。';
elseif (!check_mail_addr($reg->mail_addr))
	$msg[] = 'メールアドレスが正しく入力されていないようです。';
elseif (check_regist($reg->mail_addr))
	$msg[] = '入力されたメールアドレスはすでに登録されているようです。';

if ($reg->password == '')
	$msg[] = 'パスワードが入力されていないようです。';
elseif ($reg->password2 == '')
	$msg[] = 'パスワード確認が入力されていないようです。';
elseif ($reg->password != $reg->password2)
	$msg[] = 'パスワードとパスワード確認が一致していないようです。';
elseif (strlen($reg->password) < 6)
	$msg[] = 'パスワードは６文字以上を入力してください。';
elseif (strlen($reg->password) > 20)
	$msg[] = 'パスワードは２０文字以内で入力してください。';
elseif (!check_password($reg->password))
	$msg[] = 'パスワードは半角英数字で入力してください。';

if ($reg->html_mail == '')
	$msg[] = 'HTMLメール受信が選択されていないようです。';

if ($reg->haimail_flag == '')
	$msg[] = 'はいめーる受信が選択されていないようです。';

if ($reg->jitaku_zip1 == '' || $reg->jitaku_zip2 == '')
	$msg[] = '郵便番号（自宅）が入力されていないようです。';
elseif (!check_zip($reg->jitaku_zip1, $reg->jitaku_zip2))
	$msg[] = '郵便番号（自宅）が正しく入力されていないようです。';
else
	$jitaku_zip_ok = true;

if ($reg->jitaku_area == '')
	$msg[] = '居住地域（自宅）が入力されていないようです。';
else
	$jitaku_area_ok = true;

if ($jitaku_zip_ok && $jitaku_area_ok && !check_zip_area($reg->jitaku_zip1 . $reg->jitaku_zip2, $reg->jitaku_area))
	$msg[] = "郵便番号（自宅）と居住地域（自宅）が一致していないようです。";

if ($reg->shokugyou_cd == '')
	$msg[] = '職業が選択されていないようです。';

if ($reg->gyoushu_cd == '')
	$msg[] = '業種が選択されていないようです。';

if ($reg->shokushu_cd == '')
	$msg[] = '職種が選択されていないようです。';

if ($reg->kinmu_zip1 != '' && $reg->kinmu_zip2 != '' && $reg->kinmu_area != '' && !check_zip_area($reg->kinmu_zip1 . $reg->kinmu_zip2, $reg->kinmu_area))
	$msg[] = '郵便番号（通勤・通学先）と通勤・通学先地域が一致していないようです。';

if (!$msg)
	redirect('regist3.php');
?>
<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->

<form name="form1" method="post" action="regist2.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" width="7" align="left"><img src="<?=$img?>/contents2.gif" width="7" height="69" alt="CONTENTS"></td>
		<td width="100%" align="center" valign="top"><br>
			<br>
			<table border="0" cellpadding="15" cellspacing="0" width="500" bgcolor="white">
				<tr>
					<td><font size="2">
						<table border="0" cellpadding="3" cellspacing="2" width="462">
							<tr>
								<td><font color="#2b2b63"><b>正しく入力されていない項目があるようです。 </b></font>
									<hr>
									<font size="2"><br><?=disp_msg($msg, '・', '<br><br>')?></font>
									<hr>
									<center><input type="submit" value="　戻　る　"></center>
								</td>
							</tr>
						</table><br>
						<br>
						</font>
					</td>
				</tr>
			</table>
			<br><br>
		</td>
	</tr>
</table>
</form>
<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>