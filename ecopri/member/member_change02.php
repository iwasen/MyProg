<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:個人情報変更1/5
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_change01.php');
$reg = &$_SESSION['ss_regist2'];

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form name="form1" method="post" action="member_change02_check.php">
<table width="714"　height ="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/title/m_input02_01.gif" width="262" height="37" alt="我が家の家族構成　その1"></td>
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


		<br>
			<table width="500" border="1" cellspacing="3" cellpadding="8" bordercolor="#00CCFF">
				<tr>
					<td>
					<font size=2>
					<li>
					お申込者についてご記入ください
					</li>
<!--
					<ul>
					家族構成は、各世帯のエネルギー消費動向を知るために重要な情報です。<br>
					エネルギー（電気、ガス、水道）の検針メーターごとに、世帯の家族構成を<br>
					教えてください。
					<br>
					<br>
					<li>
					&lt;&lt;２世帯住宅にお住まいの場合&gt;&gt;<br>
					検針メーター（電気、ガス、水道）が世帯ごとに設置されている<br>
					（例えば、親世帯、子世帯で別々に電気、ガス、水道料金を支払っている）<br>
					場合には、各世帯ごとに会員登録をお願いします。<br>
					</li>
					<br>
					<li>
					&lt;&lt;検針メーターを共有している場合&gt;&gt;<br>
					同居している世帯が複数あっても、１つの検針メーターを共有している場合には、<br>
					同居している方全員を１つの世帯として登録してください。<br>
					代表者１名をお申込者として、世帯全員について入力してください。<br>
					</li>
					<br>
					<li>
					&lt;&lt;店舗併設の方&gt;&gt;<br>
					検針メーターがご自宅用、店舗用のように別々にある方は、<br>
					ご自宅用の検針メーターに含まれているご家族について登録してください。<br>
					</li>
					</ul>
-->
					</font>
					</td>
				</tr>
			</table>

			<br>
			<br>
			<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFCC66">
				<tr>
					<td><img src="img/spacer.gif" width="600" height="4"></td>
				</tr>
			</table>
			<br>
			<table border="0" width="630">
				<tr>
					<td align="left" height=20 width=60><font size=2><nobr>お 名 前</nobr></td><td align="left" width=10>：</font></td>
					<td width=385>
					<input type="text" name="name1" size=15 <?=value($reg->name1)?>>
					<input type="text" name="name2" size=15 <?=value($reg->name2)?>>
					<font size=2>（全角漢字） </font>
					</td>
					<td rowspan="5" width="100" valign="top"><img src="img/nyuukai/zou_img/m_input02_01.gif" alt="おっ！そこそこ（我が家の家族構成その1）" width="175" height="184"></td>
				</tr>
				<tr>
					<td height ="20" align="left"><font size=2><nobr>フリガナ</nobr></font></td><td>：</td>
					<td>
					<input type="text" name="name1_kana" size=15 <?=value($reg->name1_kana)?>>
					<input type="text" name="name2_kana" size=15 <?=value($reg->name2_kana)?>>
					<font size=2>（全角カタカナ） </font>
					</td>
				</tr>
				<tr>
					<td height ="30" align="left"><font size=2><nobr>性　　別</nobr></font></td><td>：</td>
					<td><font size=2>
					<input type="radio" name="sex" <?=value_checked('1', $reg->sex)?>>男性
					<input type="radio" name="sex" <?=value_checked('2', $reg->sex)?>>女性
					</font>
					</td>
				</tr>
				<tr>
					<td height ="30" align="left" height=40><font size=2><nobr>生年月日</nobr></font></td><td>：</td>
					<td><font size=2>西暦 <input type="text" name="birthday_y" size=4 maxlength=4 <?=value($reg->birthday_y)?>>年 <input type="text" name="birthday_m" size=2 maxlength=2 <?=value($reg->birthday_m)?>>月 <input type="text" name="birthday_d" size=2 maxlength=2 <?=value($reg->birthday_d)?>>日</font>
					</td>
				</tr>
				<tr>
					<td height ="30" align="left"><font size=2>郵便番号</font></td><td>：</td>
					<td>
					<input type="text" name="zip1" size="3" maxlength="3" <?=value($reg->zip1)?>>-
					<input type="text" name="zip2" size="4" maxlength="4" <?=value($reg->zip2)?>>
					<font size=2><a href="zip_search.php" title="郵便番号検索" target="zip_search">郵便番号を検索</a></font></td>
				</tr>
				<tr>
					<td valign="top"><font size=2>住　　所</font></td><td valign="top">：</td>
					<td valign="top" colspan=2>
					<select name="area_cd"><? select_area('選択してください', $reg->area_cd) ?></select><br>
					<input type="text" name="address" size="70" <?=value($reg->address)?>><br>
					</td>
				</tr>
			</table><br>


			<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFCC66">
				<tr>
					<td><img src="img/spacer.gif" width="600" height="4"></td>
				</tr>
			</table>
			<br>
			<table border="0" cellspacing="0" cellpadding="0" width="3">
				<tr>
					<td align="center"><img src="img/nyuukai/page/02.gif" alt="２ページ目"></td>
				</tr>
			</table>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">


		<!-- ↓ダイアログボックスを表示させる -->
<?
if (!$reg->back_page) {
?>
					<td><a href="member_change01_1.php"><img src="img/nyuukai/button/back.gif" border="0" alt="戻る" width="99" height="40"></a></td>
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/next.gif" border="0" alt="次へ" width="99" height="40"></td>
<?
} else {
?>
					<td><a href="javascript:history.back()"><img src="img/nyuukai/button/back.gif" width="99" height="40" border="0" alt="戻る"></a></td>
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/syuusei.gif" width="99" height="40" alt="修正" border="0"></td>
<?
}
?>
				</tr>
				<tr align="center">
					<td height="7" colspan="5"><img src="img/spacer.gif" height="7"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>