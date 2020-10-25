<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header.php");
include("$inc/mk_error.php");

// マーケターID（メールニュース登録）取得
function get_marketer_id($addr) {
	$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_mail_addr_low=". sql_char(strtolower($addr)) . " AND mr_status<>9 AND mr_type=1";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$marketer_id = $fetch[0];
	}
	return $marketer_id;
}

// 登録済みチェック
function check_regist($addr) {
	$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_mail_addr_low=". sql_char(strtolower($addr)) . " AND mr_status<>9 AND mr_type<>1";
	$result = db_exec($sql);
	return pg_numrows($result);
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('mk_kregi1.php');
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
$reg->mail_addr2 = get_mail_addr($mail_addr2);
$reg->password = get_password($password);
$reg->password2 = get_password($password2);
$reg->shokugyou_cd = $shokugyou_cd;
$reg->gyoushu_cd = $gyoushu_cd;
$reg->shokushu_cd = $shokushu_cd;
$reg->marketer_id = get_marketer_id($reg->mail_addr);

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
	redirect('mk_kregi2.php');
?>
<? marketer_header('基本情報の登録', PG_REGIST) ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
    <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top"><img src="../../images/common/spacer.gif" width="10" height="15" /></td>
          <td bgcolor="#dbdbdb">        
          <td valign="top">        
          <td valign="top">&nbsp;</td>
          <td valign="top"><img src="../../images/common/spacer.gif" width="20" height="15"></td>
        </tr>
        <tr>
          <td width="170" valign="top" bgcolor="#e5eaf0">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right" bgcolor="#FFFFFF"><img src="../../images/regist/side_regist.gif" alt="ご登録" width="57" height="18"></td>
              </tr>
              <tr>
                <td><img src="../../images/regist/side_mypage_on.gif" alt="Myページ登録" width="170" height="31"></td>
              </tr>
              <tr>
                
                <td><a href="mk_regist.php#mail" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image59','','../../images/regist/side_mailnews_on.gif',1)"><img src="../../images/regist/side_mailnews.gif" alt="メールニュース登録" name="Image59" width="170" height="31" border="0"></a></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top">
            <table width="578" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>　＞　<a href="mk_regist.php">登録</a>　＞　Myページ登録</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr> 
                <td><img src="../../images/regist/title.gif" alt="ご登録について" width="114" height="28"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr> 
                <td width="578"><img src="../../images/regist/header1.gif" alt="Myページ登録" width="578" height="38"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td align="center"><img src="../../images/regist/sequence1.gif" width="539" height="30"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>
                  <? error_msg($msg, 'mk_kregi1.php') ?>
                </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
          </td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(BLUE); ?>

</body>
</html>
