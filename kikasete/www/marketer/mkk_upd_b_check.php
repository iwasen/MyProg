<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// 登録済みチェック
function check_regist($addr) {
	$sql = "SELECT mr_mail_addr FROM t_marketer WHERE mr_mail_addr_low=". sql_char(strtolower($addr)) . " AND mr_status<>9 AND mr_marketer_id<>{$_SESSION['ss_marketer_id']}";
	$result = db_exec($sql);
	return pg_numrows($result);
}

// セッション処理
if (!isset($_SESSION['ss_regist']))
	redirect('mkk_upd_b.php');
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
$reg->mail_addr = get_mail_addr($mail_addr);
$reg->mail_addr2 = get_mail_addr($mail_addr);
$reg->password = get_password($password);
$reg->password2 = get_password($password2);
$reg->shokugyou_cd = $shokugyou_cd;
$reg->gyoushu_cd = $gyoushu_cd;
$reg->shokushu_cd = $shokushu_cd;

// 入力チェック
if ($reg->name1 == '')
	$msg[] = 'お名前（漢字）の姓を入力してください。';

if ($reg->name2 == '')
	$msg[] = 'お名前（漢字）の名を入力してください。';

if ($reg->name1_kana == '')
	$msg[] = 'お名前（フリガナ）の姓を入力してください。';
elseif (!check_kana($reg->name1_kana))
	$msg[] = 'お名前（フリガナ）の姓はカタカナで入力してください。';

if ($reg->name2_kana == '')
	$msg[] = 'お名前（フリガナ）の名を入力してください。';
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

if ($reg->sex == '')
	$msg[] = '性別を選択してください。';

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
	$msg[] = 'パスワードを入力してください。';
elseif ($reg->password2 == '')
	$msg[] = 'パスワード確認を入力してください。';
elseif ($reg->password != $reg->password2)
	$msg[] = 'パスワードとパスワード確認が一致していません。';
elseif (strlen($reg->password) < 6)
	$msg[] = 'パスワードは６文字以上を入力してください。';
elseif (strlen($reg->password) > 20)
	$msg[] = 'パスワードは２０文字以内で入力してください。';
elseif (!check_password($reg->password))
	$msg[] = 'パスワードは半角英数字で入力してください。';

if ($reg->shokugyou_cd == '')
	$msg[] = '職業を選択してください。';

if ($reg->gyoushu_cd == '')
	$msg[] = '業種を選択してください。';

if ($reg->shokushu_cd == '')
	$msg[] = '職種を選択してください。';

if (!$msg)
	redirect('mkk_upd_b2.php');
?>
<? marketer_header('登録情報の更新', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		
    <td valign="top"><img src="images/t_mkk_upd_b.gif" width="538" height="20"><br>
      <br></td>
	</tr>
</table>


<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td><?
$hidden[] = '<input type="hidden" name="cont" value="1">';
error_msg($msg, 'mkk_upd_b.php', $hidden);
?></td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
