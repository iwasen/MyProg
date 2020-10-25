<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報変更５
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/regist2.php");

//その他表示
function disp_etc($str) {
	if ($str)
		echo "( " . htmlspecialchars($str) . " )";
}
// ヘーベルハウス
function decode_hebel($code) {
	if ($code)
		echo "はい";
	else
		echo "いいえ";
}
// あり/なし表示
function decode_yesno($code) {
	if ($code == 1)
		echo "あり";
	else
		echo "なし";
}
// 配偶者表示
function disp_parent($code) {
	if ($code == '1')
		echo "妻";
	elseif ($code == '2')
		echo "夫";
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_change01.php');
$reg = &$_SESSION['ss_regist2'];

$reg->back_page = "member_change05.php";
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/zou_img/m_input05_01.gif" width="400" height="124" alt="かくにんしてね"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>
		<td align="center" valign="top">

		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><font size=2>入力された結果はこのようになりました。</font></td>
				</tr>
			</table>
			<br>
			<table border="1" cellspacing="0" cellpadding="3" bordercolor="#5F9EA0" frame="box">
				<tr bgcolor="#ccffcc">
					<td align="center" colspan=3><font size=2>お申込者について</font></td>
				</tr>
				<tr>
					<td width=100 bgcolor="#fff8dd"><font size=2>ＩＤ</font></td>
					<td width=300><font size=2><?=htmlspecialchars($reg->mb_id)?></font></td>
					<td rowspan=3><a href="member_change01_1.php"><img src="img/nyuukai/button/syusei.gif" border="0" name="修正する"></a></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>パスワード</font></td>
					<td ><font size=2><?=htmlspecialchars($reg->mb_password)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>メールアドレス</font></td>
					<td ><font size=2><?=htmlspecialchars($reg->mb_mail_addr)?></font></td>
				</tr>
				<tr>
					<td width=100 bgcolor="#fff8dd"><font size=2>お名前</font></td>
					<td width=300><font size=2><?=htmlspecialchars($reg->name1)?> <?=htmlspecialchars($reg->name2)?></font></td>
					<td rowspan=6><a href="member_change02.php"><img src="img/nyuukai/button/syusei.gif" border="0" name="修正する"></a></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>フリガナ</font></td>
					<td><font size=2><?=htmlspecialchars($reg->name1_kana)?> <?=htmlspecialchars($reg->name2_kana)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>性別</font></td>
					<td><font size=2><?=decode_sex($reg->sex,'')?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>生年月日</font></td>
					<td><font size=2><?=$reg->birthday_y?>年<?=$reg->birthday_m?>月<?=$reg->birthday_d?>日</font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>郵便番号</font></td>
					<td><font size=2><?=$reg->zip1?>-<?=$reg->zip2?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>住所</font></td>
					<td><font size=2><?=decode_area($reg->area_cd,'')?><br><?=htmlspecialchars($reg->address)?></font></td>
				</tr>
				<tr bgcolor="#ccffcc">
					<td align="center" colspan=3><font size=2>同居のご家族について</font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>同居のご家族</font></td>
					<td><font size=2>
<?
$n = 1;
$family = $reg->family[1];
if ($family->family_rel == 1) {
	$n = $n + 1;
?>
					<?=disp_parent($reg->sex)?>（<?=$family->family_age?>歳）<br>
<?
}
for ($i = $n; $i <= 14; $i++) {
	$family = &$reg->family[$i];
	if (!$family->family_rel)
		break;
?>
					<?=decode_family_rel($family->family_rel)?>（<?=$family->family_age?>歳）<br>
<?
}
?>
					</font></td>
					<td><a href="member_change03.php"><img src="img/nyuukai/button/syusei.gif" border="0" name="修正する"></a></td>
				</tr>
				<tr bgcolor="#ccffcc">
					<td align="center" colspan=3><font size=2>住居について</font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>住居形態</font></td>
					<td><font size=2><?=decode_keitai($reg->keitai_cd)?><?=disp_etc($reg->keitai_etc)?></font></td>
					<td rowspan=11><a href="member_change04.php"><img src="img/nyuukai/button/syusei.gif" border="0" name="修正する"></a></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>住居構造</font></td>
					<td><font size=2><?=decode_sozai($reg->sozai_cd)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>へーベルハウス</font></td>
					<td><font size=2><?=decode_hebel($reg->hebel_flag)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>建設年次</font></td>
					<td><font size=2><?=$reg->chikunen_cd != ''?decode_chikunen($reg->chikunen_cd).'年':'--'?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>間取り</font></td>
					<td><font size=2><?=decode_room($reg->room_cd)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>住居面積</font></td>
					<td><font size=2><?=$reg->space_cd != ''?decode_space($reg->space_cd).'m3':'--'?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>給湯器タイプ</font></td>
					<td><font size=2><?=decode_boiler($reg->boiler_cd)?><?=disp_etc($reg->boiler_etc)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>ガス種</font></td>
					<td><font size=2><?=decode_gas_kind($reg->gas_kind_cd)?><?=disp_etc(decode_gas_type($reg->gas_type_cd))?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>水道の請求月</font></td>
					<td><font size=2><?=decode_water($reg->water_month)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>車所有</font></td>
					<td>
<?
if ($reg->car_flag == 0) {
?>
					<font size=2>なし</font><br>
<?
} else {
?>
						<table cellspacing=0 cellpadding=0 border=0>
							<tr>
								<td width=80><font size=2>ガソリン車</font></td>
								<td width=20><font size=2>：</font></td>
								<td><font size=2><?=$reg->gcar_num?>台</font></td>
							</tr>
							<tr>
								<td><font size=2>ディーゼル車</font></td>
								<td><font size=2>：</font></td>
								<td><font size=2><?=$reg->dcar_num?>台</font></td>
							</tr>
							<tr>
								<td><font size=2>バイク</font></td>
								<td><font size=2>：</font></td>
								<td><font size=2><?=$reg->bike_num?>台</font></td>
							</tr>
						</table>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>灯油使用</font></td>
					<td><font size=2><?=decode_yesno($reg->ol_flag)?></font></td>
				</tr>
			</table>
			<br>

			<table border="0" cellspacing="10" cellpadding="0">
				<tr>
					<td><img src="img/nyuukai/zou_img/m_input05_02.gif" alt="どうだった？"> </td>
					<td align="left" valign="middle">
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFCC66">
							<tr>
								<td><img src="img/spacer.gif" width="320" height="3"></td>
							</tr>
						</table>
						<br><br>
						<font size=2>
						入力しなおす場合は<img src="img/nyuukai/button/syusei_s.gif" alt="修正する" border="0" align="middle" width="49" height="21">
						を押して修正してください。
						</font>

						<br>
						<br>
						<br>
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFCC66">
							<tr>
								<td><img src="img/spacer.gif" width="320" height="3"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<img src="img/nyuukai/page/05.gif" alt="５ページ目">
			<hr width="95%" size="1"></td>
				<td><br></td>
				</tr>
				<tr>
				<form name="form1" method="post" action="member_change_end.php">
					<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
					<td align="center" valign="bottom">
					<table border="0" cellspacing="0" cellpadding="0">
					<tr align="center">
					<td><a href="member_change_end.php"><img src="img/nyuukai/button/ok.gif" width="99" height="40" border="0" alt="完了"></a></td>
				</form>
				</tr>
				<tr>
					<td height="7" colspan="3"><img src="img/spacer.gif" width="202" height="7"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

<br>
<br>
<br>
<br>
</div>

<br>
<br>
<br>
<br>
</body>
</html>