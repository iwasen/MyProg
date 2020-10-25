<?
$top = './..';
$inc = "$top/../inc";
include("$inc/decode.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_header.php");

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
if ($reg->spouse_flg == '')
	$msg[] = '配偶者の有無が選択されていないようです。';
else if ($reg->spouse_flg == '1') {
	if ($reg->spouse_birthday_y == '' || $reg->spouse_birthday_m == '' || $reg->spouse_birthday_d == '')
		$msg[] = '配偶者の生年月日が選択されていないようです。';

	if ($reg->spouse_shokugyo_cd == '')
		$msg[] = '配偶者の職業が選択されていないようです。';
}

if ($reg->have_child == '')
	$msg[] = 'お子さまの有無が選択されていないようです。';
else if ($reg->have_child == '1') {
	foreach ($reg->child as $i => &$child) {
		$n = $i + 1;

		if ($child->sex == '')
			$msg[] = "{$n}人目のお子さまの性別が選択されていないようです。";

		if ($child->birthday_y == '' || $child->birthday_m == '' || $child->birthday_d == '')
			$msg[] = "{$n}人目のお子さまの生年月日が選択されていないようです。";
	}
}

if ($reg->senior_flg == '')
	$msg[] = '現在同居されているシニア層の方が選択されていないようです。';

if ($reg->housing_form == '')
	$msg[] = '住居形態が選択されていないようです。';

if ($reg->have_car == '')
	$msg[] = '車の所有が選択されていないようです。';
*/
if (!$msg)
	redirect('regi_confirm.php');
?>
<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
<form name="form1" method="post" action="regist3.php">
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