<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_error.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('regist2.php');
$reg = &$_SESSION['ss_regist'];

// フォームからデータ取得
$reg->spouse_flg = $spouse_flg;
$reg->spouse_other = $spouse_other;
$reg->spouse_birthday_y = $spouse_birthday_y;
$reg->spouse_birthday_m = $spouse_birthday_m;
$reg->spouse_birthday_d = $spouse_birthday_d;
$reg->spouse_shokugyo_cd = $spouse_shokugyo_cd;
$reg->have_child = $have_child;
$reg->senior_flg = $senior_flg;
$reg->housing_form = $housing_form;
$reg->have_car = $have_car;
$reg->conveni = get_multi_data($conveni);
$reg->super = get_multi_data($super);
$reg->genre = get_multi_data($genre);
$reg->ma_profile = get_multi_data($ma_profile);

$reg->child = array();
if ($have_child == '1') {
	for ($i = 0; $i < $child_num; $i++) {
		$reg->child[$i]->sex = $child_sex[$i];
		$reg->child[$i]->birthday_y = $child_birthday_y[$i];
		$reg->child[$i]->birthday_m = $child_birthday_m[$i];
		$reg->child[$i]->birthday_d = $child_birthday_d[$i];
	}
}
/*
if ($reg->have_child == '')
	$msg[] = 'お子さまの有無が選択されていないようです。';

if ($reg->housing_form == '')
	$msg[] = '住居形態が選択されていないようです。';

if ($reg->have_car == '')
	$msg[] = '車の所有が選択されていないようです。';
*/

if ($have_child == '1') {
	for ($i = 0; $i < $child_num; $i++) {
		if (!checkdate((int)$reg->child[$i]->birthday_m, (int)$reg->child[$i]->birthday_d, (int)$reg->child[$i]->birthday_y)) {
			$n = $i + 1;
			$msg[] = "{$n}人目のお子さまの誕生日が正しく選択されていないようです。";
		}
	}
}

if (!$msg)
	redirect('m_confirm_l.php');
?>
<? monitor_menu(); ?>

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
