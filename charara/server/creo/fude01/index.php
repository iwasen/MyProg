<?
/******************************************************
' System :「きゃららFactory」クレオキャンペーン
' Content:入力画面
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
$inc = "../inc";
include("$inc/regist.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	$_SESSION['ss_regist'] = new reg_fude01;
$reg = &$_SESSION['ss_regist'];
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>「にがおえ」キャンペーン♪</title>

<script language="JavaScript">
<!--
mac=(navigator.appVersion.indexOf('Mac') != -1)?true:false;
ie=(navigator.appName.charAt(0) == "M")?true:false;
ns=(navigator.appName.charAt(0) == "N")?true:false;

if(mac)	{
	fName = "css/mac.css";
}
else	{
	if(ie)	{
	fName = "css/win_ie.css";
	}
	else if(ns)	{
	fName = "css/win_ns.css";
	}
}

if(fName != null)	{
	document.write("<link rel=stylesheet href='"+fName+"' type='text/css'>");
}
//-->
</script>



</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<br>
<table width="640" border="0">
	<tr>
		<td colspan="6"><div align="center"><img src="img/title2004.gif" alt="ご愛顧感謝キャンペーン！" width="294" height="109"></div></td>
	</tr>
	<tr>
		<td colspan="6">
			<div align="center"><br>
			<span class="gyokan105"><span class="large">携帯の待ち受けや、クレオのソフトで簡単に（はがきやシールに）プリントアウトできます♪ <br>
			＊機種によって対応できないものもございます </span></span><br>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="6"><div align="center"><img src="img/picture1.gif" alt="にがおえサンプル1" width="602" height="148"></div></td>
	</tr>
	<tr>
		<td colspan="6"><div align="center"><img src="img/picture2.gif" alt="にがおえサンプル2" width="492" height="310"></div></td>
	</tr>
	<tr>
		<td colspan=6 align="center"><span class="medium">（サンプルは、すべて「筆まめVer.14」を使用しています。）</span></td>
	</tr>
</table>
<br><br>
<table width="640" border="0">
	<tr>
		<td colspan="6"><div align="center"><img src="img/mousikomi.gif" alt="お申し込み方法" width="423" height="25"></div></td>
	</tr>

	<form name="form1" method="post" action="input_check.php">
	<tr>
		<td colspan="6"><div align="center"><span class="gyokan105"><span class="medium">
			<font color="#FF0000"><b>■クレオイーショップ会員様へ♪</b></font><br>
			クレオイーショップ会員番号をお持ちのお客様ならどなたでもお申し込みいただけます。 <br>
		</td>
	</tr>
	<tr>
		<td align="center"colspan=6>
			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" bgcolor="#f5ffff" width=350>
				<tr>
					<td align="center" colspan=6><span class="medium"><font color="#444444">
						<font color="#0033cc">●まだクレオイーショップ会員でない方</font><br>
						こちらより会員登録が可能です。（登録・年会費は無料）<br>
						<a href="https://e-shop.fudemame.net/creo/cust/menu.asp" target="_blank">無料会員登録</a><br>
						</font></span>
					</td>
				</tr>
			</table><br>
		</td>
	</tr>
	<tr>
		<td colspan="6"><div align="center"><span class="gyokan105"><span class="medium">
			<font color="#0000FF"><b>■先着２００名様に当たります♪ </b></font><br>
			当選者にメールで「にがおえＵＲＬ」をお届けします。 <br>
			届いたＵＲＬをクリックしてください。（WindowsのみIE5.0以上対応） <br><br>
			</span></span></div>
		</td>
	</tr>
	<tr>
		<td width="213"><div align="right"><span class="medium">会員番号（半角）</span></div></td>
		<td width="27"><div align="right"><span class="medium"></span></div></td>
		<td><input name="df_creo_id" type="text" size=20 maxlength=12 <?=value($reg->df_creo_id)?>></td>
		<td colspan=2><font color="green"><span class="medium">※会員番号を忘れた方は⇒<a href="https://e-shop.fudemame.net/creo/cust/askpass.asp" target="_blank">こちら</a></spna></font></td>
	</tr>
	<tr>
		<td width="213"><div align="right"><span class="medium">お名前（漢字）</span></div></td>
		<td width="27"><div align="right"><span class="medium">(姓)</span></div></td>
		<td width="120"><input name="df_name1" type="text" size=20 maxlength=20 <?=value($reg->df_name1)?>></td>
		<td width="36"><div align="center"><span class="medium">(名)</span></div></td>
		<td width="222"><input name="df_name2" type="text" size=20 maxlength=20 <?=value($reg->df_name2)?>></td>
	</tr>
	<tr>
		<td><div align="right"><span class="medium">お名前（フリガナ）</span></div></td>
		<td><div align="right"><span class="medium">(姓)</span></div></td>
		<td><input name="df_name1_kana" type="text" size=20 maxlength=20 <?=value($reg->df_name1_kana)?>></td>
		<td><div align="center"><span class="medium">(名)</span></div></td>
		<td><input name="df_name2_kana" type="text" size=20 maxlength=20 <?=value($reg->df_name2_kana)?>></td>
	</tr>
	<tr>
		<td><div align="right"><span class="large">E-mailアドレス</span></div></td>
		<td><span class="large"><br></span></td>
		<td colspan=3><input name="df_mail_addr" type="text" size=40 maxlength=100 <?=value($reg->df_mail_addr)?>></td>
	</tr>
	<tr>
		<td colspan="6">
			<div align="center">
			<input type="image" src="img/btn1.gif" border=0 alt="入力完了" width="107" height="47">
<!--
			<img src="img/spacer.gif" width="50" height="2">
			<input type="image" src="img/btn2.gif" border="0" width="107" height="47" onClick="location.reload()">
-->
			<br>
			<br>
			</div>
		</td>
	</tr>
	</form>
	<tr>
<!--
		<td colspan="6"><div align="center"><img src="img/toousen.gif" alt="当選したら！！" width="421" height="23"></div></td>
	</tr>

	<tr>
		<span class="gyokan105"><span class="large">
			<font color="#FF0000">■デジタル画像を用意しましょう♪</font><br>
			デジカメ・携帯電話で撮影した顔写真をご用意ください（最大：500ｘ500）。 <br>
			画像形式は、<font color="#FF0000">JPG/BMP/PNG/GIF</font>ならどれでもＯＫ♪ <br><br>
			<font color="#0000FF">■画像お申し込み期限は♪</font><br>
			当選メールが届いてから、１週間以内にお申し込みください。 <br>
			メール内の指定ＵＲＬをクリックして、にがおえをダウンロードしてください。 <br>
			<br><br><br><br>
			あなたの写真がどんな顔に？詳細はこちら↓ <br>
			</span>
-->
		<td colspan="6"><div align="center">
			<span class="medium">
			☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★ <br>
			似顔絵キャンペーン事務局<br>
			E-mail : <a href="mailto:creo_camp@chrr.jp">creo_camp@chrr.jp</a>
			<br>
			営業時間 10:00-12:00, 13:00-17:00（土日曜・祝祭日を除く） <br>
			☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★ <br><br></span></span></div>
		</td>
	</tr>
</table>

</div>
</body>
</html>