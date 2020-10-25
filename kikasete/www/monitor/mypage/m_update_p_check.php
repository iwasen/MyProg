<?
$top = './..';
$inc = "$top/../inc";
include("$inc/check.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_partner.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_error.php");

// セッション処理
if (!isset($_SESSION['ss_partner']))
	redirect('m_update.php');
$partner = &$_SESSION['ss_partner'];

// フォームからデータ取得
$partner->jitaku_zip1 = trim(mb_convert_kana($jitaku_zip1, 'n'));
$partner->jitaku_zip2 = trim(mb_convert_kana($jitaku_zip2, 'n'));
$partner->jitaku_area = $jitaku_area;
$partner->jitaku_addr1 = $jitaku_addr1;
$partner->jitaku_addr2 = $jitaku_addr2;
$partner->jitaku_addr3 = $jitaku_addr3;
$partner->jitaku_tel1 = trim(mb_convert_kana($jitaku_tel1, 'n'));
$partner->jitaku_tel2 = trim(mb_convert_kana($jitaku_tel2, 'n'));
$partner->jitaku_tel3 = trim(mb_convert_kana($jitaku_tel3, 'n'));
$partner->jitaku_fax1 = trim(mb_convert_kana($jitaku_fax1, 'n'));
$partner->jitaku_fax2 = trim(mb_convert_kana($jitaku_fax2, 'n'));
$partner->jitaku_fax3 = trim(mb_convert_kana($jitaku_fax3, 'n'));
/*
$partner->shokugyou_cd = $shokugyou_cd;
$partner->gyoushu_cd = $gyoushu_cd;
$partner->shokushu_cd = $shokushu_cd;
$partner->kinmu_name = $kinmu_name;
$partner->kinmu_zip1 = trim(mb_convert_kana($kinmu_zip1, 'n'));
$partner->kinmu_zip2 = trim(mb_convert_kana($kinmu_zip2, 'n'));
$partner->kinmu_area = $kinmu_area;
$partner->kinmu_addr1 = $kinmu_addr1;
$partner->kinmu_addr2 = $kinmu_addr2;
$partner->kinmu_addr3 = $kinmu_addr3;
$partner->kinmu_tel1 = trim(mb_convert_kana($kinmu_tel1, 'n'));
$partner->kinmu_tel2 = trim(mb_convert_kana($kinmu_tel2, 'n'));
$partner->kinmu_tel3 = trim(mb_convert_kana($kinmu_tel3, 'n'));
$partner->kinmu_fax1 = trim(mb_convert_kana($kinmu_fax1, 'n'));
$partner->kinmu_fax2 = trim(mb_convert_kana($kinmu_fax2, 'n'));
$partner->kinmu_fax3 = trim(mb_convert_kana($kinmu_fax3, 'n'));

for ($i = 1; $i <= 5; $i++) {
	$family = &$partner->family[$i];
	$family->family_rel_cd = $family_rel_cd[$i - 1];
	$family->age = $family_age[$i - 1];
}
*/
$reg = &$_SESSION['ss_regist'];
$reg->jitaku_zip1 = $partner->jitaku_zip1;
$reg->jitaku_zip2 = $partner->jitaku_zip2;
$reg->jitaku_area = $partner->jitaku_area;

// 内容チェック
if ($partner->jitaku_zip1 == '' || $partner->jitaku_zip2 == '')
	$msg[] = '郵便番号（自宅）が入力されていないようです。';
elseif (!check_zip($partner->jitaku_zip1, $partner->jitaku_zip2))
	$msg[] = '郵便番号（自宅）が正しく入力されていないようです。';
else
	$jitaku_zip_ok = true;

if ($partner->jitaku_area == '')
	$msg[] = '居住地域（自宅）が入力されていないようです。';
else
	$jitaku_area_ok = true;

if ($partner->jitaku_addr1 == '')
	$msg[] = '住所（市区）が入力されていないようです。';

if ($partner->jitaku_addr2 == '')
	$msg[] = '住所（町村以下）が入力されていないようです。';

if ($partner->jitaku_tel1 == '' || $partner->jitaku_tel2 == '' || $partner->jitaku_tel3 == '')
	$msg[] = '電話番号が入力されていないようです。';

if ($jitaku_zip_ok && $jitaku_area_ok && !check_zip_area($partner->jitaku_zip1 . $partner->jitaku_zip2, $partner->jitaku_area))
	$msg[] = "郵便番号（自宅）と居住地域（自宅）が一致していないようです。";
/*
if ($partner->shokugyou_cd == '')
	$msg[] = '職業が選択されていないようです。';

if ($partner->gyoushu_cd == '')
	$msg[] = '業種が選択されていないようです。';

if ($partner->shokushu_cd == '')
	$msg[] = '職種が選択されていないようです。';

switch ($partner->shokugyou_cd) {
case 5:
case 9:
case 10:
case 11:
	break;
default:
	if ($partner->kinmu_name == '')
		$msg[] = '勤務先名が入力されていないようです。';

	if ($partner->kinmu_zip1 == '' || $partner->kinmu_zip2 == '')
		$msg[] = '郵便番号（通勤先）が入力されていないようです。';
	elseif (!check_zip($partner->kinmu_zip1, $partner->kinmu_zip2))
		$msg[] = '郵便番号（通勤先）が正しく入力されていないようです。';
	else
		$kinmu_zip_ok = true;

	if ($partner->kinmu_area == '')
		$msg[] = '通勤・通学先地域が入力されていないようです。';
	else
		$kinmu_area_ok = true;

	if ($kinmu_zip_ok && $kinmu_area_ok && !check_zip_area($partner->kinmu_zip1 . $partner->kinmu_zip2, $partner->kinmu_area))
		$msg[] = '郵便番号（通勤・通学先）と通勤・通学先地域が一致していないようです。';
	break;
}

if ($partner->kinmu_addr1 == '')
	$msg[] = '通勤先住所（市区）が入力されていないようです。';

if ($partner->kinmu_addr2 == '')
	$msg[] = '通勤先住所（町村以下）が入力されていないようです。';

if ($partner->kinmu_tel1 == '' || $partner->kinmu_tel2 == '' || $partner->kinmu_tel3 == '')
	$msg[] = '通勤先電話番号が入力されていないようです。';
*/
if (!$msg)
	redirect('m_confirm_p.php');
?>
<? monitor_menu() ?>

<form name="form1" method="post" action="regist2_check.php">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>■　プロフィール</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">　</td>
		<td colspan="3"><table width="481" border="0" cellspacing="0" cellpadding="20">
			<? error_msg($msg) ?>
		</td>
	</tr>
</table>
</form>
</body>
</html>
