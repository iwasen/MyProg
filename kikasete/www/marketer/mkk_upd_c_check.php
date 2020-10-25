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

// セッション処理
if (!isset($_SESSION['ss_regist']))
	redirect('mkk_upd_c.php');
$reg = &$_SESSION['ss_regist'];

$reg->kinmu_name = trim($kinmu_name);
$reg->kinmu_unit = trim($kinmu_unit);
$reg->kinmu_post = trim($kinmu_post);
$reg->kinmu_zip1 = get_zip($kinmu_zip1);
$reg->kinmu_zip2 = get_zip($kinmu_zip2);
$reg->kinmu_area = $kinmu_area;
$reg->kinmu_addr1 = trim($kinmu_addr1);
$reg->kinmu_addr2 = trim($kinmu_addr2);
$reg->kinmu_addr3 = trim($kinmu_addr3);
$reg->kinmu_tel1 = get_tel($kinmu_tel1);
$reg->kinmu_tel2 = get_tel($kinmu_tel2);
$reg->kinmu_tel3 = get_tel($kinmu_tel3);
$reg->kinmu_fax1 = get_tel($kinmu_fax1);
$reg->kinmu_fax2 = get_tel($kinmu_fax2);
$reg->kinmu_fax3 = get_tel($kinmu_fax3);

// 入力チェック
if ($reg->kinmu_name == '')
	$msg[] = '勤務先名を入力してください。';

if ($reg->kinmu_zip1 == '' || $reg->kinmu_zip2 == '')
	$msg[] = '勤務先郵便番号を入力してください。';
elseif (!check_zip($reg->kinmu_zip1, $reg->kinmu_zip2))
	$msg[] = '勤務先郵便番号が正しく入力されていません。';
else
	$kinmu_zip_ok = true;

if ($reg->kinmu_area == '')
	$msg[] = '勤務先住所（都道府県）を入力してください。';
else
	$kinmu_area_ok = true;

if ($kinmu_zip_ok && $kinmu_area_ok && !check_zip_area($reg->kinmu_zip1 . $reg->kinmu_zip2, $reg->kinmu_area))
	$msg[] = '勤務先郵便番号と勤務先住所（都道府県）が一致していません。';

if ($reg->kinmu_addr1 == '')
	$msg[] = '勤務先住所（市区）を入力してください。';

if ($reg->kinmu_addr2 == '')
	$msg[] = '勤務先住所（町村・番地）を入力してください。';

if ($reg->kinmu_tel1 == '' || $reg->kinmu_tel2 == '' || $reg->kinmu_tel3 == '')
	$msg[] = '勤務先電話番号を入力してください。';
elseif (!check_tel($reg->kinmu_tel1, $reg->kinmu_tel2, $reg->kinmu_tel3))
	$msg[] = '勤務先電話番号が正しく入力されていません。';

if ($reg->kinmu_fax1 != '' || $reg->kinmu_fax2 != '' || $reg->kinmu_fax3 != '') {
	if (!check_tel($reg->kinmu_fax1, $reg->kinmu_fax2, $reg->kinmu_fax3))
		$msg[] = '勤務先FAX番号が正しく入力されていません。';
}

if (!$msg)
	redirect('mkk_upd_c2.php');
?>
<? marketer_header('登録情報の更新', PG_NULL) ?>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tr>
	<td width="10"><img src="images/common/spacer.gif" width="10" height="10" border="0"></td>
	<td><img src="images/common/spacer.gif" width="778" height="10" border="0"></td>
</tr>
	<tr>
	<td width="10"><img src="images/common/spacer.gif" width="10" height="10" border="0"></td>
    <td width="400" valign="top"><img src="images/t_mkk_upd_c.gif" width="538" height="20"><br>
      <br></td>
	</tr>
</table>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" height="10" border="0"></td>
		<td><?
$hidden[] = '<input type="hidden" name="cont" value="1">';
error_msg($msg, 'mkk_upd_c.php', $hidden);
?></td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>

<? marketer_footer() ?>
